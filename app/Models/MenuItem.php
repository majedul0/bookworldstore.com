<?php

namespace App\Models;

use App\Models\Product\Brand;
use App\Models\Product\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public function Items()
    {
        return $this->hasMany(MenuItem::class, 'menu_item_id')->orderBy('position');
    }

    public function Blog()
    {
        return $this->belongsTo(Blog::class, 'relation_id', 'id');
    }

    public function Page()
    {
        return $this->belongsTo(Page::class, 'relation_id');
    }

    public function Category()
    {
        return $this->belongsTo(Category::class, 'relation_id');
    }

    public function Brand()
    {
        return $this->belongsTo(Brand::class, 'relation_id');
    }

    public function getMenuInfoAttribute()
    {
        $output = [
            'text' => '',
            'url' => ''
        ];

        if ($this->relation_with == 'page') {
            $output['text'] = $this->Page->title ?? null;
            $output['url'] = $this->Page->route ?? '';
        } elseif ($this->relation_with == 'blog') {
            $output['text'] = $this->Blog->title ?? null;
            $output['text'] = $this->Blog->route ?? '';
        } elseif ($this->relation_with == 'category') {
            $output['text'] = $this->Category->title ?? null;
            $output['url'] = $this->Category->route ?? '';
        } elseif ($this->relation_with == 'brand') {
            $output['text'] = $this->Brand->title ?? null;
            $output['url'] = $this->Brand->route ?? '';
        } else {
            $output['url'] = $this->url;
            $output['text'] = $this->text;
        }

        return $output;
    }
}
