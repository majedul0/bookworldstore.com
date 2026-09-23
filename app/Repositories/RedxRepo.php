<?php

namespace App\Repositories;

use Info;

class RedxRepo {
    public static function curl($url, $method = 'GET', $post_fields = ''){
        $courier_config = Info::SettingsGroupKey('courier');

        $curl = curl_init();

        if(env('APP_ENV') == 'local'){
            curl_setopt($curl, CURLOPT_URL, 'https://sandbox.redx.com.bd/v1.0.0-beta/' . $url);
        }else{
            curl_setopt($curl, CURLOPT_URL, 'https://openapi.redx.com.bd/v1.0.0-beta/' . $url);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        // curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if($method == 'POST'){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'API-ACCESS-TOKEN: Bearer ' . ($courier_config['redx_api_token'] ?? ''),
            'Content-Type: application/json'
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        return $data;
    }
}
