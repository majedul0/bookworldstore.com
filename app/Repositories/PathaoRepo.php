<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Storage;
use Info;

// Test Credentials ========================================
// Base URL: https://hermes-api.p-stageenv.xyz/
// Client ID: 133
// Client Secret: CSHGEGFrYtBW4BWdYkWgfSh5vKvX8As28vnXria2
// Pathao Username: nawaze.info@gmail.com
// Pathao Password: lovePathao

class PathaoRepo{
    public static function authenticate(){
        // if(env('APP_ENV') == 'local'){
        //     $client_id = '133';
        //     $client_secret = 'CSHGEGFrYtBW4BWdYkWgfSh5vKvX8As28vnXria2';
        //     $username = 'nawaze.info@gmail.com';
        //     $password = 'lovePathao';
        // }else{
        // }
        $courier_config = Info::SettingsGroupKey('courier');
        $client_id = $courier_config['pathao_client_id'] ?? '';
        $client_secret = $courier_config['pathao_client_secret'] ?? '';
        $username = $courier_config['pathao_username'] ?? '';
        $password = $courier_config['pathao_password'] ?? '';

        $response = (new static)->curl("POST", "aladdin/api/v1/issue-token", [
            "client_id"     => $client_id,
            "client_secret" => $client_secret,
            "username"      => $username,
            "password"      => $password,
            "grant_type"    => "password",
        ]);

        $accessToken = [
            "token"      => "Authorization: Bearer " . $response['access_token'],
            "expires_in" => time() + $response['expires_in']
        ];

        Storage::disk('local')->put('pathao_bearer_token.json', json_encode($accessToken));
    }

    public static function authorization(){
        $storageExits = Storage::disk('local')->exists('pathao_bearer_token.json');

        if (!$storageExits) {
            (new static)->authenticate();
        }

        $jsonToken = Storage::get('pathao_bearer_token.json');
        $jsonToken = json_decode($jsonToken, true);

        if ($jsonToken['expires_in'] < time()) {
            (new static)->authenticate();
            $jsonToken = Storage::get('pathao_bearer_token.json');
            $jsonToken = json_decode($jsonToken, true);
        }

        return $jsonToken['token'];
    }

    public static function curl($request_method, $url, $body = [], $token = ''){
        $body = json_encode($body);
        if(env('APP_ENV') == 'local'){
            $full_url = 'https://hermes-api.p-stageenv.xyz/' . $url;
        }else{
            $full_url = 'https://api-hermes.pathaointernal.com/' . $url;
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,            $full_url );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
        if($request_method == 'POST'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }else{
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
        }
        if($token){
            curl_setopt($curl, CURLOPT_HTTPHEADER,     array('Content-Type: application/json', 'Accept: application/json', $token));
        }else{
            curl_setopt($curl, CURLOPT_HTTPHEADER,     array('Content-Type: application/json', 'Accept: application/json'));
        }

        $output = curl_exec($curl);

        $error = curl_error($curl);

        $output_arr = json_decode($output, true);

        curl_close($curl);

        return $output_arr;
    }

    public static function send($request_method, $url, $body_data = []){
        try{
            $token = (new static)->authorization();

            if($token){
                $response = (new static)->curl($request_method, $url, $body_data, $token);

                return [
                    'status' => true,
                    'response' => $response
                ];
            }

            return [
                'status' => false
            ];
        }catch(\Exception $e){
            return [
                'status' => false
            ];
        }
    }
}
