<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function menu(Branch $branch)
    {
        $branch->load(['categories', 'categories.products']);

        return response()->json([
            'menu' => [
                'branch' => $branch
            ],
            'success' => true
        ]);
    }

    public function index()
    {
        $branches = Branch::with(['admins', 'categories'])->get();
        return response()->json([
            'branches' => $branches,
            'success' => true
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'active' => 'required',
            'note' => 'required'
        ]);
        Branch::query()->create($data);
        return response()->json([
            'success' => true
        ]);
    }

    public function show(Branch $branch)
    {
        $branch->load(['admins', 'categories']);

        return response()->json([
            'branch' => $branch,
        ]);
    }

    public function update(Branch $branch, Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes',
            'active' => ['sometimes', 'boolean'],
            'note' => 'sometimes',
        ]);
        $branch->update($data);
        return response()->json([
            'success' => true
        ]);
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return response()->json([
            'success' => true
        ]);
    }

}
