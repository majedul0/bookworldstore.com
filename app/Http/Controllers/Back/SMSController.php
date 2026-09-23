<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Info;
use Illuminate\Support\Facades\Artisan;

class SMSController extends Controller
{
    public function config(){
        $sms_config = Info::SettingsGroupKey('sms', request('in_user_id'));

        return view('back.sms.config', compact('sms_config'));
    }

    public function update(Request $request){
        $where = array();

        $where['group'] = 'sms';

        // Save Credentials
        // dd($request->enable_sms);

        if($request->enable_sms == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'enable_sms';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'sms_provider';
        $insert['value'] = $request->sms_provider;
        DB::table('settings')->updateOrInsert($where, $insert);

        // My Soft IT
        $where['name'] = 'mysoftit_api_key';
        $insert['value'] = $request->mysoftit_api_key;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'mysoftit_api_secrete';
        $insert['value'] = $request->mysoftit_api_secrete;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'mysoftit_sender_id';
        $insert['value'] = $request->mysoftit_sender_id;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Mobi Shastra
        $where['name'] = 'mshastra_user_id';
        $insert['value'] = $request->mshastra_user_id;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'mshastra_password';
        $insert['value'] = $request->mshastra_password;
        DB::table('settings')->updateOrInsert($where, $insert);
        $where['name'] = 'mshastra_sender_id';
        $insert['value'] = $request->mshastra_sender_id;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Create
        $where['name'] = 'on_create_order';
        $insert['value'] = $request->on_create_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_create_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_create_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // Delivered
        $where['name'] = 'on_Delivered_order';
        $insert['value'] = $request->on_Delivered_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_Delivered_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_Delivered_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // Completed
        $where['name'] = 'on_Completed_order';
        $insert['value'] = $request->on_Completed_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_Completed_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_Completed_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // In Courier
        $where['name'] = 'on_InCourier_order';
        $insert['value'] = $request->on_InCourier_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_InCourier_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_InCourier_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // Canceled
        $where['name'] = 'on_Canceled_order';
        $insert['value'] = $request->on_Canceled_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_Canceled_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_Canceled_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // Returned
        $where['name'] = 'on_Returned_order';
        $insert['value'] = $request->on_Returned_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_Returned_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_Returned_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // Confirmed
        $where['name'] = 'on_Confirmed_order';
        $insert['value'] = $request->on_Confirmed_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_Confirmed_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_Confirmed_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        // Hold
        $where['name'] = 'on_Hold_order';
        $insert['value'] = $request->on_Hold_order;
        DB::table('settings')->updateOrInsert($where, $insert);

        if($request->on_Hold_order_status == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'on_Hold_order_status';
        DB::table('settings')->updateOrInsert($where, $insert);

        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'SMS Config Updated!');
    }
}
