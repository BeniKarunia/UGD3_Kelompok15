<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataJanji = Mahasiswa::all();

        if(count($dataJanji)>0) {
            return response([
                'message' => 'Retrieve All Data Success',
                'data' => $dataJanji
            ], 200);
        }

        return response([
            'message' => 'Data Janji Empty',
            'data' => null
        ], 400); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'npm' => 'required',
            'fakultas' => 'required',
            'prodi' => 'required',
        ]);

        if($validate->fails()) 
            return response(['message' => $validate->errors()], 400);
            
        $janji = Mahasiswa::create($storeData);

        return response([
            'message' => 'Create Janji Temu Success',
            'data' => $janji
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataJanji = Mahasiswa::find($id);

        if(!is_null($dataJanji)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $dataJanji
            ], 200);
        }

        return response([
            'message' => 'Janji Temu Not Found',
            'data' => null
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $janjiData = Mahasiswa::find($id);

        if(is_null($janjiData)) {
            return response([
                'message' => 'Data Not Found',
                'data' => null
            ], 404);
        }

        $updateDataJanji  = $request->all();
        $validate = Validator::make($updateDataJanji, [
            'nama' => 'required',
            'npm' => 'required',
            'fakultas' => 'required',
            'prodi' => 'required',
        ]);

        if($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }


        $janjiData->nama = $updateDataJanji['nama'];
        $janjiData->npm = $updateDataJanji['npm'];
        $janjiData->fakultas = $updateDataJanji['fakultas'];
        $janjiData->prodi = $updateDataJanji['prodi'];

        if($janjiData->save()) {
            return response([
                'message' => 'Update Data Janji Success',
                'data' => $janjiData
            ], 200);
        }

        return response([
            'message' => 'Update Janji Failed',
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
        $janji = Mahasiswa::find($id);

        if(is_null($janji)) {
            return response([
                'message' => 'Janji Not Found',
                'data' => null
            ], 404);
        }

        if($janji->delete()) {
            return response([
                'message' => 'Delete Data Success',
                'data' => $janji
            ], 200);
        }

        return response([
            'message' => 'Delete Data Failed',
            'data' => $janji
        ], 400);
    }
}