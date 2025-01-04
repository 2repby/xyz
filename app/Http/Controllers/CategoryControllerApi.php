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
        ->get());
    }
    public function total()
    {
        return response(Category::all()->count());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Gate::allows('create-category')) {
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
        $fileName = rand(1, 100000). '_' . $file->getClientOriginalName();
        try {
            // Загрузка файла в S3
            $path = Storage::disk('s3')->putFileAs('category_pictures', $file, $fileName);
            // Получение URL загруженного файла
            $fileUrl = Storage::disk('s3')->url($path);
        }
        catch (Exception $e){
            return response()->json(['message' => 'Error uploading file to S3: ',
                'error' => ['code' => $e->getCode(), 'message'=> $e->getMessage()]], 500);
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
        return response(Category::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
