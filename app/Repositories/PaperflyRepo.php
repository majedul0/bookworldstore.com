<?php

namespace App\Repositories;

use Info;

class PaperflyRepo {
    public static function curl($url, $post_data){
        $courier_config = Info::SettingsGroupKey('courier');
        $basicAuth = ($courier_config['paperfly_username'] ?? '') . ":" . ($courier_config['paperfly_password'] ?? '');

        if(env('APP_ENV') == 'local'){
            $base_url = 'https://sandbox.paperfly-bd.com/';
        }else{
            $base_url = 'https://api.paperfly.com.bd/';
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, ($base_url . $url));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        if(count($post_data)){
            $fields = json_encode($post_data);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "paperflykey" => ($courier_config['paperfly_api_key'] ?? ''),
            "Authorization" => 'Basic ' . base64_encode($basicAuth)
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
