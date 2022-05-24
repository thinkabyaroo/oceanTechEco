<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ApiAuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|min:3',
            'email'=>'required|unique:users,email',
            'password'=>'required|min:8',
            'password_confirm'=>"required|same:password"
        ]);

        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();
        return response()->json([
            'message'=>'success'
        ],200);
    }
    public function login(Request $request){
        $credentials=$request->validate([
            'password'=>'required',
            "email"=>'required'
        ]);

        if (!Auth::attempt($credentials)){
            return response()->json([
                'message'=>'login fail',
                'error'=>'invalid credentials'
            ],422);
        }
        else{
            $token=Auth::user()->createToken("user-auth");
            return response()->json([
                'message'=>'login successful',
                'data'=>$token
            ],200);
        }
//        return $request;
    }
    public function updatePassword(Request $request){
        $validator=Validator::make($request->all(),[
            "old_password"=>"required",
            "password"=>"required|min:6|max:100",
            "confirm_password"=>"required|same:password"
        ]);
        if ($validator->fails()){
            return response()->json([
                "message"=>"validations fails",
                "errors"=>$validator->errors()
            ],422);
        }
        $user=$request->user();
        if (Hash::check($request->old_password,$user->password)){
            $user->update(["password"=>Hash::make($request->password)]);
            return response()->json([
                "message"=>"Password Successfully updated",
            ],200);
    }else{
            return response()->json([
                "message"=>"Old password does not match",
            ],400);
        }
    }
    public function logout(){
        Auth::user()->tokens()->delete();
        return response()->json([
            'message'=>'logout successful'
        ],200);
    }
}
