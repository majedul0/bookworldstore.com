<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedOrder extends Model
{
    use HasFactory;

    public function failed_order_items(){
        return $this->hasMany(FailedOrderItem::class);
    }
}
