<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Request;
use App\Models\JanjiTemu;

class JanjiTemuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataJanji = JanjiTemu::all();

        if(count($dataJanji)>0) {
            return response([
                'message' => 'Retrieve All Data Success',
                'data' => $dataJanji
            ], 200);
        }

        return response([
            'message' => 'Data Janji Temu Empty',
            'data' => null
        ], 400); 
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
            'rumahSakit' => 'required',
            'tanggal' => 'required',
            'dokter' => 'required',
            'keluhan' => 'required',
        ]);

        if($validate->fails()) 
            return response(['message' => $validate->errors()], 400);
            
        $janji = JanjiTemu::create($storeData);

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
        $dataJanji = JanjiTemu::find($id);

        if(!is_null($dataJanji)) {
            return response([
                'message' => 'Retrieve Data Janji Success',
                'data' => $dataJanji
            ], 200);
        }

        return response([
            'message' => 'Data Janji Temu Not Found',
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
        $janjiData = JanjiTemu::find($id);

        if(is_null($janjiData)) {
            return response([
                'message' => 'Data Janji Temu Not Found',
                'data' => null
            ], 404);
        }

        $updateDataJanji  = $request->all();
        $validate = Validator::make($updateDataJanji, [
            'rumahSakit' => 'required',
            'tanggal' => 'required',
            'dokter' => 'required',
            'keluhan' => 'required',
        ]);

        if($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }


        $janjiData->rumahSakit = $updateDataJanji['rumahSakit'];
        $janjiData->tanggal = $updateDataJanji['tanggal'];
        $janjiData->dokter = $updateDataJanji['dokter'];
        $janjiData->keluhan = $updateDataJanji['keluhan'];

        if($janjiData->save()) {
            return response([
                'message' => 'Update Data Janji Temu Success',
                'data' => $janjiData
            ], 200);
        }

        return response([
            'message' => 'Update Data Janji Temu Failed',
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
        $janji = JanjiTemu::find($id);

        if(is_null($janji)) {
            return response([
                'message' => 'Data Janji Temu Not Found',
                'data' => null
            ], 404);
        }

        if($janji->delete()) {
            return response([
                'message' => 'Delete Data Janji Temu Success',
                'data' => $janji
            ], 200);
        }

        return response([
            'message' => 'Delete Data Janji Temu Failed',
            'data' => $janji
        ], 400);
    }
}