<?php

namespace App\Models\Product;

use App\Models\Media;
use App\Models\Order\OrderProduct;
use App\Models\PurchaseItem;
use App\Repositories\MediaRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Sluggable, Notifiable;

    protected $fillable = [
        'slug'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title', 'id']
            ]
        ];
    }

    // Active
    public function scopeActive($q, $take = null){
        $q->where('status', 1);
        // $q->where('stock', '>', 0);
        $q->latest('id');
        if($take){
            $q->take($take);
        }
    }

    // Paths
    public function getImgPathsAttribute(){
        return MediaRepo::sizes($this->image_path, $this->image);
    }

    // Product Data
    public function ProductData(){
        return $this->hasOne(ProductData::class, 'product_id')->whereIn('type', ['Simple', 'Bundle']);
    }
    public function VariableProductData(){
        return $this->hasMany(ProductData::class, 'product_id')->where('type', 'Variable');
    }

    // // Sale Price
    // public function getSalePriceAttribute(){
    //     if($this->type == 'Simple'){
    //         return $this->ProductData->sale_price ?? 'N/A';
    //     }
    //     return 'N/A';
    // }

    // // Regular Price
    // public function getRegularPriceAttribute(){
    //     if($this->type == 'Simple'){
    //         return $this->ProductData->regular_price ?? 'N/A';
    //     }
    //     return 'N/A';
    // }

    public function getDiscountPercentageAttribute(){
        if($this->regular_price){
            return (int)((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }
        return 0;
    }

    // Category
    public function Categories(){
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    // Simple Attributes
    public function Attributes(){
        return $this->belongsToMany(Attribute::class, 'product_attributes')->where('type', 'Simple');
    }

    // Simple Attributes
    public function VariableAttributes(){
        return $this->belongsToMany(Attribute::class, 'product_attributes')->where('type', 'Variable');
    }

    public function Gallery(){
        return $this->belongsToMany(Media::class, 'product_media');
    }

    // Price
    public function getPricesAttribute(){
        $output = [
            'regular_price' => 0,
            'sale_price' => 0,
            'discount_amount' => 0,
            'discount_percentage' => 0,
            'less' => 0,
            'sku' => null
        ];

        if($this->type == 'Variable'){
            $query = $this->VariableProductData->sortBy('sale_price')[0] ?? null;
        }else{
            $query = $this->ProductData;
        }

        if($query){
            $output['regular_price'] = $query->regular_price ?? 0;
            $output['sale_price'] = $query->sale_price;

            $output['sku'] = $query->sku_code;
        }

        return $output;
    }

    public function getRouteAttribute(){
        // $slug = Generate::slug($this->title);

        return route('product', $this->id);
    }

    // Rating stars
    public function getRatingStarsAttribute(){
        $average_rating = round($this->average_rating, 1);

        $whole = floor($average_rating);      // 1
        $fraction = $average_rating - $whole; // .25
        $star_width = $fraction * 100;

        // dd($star_width);

        $output = '<div class="rating_stars">';
        for($r = 1; $r < 6; $r++){
            $output .= '<div class="r_star">
                            <svg viewBox="0 0 32 32"><path d="M16 23.21l7.13 4.13-1.5-7.62a.9.9 0 0 1 .27-.83l5.64-5.29-7.64-.93a.9.9 0 0 1-.71-.52L16 5.1l-3.22 7a.9.9 0 0 1-.71.52l-7.6.93 5.63 5.29a.9.9 0 0 1 .27.83l-1.51 7.67zm0 2l-7.9 4.58a.9.9 0 0 1-1.34-.95l1.73-9-6.65-6.3A.9.9 0 0 1 2.36 12l9-1.08 3.81-8.32a.9.9 0 0 1 1.64 0l3.81 8.32 9 1.08a.9.9 0 0 1 .51 1.55l-6.66 6.3 1.68 9a.9.9 0 0 1-1.34.94z" fill="#c5cad4" fill-rule="evenodd"></path></svg>

                            <div class="r_star_top" style="width: '. (($r > $this->average_rating) ? ((($whole + 1) == $r) ? $star_width : 0) : '100') .'%;">
                                <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 25.19l-8.24 4.65a.9.9 0 0 1-1.33-1l1.8-9-6.86-6.26A.9.9 0 0 1 1.88 12l9.32-1.08 4-8.39a.9.9 0 0 1 1.63 0l4 8.39L30.12 12a.9.9 0 0 1 .5 1.56l-6.88 6.29 1.74 9a.9.9 0 0 1-1.33 1z" fill="#fecf0a" fill-rule="evenodd"></path></svg>
                            </div>
                        </div>';
            // $output .= '<i class="fa'. (($r > $this->average_rating) ? 'r' : 's') .' fa-star"></i>';
        }
        $output .= '</div>';

        return $output;
    }

    public function Brand(){
        return $this->belongsTo(Brand::class);
    }

    public function OrderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function getAttributeItemsArrAttribute()
    {
        if($this->attribute_items_id){
            return json_decode($this->attribute_items_id, true);
        }
        return [];
    }

    public function bundle_product_items(){
        return $this->belongsToMany(Product::class, 'product_bundle_items', 'product_id', 'bundle_product_id');
    }

    // Manually selected related products (admin dashboard)
    public function RelatedProducts(){
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id')
            ->withPivot('position')
            ->orderBy('product_related.position');
    }

    public function purchase_items(){
        return $this->hasMany(PurchaseItem::class);
    }

    public function packing_materials(){
        return $this->hasMany(PackingMaterial::class);
    }

    public function getOffAttribute(){
        if($this->regular_price){
            return (int)((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
        }
        return 0;
    }
}
