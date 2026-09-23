<?php

namespace App\Models\Order;

use App\Models\OrderPayment;
use App\Repositories\SMSRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Attachment Path
    public function getAttachmentPathAttribute(){
        if($this->attachment && file_exists(public_path('uploads/order', $this->attachment))){
            return asset('uploads/order/' . $this->attachment);
        }

        return null;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullAddressAttribute()
    {
        return "{$this->street}, {$this->zip}, {$this->city}, {$this->state}, {$this->country}";
    }

    public function getShippingFullAddressAttribute()
    {
        return $this->shipping_street;
    }

    public function getCustomProductTotalAttribute(){
        return $this->product_total - $this->refund_product_total;
    }

    public function getGrandTotalAttribute()
    {
        return ($this->shipping_charge + $this->product_total + $this->tax_amount + $this->other_cost) - ($this->discount_amount + $this->refund_total_amount);
    }

    public function getDueAttribute(){
        return $this->grand_total - ($this->paid_amount + $this->refund_product_total);
    }

    // Order Products
    public function OrderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function OrderPayments(){
        return $this->hasMany(OrderPayment::class);
    }

    public function sendSMS($event_key){
        $body_data = [
            "{customer_name}" => $this->shipping_full_name,
            "{invoice_id}" => $this->id,
            "{courier_invoice}" => $this->courier_invoice,
            "{courier_name}" => $this->courier,
            "{invoice_due}" => $this->due,
            "{invoice_paid}" => $this->paid_amount,
        ];

        SMSRepo::send($this->shipping_mobile_number, $event_key, $body_data, $this->user_id);
    }
}
