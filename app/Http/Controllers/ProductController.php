<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'qty' => $request->qty,
            'sku' => $request->sku,
            'description' => $request->description,
        ]);

        // Store main image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->store('', 'public');
            $imagePath = '/uploads/'.$filename;
            $product->update(['image' => $imagePath]);
        }

        // Store additional images
        if ($request->hasFile('images')){
            $images = $request->file('images');
            foreach ($images as $image) {
                $filename = $image->store('', 'public');
                $imagePath = '/uploads/'.$filename;
                $product->images()->create(['path' => $imagePath]);
            }
        }

        // Store colors
        if ($request->has('colors')) {
            foreach ($request->colors as $color) {
                $product->colors()->create(['name' => trim($color)]);
            }
        }

        return redirect()->back();

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
