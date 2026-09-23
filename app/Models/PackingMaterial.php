<?php

namespace App\Models;

use App\Models\Product\Product;
use App\Models\Product\ProductData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Repositories\InventoryRepo;

class PackingMaterial extends Model
{
    use HasFactory, SoftDeletes;

    public function pm_product(){
        return $this->belongsTo(Product::class, 'pm_product_id');
    }

    public function product_data(){
        return $this->belongsTo(ProductData::class);
    }
}
