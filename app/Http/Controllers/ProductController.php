<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index() : View
    {
        // gett all products
        $products = Product::latest()->paginate(10);

         //render view with products
         return view('products.index', compact('products'));
    }
}
