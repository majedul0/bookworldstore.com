<?php

namespace App\Repositories;

use Info;
use Illuminate\Support\Facades\Http;

class SMSRepo{
    public static function send($mobile_number, $key, $body_data){
        // Send SMS
        try{
            $enable_sms = Info::Settings('sms', 'enable_sms');
            $template_status = Info::Settings('sms', ($key . '_status'));
            $template = Info::Settings('sms', $key);
            $sms_provider = Info::Settings('sms', 'sms_provider');

            if ($enable_sms == 'Yes' && $template_status == 'Yes' && $template && $sms_provider) {
                $body = str_replace(
                    array_keys($body_data),
                    array_values($body_data),
                    $template
                );

                if($sms_provider == 'mysoftit'){
                    return (new static)->mySoftIt($mobile_number, $body);
                }

                return false;
            }

            return false;
        } catch (\Exception $e){
            return false;
        }
    }

    public static function mySoftIt($mobile_number, $sms_body){
        $api_key = Info::Settings('sms', 'mysoftit_api_key');
        $api_secrete = Info::Settings('sms', 'mysoftit_api_secrete');
        $sender_id = Info::Settings('sms', 'mysoftit_sender_id');

        if($api_key && $api_secrete && $sender_id){
            $url = 'https://smsapi.my-softit.com:7790/sendtext';

            try {
                $response = Http::withoutVerifying()->get($url, [
                    'apikey' => $api_key,
                    'secretkey' => $api_secrete,
                    'callerID' => $sender_id,
                    'toUser' => $mobile_number,
                    'messageContent' => $sms_body,
                ]);
                // $statusCode = $response->status();
                // $body = $response->body();
                // dd($body);

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    public static function sendSms($mobile_number, $body){
        $sms_provider = Info::Settings('sms', 'sms_provider');

        if($sms_provider){
            if($sms_provider == 'mysoftit'){
                return (new static)->mySoftIt($mobile_number, $body);
            }elseif($sms_provider == 'mshastra'){
                return (new static)->mobiShastra($mobile_number, $body);
            }
        }

        return false;
    }

    public static function mobiShastra($mobile_number, $sms_body){
        try{
            $user_id = Info::Settings('sms', 'mshastra_user_id');
            $password = Info::Settings('sms', 'mshastra_password');
            $sender_id = Info::Settings('sms', 'mshastra_sender_id');

            if($user_id && $password && $sender_id){
                $response = Http::get('https://mshastra.com/sendurlcomma.aspx', [
                    'user' => $user_id,
                    'pwd' => $password,
                    'senderid' => $sender_id,
                    'CountryCode' => '880',
                    'mobileno' => $mobile_number,
                    'msgtext' => $sms_body
                ]);

                $responseBody = $response->body();

                return true;
            }

            return false;
        }catch(\Exception $e){
            return false;
        }
    }
}
