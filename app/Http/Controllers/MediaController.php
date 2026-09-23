<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Media;
use App\Repositories\MediaRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('settings', 'settingsUpdate');
    }

    // Settings
    public function settings(){
        return view('back.media.settings');
    }
    public function settingsUpdate(Request $request){
        $where = array();
        $where['group'] = 'media';

        // Small
        $where['name'] = 'small_width';
        $insert['value'] = $request->small_width;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'small_height';
        $insert['value'] = $request->small_height;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Medium
        $where['name'] = 'medium_width';
        $insert['value'] = $request->medium_width;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'medium_height';
        $insert['value'] = $request->medium_height;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Large
        $where['name'] = 'large_width';
        $insert['value'] = $request->large_width;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'large_height';
        $insert['value'] = $request->large_height;
        DB::table('settings')->updateOrInsert($where, $insert);

        return redirect()->back()->with('success-alert', 'Media settings updated successfully.');
    }

    public function upload(Request $request){
        $uploaded_file = MediaRepo::upload($request->file('file'));

        return response()->json(['success' => $uploaded_file]);
    }

    public function imageUpload(Request $request){
        // CK Editor function ===============================================
        $image       = $request->file('upload');
        $filename    = time() . '.' . $image->getClientOriginalExtension();
        $destination = public_path() . '/uploads/image';
        $image->move($destination, $filename);

        $url = asset('uploads/image/' . $filename);

        return response()->json([
            'fileName' => $filename,
            'uploaded' => true,
            'url' => $url,
        ]);
    }

    public function getGallery(Request $request){
        $skip = $request->skip * 24;

        $items = Media::latest('id')->skip($skip)->take(24)->get();

        return view('back.media.getGallery', compact('items'));
    }
}
