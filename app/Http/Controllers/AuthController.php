<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
public function  register(Request $request)
    {
        $data=$request->validate([
           "name"=>"required",
           "email"=>"required|email|unique:users",
            "password"=>"required|confirmed"
        ]);
        $data['password'] = Hash::make($request->input('password'));
       $user = User::create($data);

        return response()->json([
            'status'=>201,
            'data'=> $user
        ]);

    }

    public function login(Request $request)
    {
//        $data = $request->validate([
//            "email"=>"required|email",
//            "password"=>"required"
//        ]);

        $data=$request->all();

        $token = JWTAuth::attempt($data);

        if(!empty($token)){
            return response()->json([
                'status'=>200,
                'data'=>auth()->user(),
                "token"=> $token
            ]);
        }else{
            return response()->json([
                'status'=>false,
                "message"=> "deconnecter"
            ]);
        }

    }

    public  function  logout(Request $request)
    {
        auth()->logout();
        return response()->json([
            'status'=>true,
            "token"=> null
        ]);
    }

    public  function  refresh()
    {
        $newToken = auth()->refresh();
        return response()->json([
            'status'=>true,
            "token"=> $newToken
        ]);
    }






//    public function login(Request $request)
//    {
//        if (!Auth::attempt($request->only('email','password'))){
//            return response()->json(['message'=>'Invalid login details'],401);
//        }
//
//        $user = User::where('email',$request->email)->firstOrFail();
//        $token = $user->createToken('auth_token')->plainTextToken;
//
//        return response()->json(['access_token'=>$token,'token_type'=>'Bearer']);
//    }

//    public function logout(Request $request)
//    {
//        $request->user()->currentAccessToken()->delete();
//        return response()->json(['message'=>'Deconnecter avec success']);
//    }
}
