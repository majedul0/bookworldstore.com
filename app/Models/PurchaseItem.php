<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function product_data(){
        return $this->belongsTo(ProductData::class);
    }
}
