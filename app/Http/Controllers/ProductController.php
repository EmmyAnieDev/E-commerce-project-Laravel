<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view('admin.dashboard', compact('products'));
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
        # Eager load the 'colors' and 'images' relationship along with the product's data
        $product = Product::with(['colors', 'images'])->findOrFail($id);

        $colors = $product->colors->pluck('name')->toArray();

        return view('admin.product.edit', compact('product', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'qty' => $request->qty,
            'sku' => $request->sku,
            'description' => $request->description,
        ]);

        // Update main image
        if ($request->hasFile('image')) {

            // Delete old Image from storage/bucket
            File::delete(public_path($product->image));

            $image = $request->file('image');
            $filename = $image->store('', 'public');
            $imagePath = '/uploads/'.$filename;
            $product->update(['image' => $imagePath]);
        }

        // Update additional images
        if ($request->hasFile('images')){

            foreach ($product->images as $image) {
                // Delete old Image from storage/bucket
                File::delete(public_path($image->path));
                $image->delete();
            }

            $images = $request->file('images');
            foreach ($images as $image) {
                $filename = $image->store('', 'public');
                $imagePath = '/uploads/'.$filename;
                $product->images()->create(['path' => $imagePath]);
            }
        }

        // Update colors
        if ($request->has('colors')) {

            $product->colors()->delete();

            foreach ($request->colors as $color) {
                $product->colors()->create(['name' => trim($color)]);
            }
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $product = Product::findOrFail($id);

        # Delete colors
        $product->colors()->delete();

        # Delete each additional Images from storage and database
        foreach ($product->images as $image) {
            File::delete(public_path($image->path));
            $image->delete();
        }

        # Delete main Image from storage
        File::delete(public_path($product->image));

        # Delete the Product from database
        $product->delete();

        return redirect()->back();
    }
}
