<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response(Category::limit($request->perpage ?? 5)
            ->offset(($request->perpage ?? 5) * ($request->page ?? 0))
            ->where('name', 'LIKE', '%' . $request->search . "%")
            ->get());
    }

    public function total(Request $request)
    {
        return response(Category::where('name', 'LIKE', '%' . $request->search . "%")
            ->count());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create-category')) {
            return response()->json([
                'code' => 1,
                'message' => 'У вас нет прав на добавление категории',
            ]);
        }
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
            'image' => 'required|file'
        ]);
        $file = $request->file('image');
        // Генерация уникального имени файла
        $fileName = rand(1, 100000) . '_' . $file->getClientOriginalName();
        try {
            // Загрузка файла в S3
            $path = Storage::disk('s3')->putFileAs('category_pictures', $file, $fileName);
            // Получение URL загруженного файла
            $fileUrl = Storage::disk('s3')->url($path);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error uploading file to S3: ',
                'error' => ['code' => $e->getCode(), 'message' => $e->getMessage()]], 500);
        };
//        catch (Exception $e){
//            return response()->json([
//                'code' => 2,
//                'message' => 'Ошибка загрузки файла в хранилище S3',
//            ]);
//        };
        $category = new Category($validated);
        $category->picture_url = $fileUrl;
        $category->save();
        return response()->json([
            'code' => 0,
            'message' => 'Категория успешно добавлена',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Category::with('items')->find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Gate::allows('update-category')) {
            return response()->json([
                'code' => 1,
                'message' => 'У вас нет прав на редактирование категории',
            ]);
        }
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'image' => 'nullable|file|image|max:2048'
        ]);
        try {
            $category = Category::findOrFail($id);
            $category->name = $validated['name'];
            // если передан новый файл)
            if ($request->hasFile('image')) {
                try {
                    // удаление старого файла из хранилища
                    if ($category->picture_url) {
                        $baseUrl = Storage::disk('s3')->getClient()->getEndpoint();
                        $oldPath = str_replace($baseUrl, '', $category->picture_url);
                        if (Storage::disk('s3')->exists($oldPath)) {
                            Storage::disk('s3')->delete($oldPath);
                        }
                    }
                    $file = $request->file('image');
                    $fileName = rand(1, 100000) . '_' . $file->getClientOriginalName();
                    $path = Storage::disk('s3')->putFileAs('category_pictures', $file, $fileName);
                    $category->picture_url = Storage::disk('s3')->url($path);
                } catch (Exception $e) {
                    return response()->json(['message' => 'Error uploading file to S3: ',
                        'error' => ['code' => $e->getCode(), 'message' => $e->getMessage()]], 500);
                }
            }
            $category->save();
            return response()->json([
                'code' => 0,
                'message' => 'Категория успешно обновлена',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 2,
                'message' => 'Ошибка при обновлении: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows('delete-category')) {
            return response()->json([
                'code' => 1,
                'message' => 'У вас нет прав на удаление категории',
            ], 401);
        }
        $category = Category::find($id);
        if ($category->items()->count()) {
            return response()->json(['code' => 1, 'error' => 'Нальзя удалить непустую категорию']);
        }
        $deleted = Category::destroy($id);
        if ($deleted === 0) {
            return response()->json(['code' => 1, 'error' => 'Категория не найдена']);
        }
        return response()->json(['code' => 0, 'message' => 'Категория успешно удалена']);
    }
}
