<?php

namespace App\Models\Product;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function Product(){
        return $this->belongsTo(Product::class)->where('status', 1);
    }

    public function ProductData(){
        return $this->belongsTo(ProductData::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
}
