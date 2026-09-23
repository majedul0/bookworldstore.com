<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use App\Models\Product\ProductData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    public function Product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function ProductData(){
        return $this->belongsTo(ProductData::class)->withTrashed();
    }

    public function OrderProductStocks(){
        return $this->hasMany(OrderProductStock::class);
    }

    public function Order(){
        return $this->belongsTo(Order::class);
    }

    public function getCalculatedTaxAmountAttribute(){
        if($this->tax_method == 'Exclusive' && $this->quantity > $this->return_quantity){
            if($this->tax_type == 'Percentage'){
                return (($this->sale_price * ($this->quantity - $this->return_quantity)) * $this->tax_amount) / 100;
            }else{
                return $this->tax_amount;
            }
        }
        return 0;
    }

    public function getSimpleAttributesArrAttribute(){
        if($this->simple_attributes){
            return json_decode($this->simple_attributes, true);
        }

        return [];
    }
}
