<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //  public function save(Request $request)
    //     {
    //         // Validate basic fields
    //         $validated = $request->validate([
    //             'name'         => 'required|string|max:255',
    //             'description'  => 'nullable|string',
    //             'base_price'   => 'required|numeric',
    //             'category'     => 'nullable|string|max:255',
    //             'stock'        => 'required|integer',
    //             'sku'          => 'nullable|string|max:255',
    //             'weight'       => 'nullable|string|max:255',
    //             'dimensions'   => 'nullable|string|max:255',
    //             'hsn_code'     => 'nullable|string|max:255',
    //             'barcode'      => 'nullable|string|max:255',
    //             'unit'         => 'nullable|string|max:50',
    //             'status'       => 'nullable|string|max:50',
    //             'condition'    => 'nullable|string|max:255',
    //             'cost_price'   => 'nullable|numeric',
    //             'base_price'   => 'nullable|numeric',
    //             'tax_rate'     => 'nullable',
    //             'brand'        => 'nullable|string|max:255',
    //             'productType'  => 'nullable|string|max:255',
    //         ]);

    //         // Handle file upload
    //         if ($request->hasFile('image')) {
    //             $validated['image'] = $request->file('image')->store('product', 'public');
    //         }

    //         // Add JSON fields manually
    //         if ($request->filled('pricing_levels')) {
    //             $validated['pricing_levels'] = json_decode($request->input('pricing_levels'), true);
    //         }

    //         if ($request->filled('attributes')) {
    //             $validated['attributes'] = json_decode($request->input('attributes'), true);
    //         }
            

    //         // Save the product
    //         $product = Product::create($validated);

    //         return response()->json($product, 201);
    //     }
    public function save(Request $request)
    {
        // Validate basic fields and multiple images
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'base_price'   => 'required|numeric',
            'category'     => 'nullable|string|max:255',
            'stock'        => 'required|integer',
            'sku'          => 'nullable|string|max:255',
            'weight'       => 'nullable|string|max:255',
            'dimensions'   => 'nullable|string|max:255',
            'hsn_code'     => 'nullable|string|max:255',
            'barcode'      => 'nullable|string|max:255',
            'unit'         => 'nullable|string|max:50',
            'status'       => 'nullable|string|max:50',
            'condition'    => 'nullable|string|max:255',
            'cost_price'   => 'nullable|numeric',
            'tax_rate'     => 'nullable|string',
            'brand'        => 'nullable|string|max:255',
            'productType'  => 'nullable|string|max:255',
            'images.*'     => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Validate each image (up to 5MB)
        ]);

        // Initialize images array
        $imagePaths = [];

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Store each image and save its path
                $path = $image->store('product', 'public');
                $imagePaths[] = $path;
            }
        }

        // Add image paths to validated data
        $validated['images'] = $imagePaths;

        // Handle single image (for backward compatibility, optional)
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product', 'public');
        }

        // Add JSON fields manually
        if ($request->filled('pricing_levels')) {
            $validated['pricing_levels'] = json_decode($request->input('pricing_levels'), true);
        }

        if ($request->filled('attributes')) {
            $validated['attributes'] = json_decode($request->input('attributes'), true);
        }

        // Save the product
        $product = Product::create($validated);

        return response()->json($product, 201);
    }

     public function list()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }
     public function update(Request $request, $id)
    {
        // 1) Validate incoming fields
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'base_price'   => 'required|numeric',
            'category'     => 'nullable|string|max:255',
            'stock'        => 'required|integer',
            'sku'          => 'nullable|string|max:255',
            'weight'       => 'nullable|string|max:255',
            'dimensions'   => 'nullable|string|max:255',
            'hsn_code'     => 'nullable|string|max:255',
            'barcode'      => 'nullable|string|max:255',
            'unit'         => 'nullable|string|max:50',
            'status'       => 'nullable|string|max:50',
            'condition'    => 'nullable|string|max:255',
            'cost_price'   => 'nullable|numeric',
            'tax_rate'     => 'nullable|string',
            'brand'        => 'nullable|string|max:255',
            'product_type' => 'nullable|string|max:255',
            'images.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Multiple images
            'deletedImages' => 'nullable|string', // JSON string of paths to delete
            'pricing_levels' => 'nullable|string', // JSON field
            'attributes'     => 'nullable|string', // JSON field
        ]);

        // 2) Find existing product by ID (or fail with 404)
        $product = Product::findOrFail($id);

        // 3) Handle deleted images
        $imagePaths = $product->images ?? [];
        if ($request->filled('deletedImages')) {
            $deletedImages = json_decode($request->input('deletedImages'), true);
            if (is_array($deletedImages)) {
                // Remove deleted images from array
                $imagePaths = array_diff($imagePaths, $deletedImages);
                // Optionally delete files from storage
                foreach ($deletedImages as $path) {
                    \Storage::disk('public')->delete($path);
                }
            }
        }

        // 4) Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product', 'public');
                $imagePaths[] = $path;
            }
        }

        // 5) Update images array
        $validated['images'] = array_values($imagePaths); // Reindex array

        // 6) Handle single image (for backward compatibility)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product', 'public');
            $validated['image'] = $imagePath;
        }

        // 7) Handle JSON fields
        if ($request->filled('pricing_levels')) {
            $validated['pricing_levels'] = json_decode($request->input('pricing_levels'), true);
        }
        if ($request->filled('attributes')) {
            $validated['attributes'] = json_decode($request->input('attributes'), true);
        }

        // 8) Update the product with validated data
        $product->update($validated);

        // 9) Return JSON of the updated product
        return response()->json($product, 200);
    }
    public function destroy($id)
        {

            // dd($id);
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->delete();

           return response()->json([
                'status' => 200,
                'message' => 'Product deleted successfully',
            ]);

        }

    //
}
