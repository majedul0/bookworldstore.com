<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function dashboard(){
        $orders = Order::where('user_id', auth()->user()->id)->latest('id')->get();

        return view('front.auth.dashboard', compact('orders'));
    }

    public function editProfile(){
        return view('front.auth.editProfile');
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email,' . auth()->user()->id,
            'mobile_number' => 'required|max:255|unique:users,mobile_number,' . auth()->user()->id,
            'address' => 'required|max:255'
        ]);

        $user = User::find(auth()->user()->id);
        $user->last_name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();

        return redirect()->back()->with('success-alert', 'Profile updated!');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail(auth()->user()->id);

        if(Hash::check($request->old_password, auth()->user()->password)){
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success-alert', 'Password changed success.');
        }
        return redirect()->back()->with('error-alert', 'Old password dose not match!');
    }

    public function orderDetails($id){
        $order = Order::where('user_id', auth()->user()->id)->findOrFail($id);

        return view('front.auth.orderDetails', compact('order'));
    }
}
