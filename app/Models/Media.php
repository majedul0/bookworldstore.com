<?php

namespace App\Models;

use App\Repositories\MediaRepo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    // Paths
    public function getPathsAttribute(){
        $year_month = $this->year . '/' . $this->month;
        $file_name = $this->file_name;

        $output['original'] = asset("uploads/$year_month/$file_name");

        if(file_exists(public_path("uploads/$year_month/small_$file_name"))){
            $output['small'] = asset("uploads/$year_month/small_$file_name");
        }else{
            $output['small'] = asset('img/no-image.png');
        }
        if(file_exists(public_path("uploads/$year_month/medium_$file_name"))){
            $output['medium'] = asset("uploads/$year_month/medium_$file_name");
        }else{
            $output['medium'] = asset('img/no-image.png');
        }
        if(file_exists(public_path("uploads/$year_month/large_$file_name"))){
            $output['large'] = asset("uploads/$year_month/large_$file_name");
        }else{
            $output['large'] = asset('img/no-image.png');
        }

        return $output;
    }
}
