<?php

namespace App\Http\Controllers;

use App\Models\kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class kamarController extends Controller
{
    //create data kamar start
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required',
            'id_tipe_kamar' => 'required'
        ]);

        if($validator -> fails()){
            return Response() -> json($validator -> errors());
        }

        $store = kamar::create([
            'nomor' =>$request->nomor,
            'id_tipe_kamar' => $request->id_tipe_kamar
        ]);

        $data = kamar::where('nomor', '=', $request->nomor)->get();
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
        $show = kamar::with(['tipe_kamar'])
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('tipe_kamar', function ($query) use ($search) {
                    return $query->where('deskripsi', 'like', "%{$search}%")
                    ->orWhere('nomor', 'like', "%{$search}%");
                });
            })
            ->get();
        return response()->json($show);
    }

    public function detail($id){
        if(DB::table('kamar')->where('id_kamar', $id)->exists()){
            $detail_kamar = DB::table('kamar')
            ->select('kamar.*')
            ->where('id_kamar', $id)
            ->get();
            return Response()->json($detail_kamar);
        }else {
            return Response()->json(['message' => 'Couldnt find the data']);
        }
    }
    //read data end

    //update data start
    public function update($id, Request $request){
        $validator=Validator::make($request->all(),
        [
            'nomor' => 'required',
            'id_tipe_kamar' => 'required'
        ]);

        if($validator->fails()){
            return Response()->json($validator->errors());
        }

        $update=DB::table('kamar')
        ->where('id_kamar', '=', $id)
        ->update([
            'nomor' =>$request->nomor,
            'id_tipe_kamar' => $request->id_tipe_kamar
        ]);

        $data=kamar::where('id_kamar', '=', $id)->get();
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
        $delete=DB::table('kamar')
        ->where('id_kamar', '=', $id)
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
