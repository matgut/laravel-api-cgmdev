<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function sendResponse($result,$message){
        $response = [
            "code" => 0,
            "status" => "success",
            "message" => $message,
            "data" => $result
        ];

        return response()->json($response,200);
    }

    public function sendError($error, $errorMessage = [], $code = 404){
        $response = [
            "code" => 1,
            "status" => "error",
            "message" => $errorMessage,
        ];

        return response()->json($response,$code);
    }
}
