<?php

namespace App\Http\Controllers;

use App\Models\tipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class tipeKamarController extends Controller
{
    //create data tipe kamar start
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kamar' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'foto'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson());
        }

        $namaFoto = time().'.'.request()->foto->getClientOriginalExtension();
            request()->foto->move(public_path('images'),$namaFoto);

        $store = tipeKamar::create([
            'nama_kamar' =>$request->nama_kamar,
            'harga' => $request->harga,
            'deskripsi'=> $request->deskripsi,
            'foto' => $namaFoto
        ]);

        $data = tipeKamar::where('nama_kamar', '=', $request->nama_kamar)->get();
        if($store){
            return Response() -> json([
                'status' => 1,
                'message' => 'Succes create new data!',
                'data' => $data
            ]);
        } else 
        {   
            return Response()->json([
                'status' => 0,
                'message' => 'Failed create new data!'
            ]);
        }
    }
    //create data end

    //read data start
    public function show(Request $request){
        $show=tipeKamar::when($request->search, function ($query, $search) {
                return $query->where('harga', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->get();
        return response()->json($show);
    }

    public function detail($id){
        if(DB::table('tipe_kamar')->where('id_tipe_kamar', $id)->exists()){
            $detail_tipe = DB::table('tipe_kamar')
            ->select('tipe_kamar.*')
            ->where('id_tipe_kamar', $id)
            ->get();
            return Response()->json($detail_tipe);
        }else {
            return Response()->json(['message' => 'Couldnt find the data']);
        }
    }
    //read data end

    //update data start
    public function update($id, Request $request){
        $validator=Validator::make($request->all(),
        [
            'nama_kamar' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'foto'=> 'required'
        ]);

        if($validator->fails()){
            return Response()->json($validator->errors());
        }

        $namaFoto = time().'.'.request()->foto->getClientOriginalExtension();
            request()->foto->move(public_path('images'),$namaFoto);

        $update=DB::table('tipe_kamar')
        ->where('id_tipe_kamar', '=', $id)
        ->update([
            'nama_kamar' =>$request->nama_kamar,
            'harga' => $request->harga,
            'deskripsi'=> $request->deskripsi,
            'foto' => $request -> namaFoto
        ]);

        $data=tipeKamar::where('id_tipe_kamar', '=', $id)->get();
        if($update){
            return Response() -> json([
                'status' => 1,
                'message' => 'Success updating data!',
                'data' => $data  
            ]);
        } else {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed updating data!'
            ]);
        }
    }
    //update data end

    //delete data start
    public function delete($id){
        $delete=DB::table('tipe_kamar')
        ->where('id_tipe_kamar', '=', $id)
        ->delete();

        if($delete){
            return Response() -> json([
                'status' => 1,
                'message' => 'Succes delete data!'
        ]);
        } else {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed delete data!'
        ]);
        }

    }
    //delete data end
}
