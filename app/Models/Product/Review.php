<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'user_id', 'status', 'rating', 'review', 'title', 'name', 'email', 'address'];

    // Active
    public function scopeActive($q, $take = null){
        $q->where('status', 1);
        $q->latest('id');
        if($take){
            $q->take($take);
        }
    }

    // Stars
    public function getStarsAttribute(){
        $output = '';
        for($r = 1; $r < 6; $r++){
            $output .= '<i class="fa'. (($r > $this->rating) ? 'r' : 's') .' fa-star"></i>';
        }

        return $output;
    }

    public function getStatusStringAttribute(){
        if($this->status == 1){
            return 'Approved';
        }elseif($this->status == 2){
            return 'Pending';
        }

        return 'Rejected';
    }

    public function Product(){
        return $this->belongsTo(Product::class);
    }
}
