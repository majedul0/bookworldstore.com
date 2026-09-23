<?php

namespace App\Models\Product;

use App\Repositories\CartRepo;
use App\Repositories\MediaRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cookie;

class ProductData extends Model
{
    use HasFactory, SoftDeletes;

    public function getAttributeItemsAttribute()
    {
        $attr_items = explode(',', $this->attribute_item_ids);

        $item_id_arr = array();
        foreach ($attr_items as $attr_item) {
            if ($attr_item) {
                $item_ids = explode(':', $attr_item);

                $item_id_arr[] = $item_ids[1];
            }
        }

        $items = AttributeItem::with('Attribute')->withTrashed()->whereIn('id', $item_id_arr)->get();

        return $items;
    }

    public function getAttributeItemsArrAttribute()
    {
        $attr_items = explode(',', $this->attribute_item_ids);

        $item_id_arr = array();
        foreach ($attr_items as $attr_item) {
            if ($attr_item) {
                $item_ids = explode(':', $attr_item);

                $item_id_arr[] = $item_ids[1];
            }
        }

        return $item_id_arr;
    }

    public function getAttributeItemsStringAttribute()
    {
        $string = '';
        $attribute_items = $this->attribute_items;
        foreach ($attribute_items as $key => $attributes_item) {
            $string .= ($attributes_item->Attribute->name ?? 'n/a') . ": $attributes_item->name" . (count($attribute_items) != ($key + 1) ? ", " : '');
        }

        return $string;
    }

    public function getAttributeItemsBStringAttribute()
    {
        $string = '';
        $attribute_items = $this->attribute_items;
        foreach ($attribute_items as $key => $attributes_item) {
            $string .= ($attributes_item->Attribute->name ?? 'n/a') . "::$attributes_item->name" . (count($attribute_items) != ($key + 1) ? ",," : '');
        }

        return $string;
    }

    // public function getAttributeItemsStringAttribute()
    // {
    //     $string = '';
    //     $attribute_items = $this->attribute_items;
    //     foreach ($attribute_items as $key => $attributes_item) {
    //         $string .= $attributes_item->Attribute->name . ": $attributes_item->name" . (count($attribute_items) != ($key + 1) ? ", " : '');
    //     }

    //     return $string;
    // }

    public function getCustomSalePriceAttribute()
    {
        if($this->promotion_price && (date('Ymd', strtotime($this->promotion_start_date)) <= date('Ymd')) && (date('Ymd', strtotime($this->promotion_end_date)) >= date('Ymd'))){
            return $this->promotion_price;
        }
        return $this->sale_price;
    }

    // Image Paths
    public function getImgPathsAttribute()
    {
        return MediaRepo::sizes($this->image_path, $this->image);
    }

    public function Product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function Tax(){
        return $this->belongsTo(Tax::class);
    }

    public function getTaxTypeAttribute($data){
        // dd($this->Tax);
        if($this->Tax){
            return $this->Tax->type;
        }
        return $data;
    }

    public function getTaxAmountAttribute($data){
        if($this->Tax){
            return $this->Tax->amount;
        }
        return $data;
    }

    public function getCalculatedTaxAmountAttribute(){
        $tax = $this->Tax;
        if($tax && $this->tax_method == 'Exclusive'){
            if($tax->type == 'Percentage'){
                return ($this->custom_sale_price * $tax->amount) / 100;
            }else{
                return $tax->amount;
            }
        }
        return 0;
    }

    public function getCalculatedTaxAmountDiscountAttribute(){
        $tax = $this->Tax;
        if($tax && $this->tax_method == 'Exclusive'){
            if($tax->type == 'Percentage'){
                // Get Coupon
                $coupon = null;
                if(Cookie::get('coupon')){
                    $check_coupon = CartRepo::checkCoupon(Cookie::get('coupon'));

                    if($check_coupon['status']){
                        $coupon = $check_coupon['coupon'];
                    }
                }

                // After Discount
                if($coupon){
                    if($coupon->discount_type == 'fixed'){
                        $after_discount = $this->custom_sale_price - $coupon->amount;
                    }else{
                        // dd($coupon->amount);
                        // dd(($this->custom_sale_price * $coupon->amount) / 100);

                        $after_discount = $this->custom_sale_price - (($this->custom_sale_price * $coupon->amount) / 100);
                    }
                }else{
                    $after_discount = $this->custom_sale_price;
                }


                // dd(($after_discount * $tax->amount) / 100);

                return ($after_discount * $tax->amount) / 100;
            }else{
                return $tax->amount;
            }
        }
        return 0;
    }

    public function stock_product_data(){
        return $this->belongsTo(ProductData::class, 'stock_product_data_id');
    }
}
