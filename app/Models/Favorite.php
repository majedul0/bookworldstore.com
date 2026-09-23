<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public function Product(){
        return $this->belongsTo(Product::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
}
