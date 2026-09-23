<?php

namespace App\Models\Order;

use App\Models\Product\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductStock extends Model
{
    use HasFactory;

    public function Stock(){
        return $this->belongsTo(Stock::class);
    }
}
