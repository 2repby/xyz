<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perpage = $request->perpage ?? 2;
        return view('items', [
//            'items' => Item::paginate($perpage)->withQueryString()
        'items' => Item::all()
        ]);
    }
    // How to solve n+1 problem:
    //            'items' => Item::with('category')->get()->all()

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('item_create', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:items|max:255',
            'price' => 'required|integer',
            'category_id' => 'integer'

        ]);
        $item= new Item($validated);
        $item->save();
        return redirect('/item')->withErrors(['success' =>
            'Товар был успешно добавлен']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('item_edit', [
            'item' => Item::all()->where('id', $id)->first(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'category_id' => 'integer'
        ]);
        $item = Item::all()->where('id', $id)->first();
        $item->name = $validated['name'];
        $item->price = $validated['price'];
        $item->category_id = $validated['category_id'];
        $item->save();
        return redirect('/item');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (! Gate::allows('destroy-item', Item::all()->where('id', $id)->first())) {
            return redirect('/')->with('message',
                'У вас нет разрешения на удаление товара номер ' . $id);
        }
        Item::destroy($id);
        return redirect('/item')->withErrors('message',
            'Товар ' . $id . ' был успешно удален');
    }
}
