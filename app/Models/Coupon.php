<?php

namespace App\Models;

use App\Models\Order\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    use HasFactory;

    // Active
    public function scopeActive($q, $take = null){
        $q->where('status', 1);
        $q->latest('id');
        if($take){
            $q->take($take);
        }
    }

    public function getStatusStringAttribute(){
        if($this->status == 0){
            return 'Disabled';
        }elseif($this->expiry_date < Carbon::now()){
            return 'Expired';
        }

        return 'Active';
    }

    public function getUsedAmountAttribute(){
        $order = Order::where('coupon_id', $this->id)->select(DB::raw('SUM(discount_amount) as `amount`'))->first();

        return $order ? ($order->amount ?? 0) : 0;
    }

    public function Orders(){
        return $this->hasMany(Order::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
}
