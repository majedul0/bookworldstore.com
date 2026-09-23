<?php

namespace App\Repositories;

use Info;

class eCourierRepo {
    // https://ecourier.com.bd/wp-content/uploads/eCourier_Merchant_API_Document_General_v5.1-1.pdf

    public static function getInfo(){
        // $thana_list = [];

        // Get Package
        $packages = (new static)->curl('packages');

        // // Get City
        // $cities = (new static)->curl('city-list');

        return [
            'packages' => $packages,
            // 'cities' => $cities,
            // 'thana_list' => $thana_list
        ];
    }

    public static function curl($url, $post_datas = []){
        $courier_config = Info::SettingsGroupKey('courier');

        $curl = curl_init();
        if(env('APP_ENV') == 'local'){
            curl_setopt($curl, CURLOPT_URL, 'https://staging.ecourier.com.bd/api/' . $url);
        }else{
            curl_setopt($curl, CURLOPT_URL, 'https://backoffice.ecourier.com.bd/api/' . $url);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        if(count($post_datas)){
            $fields = json_encode($post_datas);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'API-SECRET: ' . ($courier_config['ecourier_secret_key'] ?? ''),
            'USER-ID: ' . ($courier_config['ecourier_user_id'] ?? ''),
            'API-KEY: ' . ($courier_config['ecourier_api_key'] ?? ''),
            'Content-Type: application/json'
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public static function storeOrder($request_data){
        $data = (new static)->curl('order-place', $request_data);

        if(isset($data['success']) && $data['success'] == true){
            return [
                'status' => true,
                'id' => $data['ID']
            ];
        }
        return [
            'status' => false
        ];
    }
}
