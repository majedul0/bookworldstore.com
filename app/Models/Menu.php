<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Active
    public function scopeActive($q, $take = null){
        $q->where('status', 1);
        $q->latest('id');
        if($take){
            $q->take($take);
        }
    }

    public function MenuItems(){
        return $this->hasMany(MenuItem::class)->orderBy('position');
    }

    public function SingleMenuItems(){
        return $this->hasMany(MenuItem::class)->where('menu_item_id', null)->orderBy('position');
    }
}
