<?php

namespace App\Repositories;

use Info;

class SteadFastRepo {
    // https://docs.google.com/document/d/e/2PACX-1vTi0sTyR353xu1AK0nR8E_WKe5onCkUXGEf8ch8uoJy9qxGfgGnboSIkNosjQ0OOdXkJhgGuAsWxnIh/pub

    public static function createOrder($invoice, $recipient_name, $recipient_phone, $recipient_address, $cod_amount, $note = null){
        $request = array();

        $request['invoice'] = $invoice;
        $request['recipient_name'] = $recipient_name;
        $request['recipient_phone'] = $recipient_phone;
        $request['recipient_address'] = $recipient_address;
        $request['cod_amount'] = $cod_amount;
        $request['note'] = $note;

        return (new static)->curl($request, 'create_order', 'POST');
    }

    public static function status($invoice_id){
        $uel = 'status_by_invoice/' . $invoice_id;

        return (new static)->curl([], $uel, 'GET');
    }

    public static function curl($request, $url, $request_method){
        $courier_config = Info::SettingsGroupKey('courier');

        $body = json_encode($request);

        $full_url = 'https://portal.steadfast.com.bd/api/v1/' . $url;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,            $full_url );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
        if($request_method == 'POST'){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }else{
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Api-Key: ' . ($courier_config['steadfast_api_key'] ?? ''),
            'Secret-Key: ' . ($courier_config['steadfast_secret_key'] ?? ''),
        ));

        $output = curl_exec($curl);

        $error = curl_error($curl);

        $output_arr = json_decode($output, true);

        curl_close($curl);

        return $output_arr;
    }
}
