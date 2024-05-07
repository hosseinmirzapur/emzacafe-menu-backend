<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function admins()
    {
        return $this->hasMany(Admin::class, 'branch_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'branch_id');
    }
}
