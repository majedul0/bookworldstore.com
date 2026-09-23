<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Testimonial;
use App\Repositories\MediaRepo;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimonials = Testimonial::get();

        return view('back.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.testimonials.create');
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
            'name' => 'required|max:255',
            'desiccation' => 'required|max:255',
            'testimonial' => 'required|max:255',
        ];

        if($request->file('image')){
            $v_data['image'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $testimonial = new Testimonial;
        $testimonial->client_name = $request->name;
        $testimonial->client_desiccation = $request->desiccation;
        $testimonial->testimonial = $request->testimonial;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $testimonial->image = $uploaded_file['file_name'];
            $testimonial->media_id = $uploaded_file['media_id'];
        }

        $testimonial->save();

        return redirect()->back()->with('success-alert', 'Testimonial created successful.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $testimonial)
    {
        return view('back.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $v_data = [
            'name' => 'required|max:255',
            'desiccation' => 'required|max:255',
            'testimonial' => 'required|max:255',
        ];

        if($request->file('image')){
            $v_data['image'] = 'mimes:jpg,png,jpeg,gif';
        }
        $request->validate($v_data);

        $testimonial->client_name = $request->name;
        $testimonial->client_desiccation = $request->desiccation;
        $testimonial->testimonial = $request->testimonial;
        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $testimonial->image = $uploaded_file['file_name'];
            $testimonial->media_id = $uploaded_file['media_id'];
        }

        $testimonial->save();

        return redirect()->back()->with('success-alert', 'Testimonial created successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('back.testimonials.index')->with('success-alert', 'Testimonial deleted successful.');
    }
}
