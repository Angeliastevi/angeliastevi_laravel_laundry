<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TransaksiModel;
use Validator;
use JWTAuth;
use Auth;

class TransaksiController extends Controller
{
    public function tampil()
    {
        if(Auth::user()->level=="petugas")
        {
            $datas=TransaksiModel::get();
            $count=$datas->count();
            $anggota=array();
            $status=1;
            foreach ($datas as $dt)
            {
                $anggota[]=array(
                    'id'=>$dt->id,
                    'id_pelanggan'=>$dt->id_pelanggan,
                    'id_petugas'=>$dt->id_petugas,
                    'tgl_trans'=>$dt->tgl_trans,
                    'tgl_selesai'=>$dt->tgl_selesai,
                    'created_at'=>$dt->created_at,
                    'updated_at'=>$dt->updated_at,
                );
            }
            return Response()->json(compact('count','anggota'));
        } else {
            return Response()->json(['status'=>'Maaf anda bukan petugas!']);
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->level=="petugas") {
        $validator=Validator::make($request->all(),[
            'id_pelanggan'=>'required',
            'id_petugas'=>'required',
            'tgl_trans'=>'required',
            'tgl_selesai'=>'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        } else {
            $insert=TransaksiModel::insert([
                'id_pelanggan'=>$request->id_pelanggan,
                'id_petugas'=>$request->id_petugas,
                'tgl_trans'=>$request->tgl_trans,
                'tgl_selesai'=>$request->tgl_selesai

            ]);
            if($insert){
                $status="sukses";
            } else {
                $status="gagal";
            }
            return response()->json(compact('status'));
        }
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
                'id_pelanggan'=>'required',
                'id_petugas'=>'required',
                'tgl_trans'=>'required',
                'tgl_selesai'=>'required',
            ]);
        if($validator->fails()){
            return Response()->json($validator->errors());
        }
        $ubah=TransaksiModel::where('id',$id)->update(
            [
                'id_pelanggan'=>$req->id_pelanggan,
                'id_petugas'=>$req->id_petugas,
                'tgl_trans'=>$req->tgl_trans,
                'tgl_selesai'=>$req->tgl_selesai
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

    public function destroy($id)
    {
        if(Auth::user()->level=="petugas") {
        $hapus=TransaksiModel::where('id',$id)->delete();
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

