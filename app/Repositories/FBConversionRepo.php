<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class FBConversionRepo
{
    public static function track($track_type, $data = null, $phone = null, $name = null, $external_id = null, $event_id = null)
    {
        if (env('PIXEL_ID') && env('PIXEL_ACCESS_TOKEN')) {
            $user_data = [
                'client_ip_address' => request()->ip(),
                'client_user_agent' => request()->userAgent(),
                'country' => hash('sha256', 'BD'),
            ];
            if (request()->cookie('_fbc')) {
                $user_data['fbc'] = request()->cookie('_fbc');
            }
            if (request()->cookie('_fbp')) {
                $user_data['fbp'] = request()->cookie('_fbp');
            }
            if ($phone) {
                $user_data['ph'] = $phone;
            }
            if ($name) {
                $user_data['fn'] = $name;
            }
            if ($external_id) {
                $user_data['external_id'] = $external_id;
            }
            $request_data = [
                'action_source' => 'website',
                'event_name' => $track_type,
                'event_time' => time(),
                'user_data' => $user_data
            ];
            if ($event_id) {
                $request_data['event_id'] = $event_id;
            }

            if ($data) {
                $data = (array)$data;
                $request_data = array_merge($request_data, $data);
            }

            if(env('PIXEL_TEST_CODE')){
                $test_perm = '?test_event_code=' . env('PIXEL_TEST_CODE');
            }else{
                $test_perm = '';
            }
            $response = Http::post(('https://graph.facebook.com/v18.0/' . env('PIXEL_ID') . '/events' . $test_perm), [
                'data' => [$request_data],
                'access_token' => env('PIXEL_ACCESS_TOKEN'),
            ]);

            $data = $response->json();
            if (isset($data['events_received']) && $data['events_received'] == 1) {
                return 'true';
            }

            return 'false';
        }

        return 'false';
    }
}
