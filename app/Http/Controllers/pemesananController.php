<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\pemesanan;
use App\Models\detailPemesanan;
use App\Models\kamar;
use App\Models\tipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class pemesananController extends Controller
{
    //create data kamar start
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_pemesanan' => 'required',
            'nama_pemesan' => 'required',
            'email_pemesan' => 'required',
            'tgl_pemesanan' => 'required',
            'tgl_check_in' => 'required',
            'tgl_check_out' => 'required',
            'nama_tamu' => 'required',
            'jumlah_kamar' => 'required',
            'id_tipe_kamar' => 'required',
            'status_pemesanan' => 'required',
            'id_user' => 'required',
        ]);
 
        if($validator -> fails()){
            return Response() -> json($validator -> errors());
        }

        //foreach ($request->payload as $jumlah_kamar) {
            $store = pemesanan::create([
                'nomor_pemesanan' =>$request->nomor_pemesanan,
                'nama_pemesan' => $request->nama_pemesan,
                'email_pemesan' => $request->email_pemesan,
                'tgl_pemesanan' => $request->tgl_pemesanan,
                'tgl_check_in' => $request->tgl_check_in,
                'tgl_check_out' => $request->tgl_check_out,
                'nama_tamu' => $request->nama_tamu,
                'jumlah_kamar' => $request->jumlah_kamar,
                'id_tipe_kamar' => $request->id_tipe_kamar,
                'status_pemesanan' => $request->status_pemesanan,
                'id_user' => $request->id_user
            ]);
        //}

        $tgl_check_in = Carbon::createFromFormat('Y-m-d', $request->tgl_check_in);
        $tgl_check_out = Carbon::createFromFormat('Y-m-d', $request->tgl_check_out);
        $diffInDays = $tgl_check_out->diffInDays($tgl_check_in);
        $latest = DB::table('pemesanan')->latest()->first();

        // $harga = 0;
        // foreach ($request->payload as $jumlah_kamar) {
        //     $kamar = kamar::where('id_kamar', $jumlah_kamar)->first();
        //     $harga += $kamar->id_tipe_kamar->harga * $diffInDays;
        // }

        $total = 0;
        $tipe = tipeKamar::where('id_tipe_kamar', $request->id_tipe_kamar)->first();
        $total += $tipe->harga * $diffInDays;

        $kamar = Kamar::where('id_tipe_kamar', $request->id_tipe_kamar)
        ->inRandomOrder()
        ->first();

        $id_kamar = $kamar->id_kamar;

        // $loggedUser = auth()->guard('api')->user();
        // if ($loggedUser->balance_amount < $harga) {
        //     return Response()->json(['status' => 0, 'message' => 'paymant Failed']);
        // }

        // $tipe_kamar = tipeKamar::where('id_tipe_kamar', $id_tipe_kamar)->first();
        // $harga = $tipe_kamar->harga;

        // create detail pemesanan
        $detail = detailPemesanan::create([
            //'staying_period' => "$diffInDays days",
            'harga' => $total,
            'tgl_akses' => $request->tgl_pemesanan,
            'id_pemesanan' => $latest->id_pemesanan,
            'id_kamar' => $id_kamar,
        ]);
        // sisa pembayaran
        // User::where('id_user', $loggedUser->id_user)
        //     ->update([
        //         'balance_amount' => $loggedUser->balance_amount - $harga
        //     ]);

        if ($detail) {
            return Response()->json(['status' => 1, 'message' => 'add successful']);
        } else {
            return Response()->json(['status' => 0, 'message' => 'add Failed']);
        }

        // $data = pemesanan::where('nomor_pemesanan', '=', $request->nomor_pemesanan)->get();
        // if($store){
        //     return Response() -> json([
        //         'status' => 1,
        //         'message' => 'Succes create new data!',
        //         'data' => $data
        //     ]);
        // } else 
        // {   
        //     return Response()->json([
        //         'status' => 0,
        //         'message' => 'Failed create new data!'
        //     ]);
        // }
    }

 
     //read data start
     public function show(Request $request){
        $show = pemesanan::with(['detail_pemesanan'])
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('detail_pemesanan', function ($query) use ($search) {
                    return $query->where('tgl_akses', 'like', "%{$search}%")
                    ->orWhere('nomor_pemesanan', 'like', "%{$search}%")
                    ->orwhere('tgl_check_in', 'like', "%{$search}%");
                });
            })
            ->get();
        return response()->json($show);
    }
 
     public function detail($id){
         if(DB::table('pemesanan')->where('id_pemesanan', $id)->exists()){
             $id_pemesanan = DB::table('pemesanan')
             ->select('pemesanan.*')
             ->where('id_pemesanan', $id)
             ->get();
             return Response()->json($id_pemesanan);
         }else {
             return Response()->json(['message' => 'Couldnt find the data']);
         }
     }
    //Read detail pemesanan
     public function showDetail(){
        return detailPemesanan::all();
    }

    public function detailDetail($id){
        if(DB::table('detail_pemesanan')->where('id_detail_pemesanan', $id)->exists()){
            $detail_pemesanan = DB::table('detail_pemesanan')
            ->select('detail_pemesanan.*')
            ->where('id_detail_pemesanan', $id)
            ->get();
            return Response()->json($detail_pemesanan);
        }else {
            return Response()->json(['message' => 'Couldnt find the data']);
        }
    }
     //read data end
 
     //update data start
     public function update($id, Request $request){
         $validator=Validator::make($request->all(),
         [
            'nomor_pemesanan' => 'required',
            'nama_pemesan' => 'required',
            'email_pemesan' => 'required',
            'tgl_pemesanan' => 'required',
            'tgl_check_in' => 'required',
            'tgl_check_out' => 'required',
            'nama_tamu' => 'required',
            'jumlah_kamar' => 'required',
            'id_tipe_kamar' => 'required',
            'status_pemesanan' => 'required',
            'id_user' => 'required'
         ]);
 
         if($validator->fails()){
             return Response()->json($validator->errors());
         }
 
         $update=DB::table('pemesanan')
         ->where('id_pemesanan', '=', $id)
         ->update([
            'nomor_pemesanan' =>$request->nomor_pemesanan,
             'nama_pemesan' => $request->nama_pemesan,
             'email_pemesan' => $request->email_pemesan,
             'tgl_pemesanan' => $request->tgl_pemesanan,
             'tgl_check_in' => $request->tgl_check_in,
             'tgl_check_out' => $request->tgl_check_out,
             'nama_tamu' => $request->nama_tamu,
             'jumlah_kamar' => $request->jumlah_kamar,
             'id_tipe_kamar' => $request->id_tipe_kamar,
             'status_pemesanan' => $request->status_pemesanan,
             'id_user' => $request->id_user
         ]);
 
         $data=pemesanan::where('id_pemesanan', '=', $id)->get();
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

     public function updateDetail($id, Request $request){
        $validator=Validator::make($request->all(),
        [
            'id_pemesanan' => 'required',
            'id_kamar' => 'required',
            'tgl_akses' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()){
            return Response()->json($validator->errors());
        }

        $update=DB::table('detail_pemesanan')
        ->where('id_detail_pemesanan', '=', $id)
        ->update([
            'id_pemesanan' =>$request->id_pemesanan,
            'id_kamar' => $request->id_kamar,
            'tgl_akses' => $request->tgl_akses,
            'harga' => $request->harga
        ]);

        $data=detailPemesanan::where('id_detail_pemesanan', '=', $id)->get();
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
         $delete=DB::table('pemesanan')
         ->where('id_pemesanan', '=', $id)
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
     //delete Detail pemesanan
     public function deleteDetail($id){
        $delete=DB::table('detail_pemesanan')
        ->where('id_detail_pemesanan', '=', $id)
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

