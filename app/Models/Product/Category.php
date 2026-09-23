<?php

namespace App\Models\Product;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

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
        $q->orderBy('title');
        if($take){
            $q->take($take);
        }
    }

    // Media
    public function Media(){
        return $this->belongsTo(Media::class);
    }

    public function getImgPathsAttribute(){
        if($this->Media){
            return $this->Media->paths;
        }else{
            return [
                'original' => asset('img/no-image.png'),
                'small' => asset('img/no-image.png'),
                'medium' => asset('img/no-image.png'),
                'large' => asset('img/no-image.png')
            ];
        }
    }

    public function Categories(){
        return $this->hasMany(Category::class)->orderBy('title');
    }

    public function Category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getRouteAttribute(){
        if($this->for == 'blog'){
            return route('blog.category', $this->slug);
        }
        return route('category', $this->id);
    }

    public function Products(){
        return $this->belongsToMany(Product::class, 'product_categories')->where('status', 1)->latest('id');
    }
}
