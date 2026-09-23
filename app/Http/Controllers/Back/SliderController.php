<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Media;
use App\Models\Slider;
use App\Repositories\MediaRepo;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('position', 'delete', 'index', 'create', 'store', 'edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('position')->get();

        return view('back.slider.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_data = [
            'text_1' => 'required|max:255',
            'text_2' => 'max:255',
            'text_3' => 'max:255',
            'button_1_text' => 'max:255',
            'button_1_url' => 'max:255',
            'button_2_text' => 'max:255',
            'button_2_url' => 'max:255'
        ];
        if(!$request->media_id){
            $v_data['image'] = 'required|image|mimes:jpg,png,jpeg,gif';
        }
        $request->validate($v_data);

        $slider = new Slider;
        $slider->text_1 = $request->text_1;
        $slider->text_2 = $request->text_2;
        $slider->text_3 = $request->text_3;
        $slider->button_1_text = $request->button_1_text;
        $slider->button_1_url = $request->button_1_url;
        $slider->button_2_text = $request->button_2_text;
        $slider->button_2_url = $request->button_2_url;

        $slider->description = $request->description;

        if($request->media_id){
            $media = Media::find($request->media_id);

            if($media){
                $slider->image = $media->file_name;
                $slider->image_path = "$media->year/$media->month";
                $slider->media_id = $media->id;
            }
        }else{
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $slider->image = $uploaded_file['file_name'];
            $slider->image_path = $uploaded_file['file_path'];
            $slider->media_id = $uploaded_file['media_id'];
        }

        if($request->file('mobile_image')){
            $uploaded_file = MediaRepo::upload($request->file('mobile_image'), '2');
            $slider->image_2 = $uploaded_file['file_name'];
            $slider->image_path_2 = $uploaded_file['file_path'];
            $slider->media_id_2 = $uploaded_file['media_id'];
        }

        $slider->save();

        return redirect()->back()->with('success-alert', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return view('back.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'text_1' => 'required|max:255',
            'text_2' => 'max:255',
            'text_3' => 'max:255',
            'button_1_text' => 'max:255',
            'button_1_url' => 'max:255',
            'button_2_text' => 'max:255',
            'button_2_url' => 'max:255',
            'image' => 'image|mimes:jpg,png,jpeg,gif'
        ]);

        $slider->text_1 = $request->text_1;
        $slider->text_2 = $request->text_2;
        $slider->text_3 = $request->text_3;
        $slider->button_1_text = $request->button_1_text;
        $slider->button_1_url = $request->button_1_url;
        $slider->button_2_text = $request->button_2_text;
        $slider->button_2_url = $request->button_2_url;

        $slider->description = $request->description;

        if($request->media_id){
            $media = Media::find($request->media_id);

            if($media){
                $slider->image = $media->file_name;
                $slider->image_path = "$media->year/$media->month";
                $slider->media_id = $media->id;
            }
        }elseif($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $slider->image = $uploaded_file['file_name'];
            $slider->image_path = $uploaded_file['file_path'];
            $slider->media_id = $uploaded_file['media_id'];
        }

        if($request->file('mobile_image')){
            $uploaded_file = MediaRepo::upload($request->file('mobile_image'), '2');
            $slider->image_2 = $uploaded_file['file_name'];
            $slider->image_path_2 = $uploaded_file['file_path'];
            $slider->media_id_2 = $uploaded_file['media_id'];
        }

        $slider->save();

        return redirect()->back()->with('success-alert', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();

        return redirect()->back()->with('success-alert', 'Slider deleted successfully.');
    }

    public function position(Request $request){
        foreach ($request->position as $i => $data) {
            $query = Slider::find($data);
            $query->position = $i;
            $query->save();
        }

        return redirect()->back()->with('success-alert', 'Position updated successfully.');
    }
}
