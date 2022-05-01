<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    public function logout(){
        Auth::user()->tokens()->delete();
        return response()->json([
            'message'=>'logout successful'
        ],200);
    }
}
