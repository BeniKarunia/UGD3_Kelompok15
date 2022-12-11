<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'namaLengkap' => 'required',
            'username' => 'required',
            'Email' => 'required',
            'nomorTelepon' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) 
            return response(['message' => $validate->errors()], 400);
            
        // $registrationData['password'] = bcrypt($request->password);
        $user = User::create($registrationData);

        return response([
            'message' => 'Create User Success',
            'data' => $user
        ], 200);
    }

    // public function login(Request $request) {
    //     $loginData = $request->all();

    //     $validate = Validator::make($loginData, [
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     if($validate->fails())
    //         return response(['message' => $validate->errors()], 400);

    //     if(!Auth::attempt($loginData)) 
    //         return response(['message' => 'Invalid Credentials'], 401);
        
    //     $user = Auth::user();
    //     $token = $user->createToken('Authentication Token')->accessToken;

    //     return response([
    //         'message' => 'Authenticated',
    //         'user'  => $user,
    //         'token_type' => 'Bearer',
    //         'access_token' => $token
    //     ]);
    // }

    public function check(Request $request) {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $usercheck = User::where('username',$loginData["username"])->where('password',$loginData['password'])->exists();

        if($usercheck) {
            return response([
                'message' => 'Data User Found',
                'data' => User::where('username',$loginData["username"])->where("password",$loginData["password"])->first()
            ], 200);
        }

        return response([
            'message' => 'Data User Not Found',
            'data' => null
        ], 400);
    }

    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
            'message' => 'Logout Success',
        ], 200);
    }
}