<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() : View
    {
        // gett all products
        $products = Product::latest()->paginate(10);

         //render view with products
         return view('products.index', compact('products'));
    }

    public function create() : View
    {
        return view('product.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        //validate form
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hasName());

        //create product
        Product::create([
            'image' => $image->hashName(),
            'titile' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        //redirect to index
        return redirect()->route('products.index')->with(['succes' => 'Data berhasil disimpan!']);
    }
}
