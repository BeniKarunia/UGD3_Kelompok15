<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;

class UserProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataUser = User::all();

        if(count($dataUser)>0) {
            return response([
                'message' => 'Retrieve All Data Success',
                'data' => $dataUser
            ], 200);
        }

        return response([
            'message' => 'Data User Empty',
            'data' => null
        ], 400); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $storeData = $request->all();
    //     $validate = Validator::make($storeData, [
    //         'namaLengkap' => 'required',
    //         'username' => 'required',
    //         'Email' => 'required||email:rfc,dns',
    //         'nomorTelepon' => 'required',
    //         'password' => 'required'
    //     ]);

    //     if($validate->fails()) 
    //         return response(['message' => $validate->errors()], 400);
            
    //     $user = UserProfil::create($storeData);

    //     return response([
    //         'message' => 'Create User Success',
    //         'data' => $user
    //     ], 200);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataUser = User::find($id);

        if(!is_null($dataUser)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $dataUser
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userData = User::find($id);

        if(is_null($userData)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $updateDataUser  = $request->all();
        $validate = Validator::make($updateDataUser, [
            'namaLengkap' => 'required',
            'username' => 'required',
            'Email' => 'required',
            'nomorTelepon' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $userData->namaLengkap = $updateDataUser['namaLengkap'];
        $userData->username = $updateDataUser['username'];
        $userData->Email = $updateDataUser['Email'];
        $userData->nomorTelepon = $updateDataUser['nomorTelepon'];
        $userData->password = $updateDataUser['password']; 

        if($userData->save()) {
            return response([
                'message' => 'Update Data User Success',
                'data' => $userData
            ], 200);
        }

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if(is_null($user)) {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        if($user->delete()) {
            return response([
                'message' => 'Delete Data Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Delete Data Failed',
            'data' => $user
        ], 400);
    }
}