<?php

namespace App\Http\Controllers;

use App\Models\Branch;

class BranchController extends Controller
{
    public function menu(Branch $branch)
    {
        $branch->load(['categories', 'categories.products']);

        return response()->json([
            'menu' => [
                'branch' => $branch
            ],
        ]);
    }
}
