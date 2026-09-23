<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductQuotes extends Model
{
    use HasFactory, SoftDeletes;

    public function Product(){
        return $this->belongsTo(Product::class);
    }
}
