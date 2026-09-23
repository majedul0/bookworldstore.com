<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    public function AttributeItems(){
        return $this->hasMany(AttributeItem::class)->latest('id');
    }

    // Active
    public function scopeActive($q, $take = null){
        // $q->where('status', 1);
        $q->latest('id');
        if($take){
            $q->take($take);
        }
    }
}
