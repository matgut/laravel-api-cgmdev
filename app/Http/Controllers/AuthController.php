<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends ApiController
{

    public function testOauth(){
        $user = Auth::user();
        return $this->sendResponse($user,"OK");
    }
    
    public function register(Request $request){

        //valido los campos que se envian
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            $error = $validator->errors();
            return $this->sendError("Error de validación",$error,422);
        }

        $input = $request->all();
        $input["password"] = bcrypt($request->get("password"));
        $user = User::create($input);
        $token = $user->createToken("myApp")->accessToken;


        $data = [
            "token"=>$token,
            "user" => $user
        ];

        return $this->sendResponse($data,"OK");
    }
}
