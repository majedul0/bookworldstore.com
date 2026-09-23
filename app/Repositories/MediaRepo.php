<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Support\Facades\File;
use Info;
use Intervention\Image\ImageManagerStatic as Image;

class MediaRepo {
    public static function upload($file, $name_prefix = ''){
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $filename_string = date('Y') . '/' . date('m') . '/' . $filename;
        $path = public_path() . '/uploads' . '/' . date('Y') . '/' . date('m') . '/';
        File::makeDirectory($path, $mode = 0777, true, true);
        $path = '/uploads' . '/' . date('Y') . '/' . date('m') . '/';

        if(strpos($file->getMimeType(), 'image') !== false){
            $small_width = Info::Settings('media', 'small_width') ?? 150;
            $small_height = Info::Settings('media', 'small_height') ?? 150;

            $medium_width = Info::Settings('media', 'medium_width') ?? 410;
            $medium_height = Info::Settings('media', 'medium_height') ?? 410;

            $large_width = Info::Settings('media', 'large_width') ?? 980;
            $large_height = Info::Settings('media', 'large_height') ?? 980;

            // Resize Image small
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit($small_width, $small_height);
            $image_resize->save(public_path($path . 'small_' . $filename));

            // Resize Image medium
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit($medium_width, $medium_height);
            $image_resize->save(public_path($path . 'medium_' . $filename));

            // Resize Image large
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit($large_width, $large_height);
            $image_resize->save(public_path($path . 'large_' . $filename));
        }

        // Original file
        $destination = public_path() . $path;
        $file->move($destination, $filename);

        // Store Media Data
        $media = new Media;
        $media->file_name = $filename;
        $media->year = date('Y');
        $media->month = date('m');
        $media->save();

        return [
            'media_id' => $media->id,
            'file_name' => $filename,
            'file_path' => date('Y') . '/' . date('m'),
            'full_file_name' => $filename_string
        ];
    }

    // public static function uploadUrl($url){
    //     $ch = curl_init($url);
    //     curl_setopt($ch, CURLOPT_NOBODY, true);
    //     curl_exec($ch);
    //     $retCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);

    //     if ($retCode == 200){
    //         // dd(123);
    //         $name = uniqid() . '.png';
    //         $image = file_get_contents($url);
    //         if ($image !== false){
    //             $image = 'data:image/jpg;base64,' . base64_encode($image);
    //         }
    //         $filename = $name;
    //         $filename_string = date('Y') . '/' . date('m') . '/' . $filename;
    //         $path = public_path() . '/uploads' . '/' . date('Y') . '/' . date('m') . '/';
    //         File::makeDirectory($path, $mode = 0775, true, true);
    //         $path = '/uploads' . '/' . date('Y') . '/' . date('m') . '/';

    //         // Original file
    //         $destination = public_path() . $path;
    //         $UPLOAD_DIR = $destination;
    //         $img = $image;
    //         $img = str_replace('data:image/jpg;base64,', '', $img);
    //         $img = str_replace('data:image/png;base64,', '', $img);
    //         $img = str_replace(' ', '+', $img);
    //         $data = base64_decode($img);
    //         $file = $UPLOAD_DIR . $name;
    //         file_put_contents($file, $data);

    //         $small_width = Info::Settings('media', 'small_width') ?? 150;
    //         $small_height = Info::Settings('media', 'small_height') ?? 150;

    //         $medium_width = Info::Settings('media', 'medium_width') ?? 410;
    //         $medium_height = Info::Settings('media', 'medium_height') ?? 410;

    //         $large_width = Info::Settings('media', 'large_width') ?? 980;
    //         $large_height = Info::Settings('media', 'large_height') ?? 980;

    //         // Resize Image small
    //         $image_resize = Image::make($image);
    //         $image_resize->fit($small_width, $small_height);
    //         $image_resize->save(public_path($path . 'small_' . $filename));

    //         // Resize Image medium
    //         $image_resize = Image::make($image);
    //         $image_resize->fit($medium_width, $medium_height);
    //         $image_resize->save(public_path($path . 'medium_' . $filename));

    //         // Resize Image large
    //         $image_resize = Image::make($image);
    //         $image_resize->fit($large_width, $large_height);
    //         $image_resize->save(public_path($path . 'large_' . $filename));

    //         // Store Media Data
    //         $media = new Media;
    //         $media->file_name = $filename;
    //         $media->year = date('Y');
    //         $media->month = date('m');
    //         $media->save();

    //         return [
    //             'status' => true,
    //             'media_id' => $media->id,
    //             'file_name' => $filename,
    //             'file_path' => date('Y') . '/' . date('m'),
    //             'full_file_name' => $filename_string
    //         ];
    //     }
    //     return [
    //         'status' => false
    //     ];
    // }

    // Size Paths
    public static function sizes($image_path, $file_name){
        $output['original'] = asset("uploads/$image_path/$file_name");

        // dd(public_path("uploads/$image_path/small_$file_name"));
        if(file_exists(public_path("uploads/$image_path/small_$file_name"))){
            $output['small'] = asset("uploads/$image_path/small_$file_name");
        }else{
            $output['small'] = asset('img/no-image.png');
        }
        if(file_exists(public_path("uploads/$image_path/medium_$file_name"))){
            $output['medium'] = asset("uploads/$image_path/medium_$file_name");
        }else{
            $output['medium'] = asset('img/no-image.png');
        }
        if(file_exists(public_path("uploads/$image_path/large_$file_name"))){
            $output['large'] = asset("uploads/$image_path/large_$file_name");
        }else{
            $output['large'] = asset('img/no-image.png');
        }

        return $output;
    }
}
