<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class ItemControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perpage = $request->perpage ?? 2;
        return response(Item::paginate($perpage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:items|max:255',
            'price' => 'required|integer',
            'category_id' => 'integer',
            'file' => 'required|file'

        ]);
        $file = $request->file('file');

        // Генерация уникального имени файла
        $fileName = rand(1, 100000). '_' . $file->getClientOriginalName();
        try {
            // Загрузка файла в S3
            $path = Storage::disk('s3')->putFileAs('item_pictures', $file, $fileName);
            // Получение URL загруженного файла
            $fileUrl = Storage::disk('s3')->url($path);
        }
        catch (Exception $e){
            return response()->json(['message' => 'Error uploading file to S3: ',
                'error' => ['code' => $e->getCode(), 'message'=> $e->getMessage()]], 500);
        };
        $item = new Item($validated);
        $item->picture_url = $fileUrl;
        $item->save();
        return response()->json([
            'message' => 'Товар успешно добавлен',
            'url' => $fileUrl,
            'path' => $path,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
