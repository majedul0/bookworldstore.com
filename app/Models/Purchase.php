<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getDueAttribute(){
        return $this->grand_total - $this->paid_amount;
    }

    public function purchase_items(){
        return $this->hasMany(PurchaseItem::class);
    }
}
