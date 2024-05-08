<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['branch', 'products'])->get();

        return response()->json([
            'categories' => $categories,
            'success' => true,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'branch_id' => ['required', Rule::exists('branches', 'id')],
            'icon' => ['required', File::image()],
            'image' => ['required', File::image()]
        ]);

        // upload image
        $filename = $request->file('image')->hashName();
        $saved = Storage::putFileAs('/category-images', $data['image'], $filename);
        if (!$saved) {
            return response()->json([
                'error' => trans('message.file_not_uploaded'),
                'success' => false
            ], 400);
        }
        $data['image'] = $saved;

        // upload icon
        $iconFilename = $request->file('icon')->hashName();
        $saved = Storage::putFileAs('/category-icons', $data['icon'], $iconFilename);
        if (!$saved) {
            return response()->json([
                'error' => trans('message.file_not_uploaded'),
                'success' => false
            ]);
        }
        $data['icon'] = $saved;

        Category::query()->create($data);

        return response()->json([
            'success' => true
        ]);
    }

    public function show(Category $category)
    {
        $category->load(['branch', 'products']);
        return response()->json([
            'category' => $category,
            'success' => true,
        ]);
    }

    public function update(Category $category, Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes',
            'icon' => ['sometimes', File::image()],
            'image' => ['sometimes', File::image()]
        ]);

        if ($data['image']) {
            $filename = $request->file('image')->hashName();
            $saved = Storage::putFileAs('/category-images', $data['image'], $filename);
            if (!$saved) {
                return response()->json([
                    'error' => trans('message.file_not_uploaded'),
                    'success' => false
                ]);
            }
            $data['image'] = $saved;
        }

        if ($data['icon']) {
            $iconFilename = $request->file('icon')->hashName();
            $saved = Storage::putFileAs('/category-icons', $data['icon'], $iconFilename);
            if (!$saved) {
                return response()->json([
                    'error' => trans('message.file_not_uploaded'),
                ]);
            }
            $data['icon'] = $saved;
        }

        $category->update($data);

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'success' => true,
        ]);
    }
}
