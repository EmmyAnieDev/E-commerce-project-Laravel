<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductPageController extends Controller
{
    function index() {

        $products = Cache::rememberForever('products', function () {
            return Product::all();
        });

        return view('pages.home', compact('products'));
    }

    function show(String $id) {

        $product = Product::with(['colors', 'images'])->findOrFail($id);

        return view('pages.product-details', compact('product'));
    }

}
