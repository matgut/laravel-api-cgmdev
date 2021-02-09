<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserData;
use App\Models\User;
use DB;
use Validator;

class UserDataController extends ApiController
{
    public function getUsers(){

        $data = [];
        //$users= UserData::all();
        $users= DB::table('users') //DB es para realizar conecciones a la DB
            ->join('userdata','users.id','=','userdata.iduser')
            ->select('users.id','userdata.nombre','userdata.foto','userdata.edad','userdata.genero')
            ->get();//con get devuelve toda la informacon de la consulta
        $data['users'] = $users;


        return $this->sendResponse($data,"OK");
    }

    public function getUser($id, Request $request){

        $user = new User();
        $userData = UserData::where("iduser","=",$id)->first();//busco los iduser que sean igual al id que ingresa
        $data = [];
        $data['user'] = $user->find($id);
        $data['userdata'] = $userData;


        return $this->sendResponse($data,"OK");
    }

    public function addUser(Request $request){

        //valido los campos que se envian
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'edad' => 'required',
            'genero' => 'required',
            'acercade' => 'required',
        ]);

        if($validator->fails()) {
            $error = $validator->errors();
            return $this->sendError("Error de validación",$error,422);
        }

        $input = $request->all();
        $input["password"] = bcrypt($request->get("password"));
        $user = User::create($input);
        $token = $user->createToken("myApp")->accessToken;

        $userData = new UserData();
        $userData->nombre= $request->get('name');
        $userData->foto= $request->get('foto');
        $userData->edad= $request->get('edad');
        $userData->genero= $request->get('genero');
        $userData->acercade= $request->get('acercade');
        $userData->iduser= $user->id;

        $userData->save();


        $data = [
            "token"=>$token,
            "user" => $user,
            "userdata" => $userData
        ];

        return $this->sendResponse($data,"Usuario creado correctamente");
    }

    public function updateUser($id,Request $request){

        $user = User::find($id);
        if($user === null) {
            return $this->sendError("Error de datos",["el usuario no existe"],422);
        }
        //valido los campos que se envian
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'edad' => 'required',
            'genero' => 'required',
            'acercade' => 'required',
        ]);

        if($validator->fails()) {
            $error = $validator->errors();
            return $this->sendError("Error de validación",$error,422);
        }

        $user->name = $request->get("name");
        $user->save();


        $userData = UserData::where("iduser","=",$id)->first();//busco los iduser que sean igual al id que ingresa
        $userData->nombre = $request->get("name");
        $userData->edad = $request->get("edad");
        $userData->genero = $request->get("genero");
        $userData->acercade = $request->get("acercade");

        $userData->save();

        $data = [
            "user" => $user,
            "userdata" => $userData
        ];

        return $this->sendResponse($data,"Usuario modificado correctamente");


    }

    public function deleteUser($id){

        $user = User::find($id);
        if($user === null) {
            return $this->sendError("Error de datos",["el usuario no existe"],422);
        }

        $user->delete();
        
        $userData = UserData::where("iduser","=",$id)->first();//busco los iduser que sean igual al id que ingresa
       

        $userData->delete();

        $data = [];

        return $this->sendResponse($data,"Usuario eliminado correctamente");


    }
}
