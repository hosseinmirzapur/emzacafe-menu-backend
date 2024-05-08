<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'products' => $products,
            'success' => true
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'image' => ['required', File::image()],
            'description' => 'nullable',
            'available' => ['required', 'boolean'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
        ]);

        $filename = $request->file('image')->hashName();
        $saved = Storage::putFileAs('/product-images', $data('image'), $filename);
        if (!$saved) {
            return response()->json([
                'success' => false,
                'error' => trans('messages.file_not_uploaded')
            ], 400);
        }
        $data['image'] = $saved;

        Product::query()->create($data);

        return response()->json([
            'success' => true,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return response()->json([
            'product' => $product,
            'success' => true
        ]);
    }

    public function update(Product $product, Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes',
            'description' => 'nullable',
            'available' => ['sometimes', 'boolean'],
            'image' => ['sometimes', File::image()],
        ]);

        if ($data['image']) {
            $filename = $request->file('image')->hashName();
            $saved = Storage::putFileAs('/product-images', $data['image'], $filename);
            if (!$saved) {
                return response()->json([
                    'success' => false,
                    'error' => trans('messages.file_not_uploaded')
                ]);
            }
            $data['image'] = $saved;
        }
        $product->update($data);
        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'success' => true,
        ]);
    }
}
