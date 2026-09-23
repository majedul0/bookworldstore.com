<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'index', 'edit', 'update', 'destroy', 'removeImage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereIn('type', ['admin', 'employee'])->where('email', '!=', 'admin@me.com')->get();
        return view('back.admin.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('back.admin.create', compact('roles'));
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
            'designation' => 'required|max:255',
            'mobile_number' => 'required|max:255|unique:users',
            'email' => 'required|max:255|unique:users',
            'street' => 'required|max:255',
            'role' => 'required',
            'password' => 'required|min:8|confirmed',
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $user = new User;
        $user->type = 'employee';
        $user->role_id = $request->role;
        $user->last_name = $request->name;
        $user->designation = $request->designation;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->street;
        $user->zip = $request->zip;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->password = Hash::make($request->password);

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Admin created successfully.');
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
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get();
        // dd($user);
        return view('back.admin.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v_data = [
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'mobile_number' => 'required|max:255|unique:users,mobile_number,' . $id,
            'email' => 'required|max:255|unique:users,email,' . $id,
            'street' => 'required|max:255',
            'role' => 'required',
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }
        if($request->password){
            $v_data['password'] = 'min:8|confirmed';
        }

        $request->validate($v_data);

        $user = User::where('email', '!=', 'admin@me.com')->findOrFail($id);
        $user->role_id = $request->role;
        $user->last_name = $request->name;
        $user->designation = $request->designation;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->street;
        $user->zip = $request->zip;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        if($request->password){
            $user->password = Hash::make($request->password);
        }

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            // Delete old
            if($user->profile){
                $img = public_path() . '/uploads/user/' . $user->profile;
                if (file_exists($img)) {
                    unlink($img);
                }
            }

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Admin updated successfully.');
    }

    public function update_profile_page(){
        $user = User::where('id', Auth::user()->id)->first();
        return view('back.admin.edit-admin')->with('user', $user);
    }

    public function update_profile(Request $request){
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:255',
            'email' => 'required|max:255',
            'street' => 'required|max:255'
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }
        $request->validate($v_data);
        $user = User::findOrFail(Auth::user()->id);
        $user->last_name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->street;
        $user->zip = $request->zip;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            if($user->profile){
                $img = public_path() . '/uploads/user/' . $user->profile;
                if (file_exists($img)) {
                    unlink($img);
                }
            }

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Profile updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user->id == auth()->user()->id){
            return redirect()->back()->with('error-alert', 'Sorry! You can not delete your own account!');
        }

        // Delete Image
        if($user->image){
            $img = public_path() . '/uploads/user/' . $user->image;
            if (file_exists($img)) {
                unlink($img);
            }
        }

        $user->delete();

        return redirect()->back()->with('success-alert', 'Admin deleted successfully.');
    }

    public function update_password(Request $request) {
        $request->validate([
            'old_password' => 'required|min:8',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('id', Auth::user()->id)->first();

        if(Hash::check($request->old_password, auth()->user()->password)){
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success-alert', 'Password updated successfully.');
        }
        return redirect()->back()->with('error-alert', 'Old password dose not match!');
    }
}
