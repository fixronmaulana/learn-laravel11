<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        return view('products.create');
    }

    public function store(Request $request) : RedirectResponse
    {
        //validate form
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        Product::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data berhasil disimpan!']);
    }

    public function show(string $id) : View
    {
        //get product by id
        $product = Product::findOrFail($id);

        //render view with product
        return view('products.show', compact('product'));

    }

    public function edit(string $id) : View
    {
        //get product by ID
        $product = Product::findOrfail($id);

        //render view with product
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id) : RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         =>      'image|mimes:png,jpg,jpeg',
            'title'         =>      'required|min:5',
            'description'   =>      'required|min:10',
            'price'         =>      'required|numeric',
            'stock'         =>      'required|numeric'
        ]);

        //get product by ID
        $product = Product::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('image'))
        {
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //deleted old image
            Storage::delete('public/products/'.$product->image);

            //update product with new image
            $product->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);

        } else {

            //update product without image
            $product->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock
            ]);
        }

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data berhasil diubah!']);

    }

    public function destroy($id) : RedirectResponse
    {
        //get product by ID
        $product = Product::findOrFail($id);

        //delete image
        Storage::delete('public/products' . $product->image);

        //delete product
        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Data berhasil di hapus!']);
    }
}
