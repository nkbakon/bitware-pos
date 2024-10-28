<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('id', 'desc')->paginate(25);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required',
            'price' => 'required',
        ]);

        if($request->hasFile('image'))
        {
            $image = $request->image;
            $path_image = $image->store('images', 'public');
        }

        $item = new Item();
        $item->name = $request->name;
        $item->image = $path_image;
        $item->price = $request->price;
        $item->save();

        if($item){
            return redirect()->route('items.index')->with('status', 'Item created successfully.');
        }
        return redirect()->route('items.index')->with('delete', 'Item create faild, try again.');
    }
}
