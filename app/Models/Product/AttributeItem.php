<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeItem extends Model
{
    use HasFactory, SoftDeletes;

    public function Attribute(){
        return $this->belongsTo(Attribute::class);
    }
}
