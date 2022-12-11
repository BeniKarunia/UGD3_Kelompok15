<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Vaksin;

class VaksinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataVaksin = Vaksin::all();

        if(count($dataVaksin)>0) {
            return response([
                'message' => 'Retrieve All Data Success',
                'data' => $dataVaksin
            ], 200);
        }

        return response([
            'message' => 'Data Vaksin Empty',
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
        $storeDataVaksin = $request->all();
        $validate = Validator::make($storeDataVaksin, [
            'nama' => 'required',
            'umur' => 'required',
            'lokasi' => 'required',
            'jenis' => 'required',
            'tanggal' => 'required'
        ]);
        if($validate->fails()) 
            return response(['message' => $validate->errors()], 400);

        $vaksin = Vaksin::create($storeDataVaksin);

        return response([
            'message' => 'Create Data Vaksin Success',
            'data' => $vaksin
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
        $dataVaksin = Vaksin::find($id);

        if(!is_null($dataVaksin)) {
            return response([
                'message' => 'Retrieve Data Vaksin Success',
                'data' => $dataVaksin
            ], 200);
        }
        return response([
            'message' => 'Data Vaksin Not Found',
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
        $dataVaksin = Vaksin::find($id);

        if(is_null($dataVaksin)) {
            return response([
                'message' => 'Data Vaksin Not Found',
                'data' => null
            ], 404);
        }

        $updateDataVaksin = $request->all();
        $validate = Validator::make($updateDataVaksin, [
            'nama' => 'required',
            'umur' => 'required',
            'lokasi' => 'required',
            'jenis' => 'required',
            'tanggal' => 'required'
        ]);

        if($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $dataVaksin->nama = $updateDataVaksin['nama'];
        $dataVaksin->umur = $updateDataVaksin['umur'];
        $dataVaksin->lokasi = $updateDataVaksin['lokasi'];
        $dataVaksin->jenis = $updateDataVaksin['jenis'];
        $dataVaksin->tanggal = $updateDataVaksin['tanggal'];
        
        if($dataVaksin->save()) {
            return response([
                'message' => 'Update Data Vaksin Success',
                'data' => $dataVaksin
            ], 200);
        }

        return response([
            'message' => 'Update Data Vaksin Failed',
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
        $vaksin = Vaksin::find($id);

        if(is_null($vaksin)) {
            return response([
                'message' => 'Data Vaksin Not Found',
                'data' => null
            ], 404);
        }
        if($vaksin->delete()) {
            return response([
                'message' => 'Delete Data Vaksin Success',
                'data' => $vaksin
            ], 200);
        }
        
        return response([
            'message' => 'Delete Data Vaksin Failed',
            'data' => $vaksin
        ], 400);
    }
}