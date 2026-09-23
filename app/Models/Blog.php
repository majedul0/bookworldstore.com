<?php

namespace App\Models;

use App\Models\Product\Category;
use App\Repositories\Generate;
use App\Repositories\MediaRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

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

    // Image Paths
    public function getImgPathsAttribute(){
        return MediaRepo::sizes($this->image_path, $this->image);
    }

    public function getRouteAttribute(){
        // $slug = Generate::slug($this->title);

        // return '#';
        return route('blog', $this->id);
    }

    // Category
    public function Categories(){
        return $this->belongsToMany(Category::class, 'blog_categories');
    }
}
