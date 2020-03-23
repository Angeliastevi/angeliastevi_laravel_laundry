<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailTransModel;
use App\TransaksiModel;
use DB;
use Validator;
use JWTAuth;
use Auth;

class DetailTransController extends Controller
{
    public function store(Request $request)
    {
        if(Auth::user()->level=="petugas") {
        $validator=Validator::make($request->all(),[
            'id_trans'=>'required',
            'id_jenis'=>'required',
            'qty'=>'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        } 
        $subtotal=DB::table('jenis_cuci')->where('id',$request->id_jenis)->first();
        $sub=$subtotal->harga_perkilo*$request->qty;

            $insert=DetailTransModel::insert([
                'id_trans'=>$request->id_trans,
                'id_jenis'=>$request->id_jenis,
                'qty'=>$request->qty,
                'subtotal'=>$sub
            ]);
            if($insert){
                $status="sukses";
            } else {
                $status="gagal";
            }
            return response()->json(compact('status'));
        
    } else 
        {
            echo "Maaf, anda bukan petugas.";
        }
    }

    public function update($id,Request $req)
    {
        if(Auth::user()->level="petugas")
        {
            $validator=Validator::make($req->all(),
            [
                'id_trans'=>'required',
                'id_jenis'=>'required',
                'qty'=>'required',
            ]);
        if($validator->fails()){
            return Response()->json($validator->errors());
        }
        $subtotal=DB::table('jenis_cuci')->where('id',$req->id_jenis)->first();
        $sub=$subtotal->harga_perkilo*$req->qty;
        $ubah=DetailTransModel::where('id',$id)->update(
            [
                'id_trans'=>$req->id_trans,
                'id_jenis'=>$req->id_jenis,
                'qty'=>$req->qty,
                'subtotal'=>$sub
            ]);
        if($ubah){
            return Response()->json(['status'=>'sukses']);
        } else {
            return Response()->json(['status'=>'gagal']);
        }
        }
        else
        {
            echo "Maaf, anda bukan petugas";
        }
    }

    public function tampil(Request $r){
        if(Auth::user()->level=="petugas"){
          $transaksi = DB::table('transaksi')
          ->join('pelanggan', 'pelanggan.id', '=', 'transaksi.id_pelanggan')
          ->join('petugas', 'petugas.id', '=', 'transaksi.id_petugas')
          ->where('tgl_trans', '>=', $r->tgl_awal)
          ->where('tgl_trans', '<=', $r->tgl_akhir)
          ->select('transaksi.tgl_trans', 'pelanggan.nama', 'pelanggan.alamat', 'pelanggan.telp',
                  'transaksi.tgl_selesai', 'transaksi.id')
          ->get();
    
          $hasil = array();
    
          foreach ($transaksi as $t){
            $grand = DB::table('detailtransaksi')
            ->where('id_trans', '=', $t->id)
            ->groupBy('id_trans')
            ->select(DB::raw('sum(subtotal) as grandtotal'))
            ->first(); 
    
            $detail = DB::table('detailtransaksi')
            ->join('jenis_cuci', 'jenis_cuci.id', '=', 'detailtransaksi.id_jenis')
            ->where('id_trans', '=', $t->id)
            ->select('detailtransaksi.*', 'jenis_cuci.*')
            ->get();
    
            $hasil2 = array();
    
            foreach ($detail as $d){
              $hasil2[] = array(
                'id transaksi' => $d->id_trans,
                'jenis cuci' => $d->nama_jenis,
                'qty' => $d->qty,
                'subtotal' => $d->subtotal
              );
            }
    
            $hasil[] = array(
              'tgl transaksi' => $t->tgl_trans,
              'nama' => $t->nama,
              'alamat' => $t->alamat,
              'telp' => $t->telp,
              'tgl selesai' => $t->tgl_selesai,
              'total transaksi' => $grand,
              'detail transaksi' => $hasil2,
            );
          }
    
          return response()->json(compact('hasil'));
    
        } else {
          echo "Hanya petugas yang bisa mengakses!";
        }
      }
    public function destroy($id)
    {
        if(Auth::user()->level=="petugas") {
        $hapus=DetailTransModel::where('id',$id)->delete();
        if($hapus){
            return Response()->json(['status'=>'sukses']);
        } else {
            return Response()->json(['status'=>'gagal']);
        }
    } else 
        {
            echo "Maaf, anda bukan petugas.";
        }
    }
}