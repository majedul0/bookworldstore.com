<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;


    public function getFullAddressAttribute()
    {
        return "{$this->state}, {$this->post_code}, {$this->city}, {$this->state}, {$this->country}";
    }
}
