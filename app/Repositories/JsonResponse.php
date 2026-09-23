<?php

namespace App\Repositories;

class JsonResponse{
    public static function withData($data, $message = 'Data fetched successfully', $status = true, $code = 200){
        return response()->json([
            'success' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function onlyMessage($message, $status = true, $code = 200){
        return response()->json([
            'success' => $status,
            'code' => $code,
            'message' => $message
        ]);
    }

    public static function validation($errors){
        return response()->json([
            'success' => false,
            'code' => 422,
            'message' => 'Validation error!',
            'errors' => $errors
        ]);
    }

    public static function noData($message = 'No data found', $status = false, $code = 404){
        return response()->json([
            'success' => $status,
            'code' => $code,
            'data' => [],
            'message' => $message
        ]);
    }
}
