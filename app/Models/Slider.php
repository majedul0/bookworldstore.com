<?php

namespace App\Models;

use App\Repositories\MediaRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Active
    public function scopeActive($q, $take = null){
        $q->where('status', 1);
        $q->orderBy('position');
        if($take){
            $q->take($take);
        }
    }

    // Image Paths
    public function getImgPathsAttribute(){
        return MediaRepo::sizes($this->image_path, $this->image);
    }

    // Image Paths 2
    public function getImgPaths2Attribute(){
        return MediaRepo::sizes($this->image_path_2, $this->image_2);
    }
}
