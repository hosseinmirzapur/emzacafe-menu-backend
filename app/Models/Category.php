<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
