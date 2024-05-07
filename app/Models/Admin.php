<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{

    use HasApiTokens;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function genToken(string $name): string
    {
        return $this->createToken($name)->plainTextToken;
    }
}
