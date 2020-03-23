<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JenisCuciModel;
use Validator;
use JWTAuth;
use Auth;

class JenisCuciController extends Controller
{
    public function store(Request $request)
    {
        if(Auth::user()->level=="admin") {
        $validator=Validator::make($request->all(),[
            'nama_jenis'=>'required',
            'harga_perkilo'=>'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        } else {
            $insert=JenisCuciModel::insert([
                'nama_jenis'=>$request->nama_jenis,
                'harga_perkilo'=>$request->harga_perkilo
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
            echo "Maaf, anda bukan admin.";
        }
    }

    public function update($id,Request $req)
    {
        if(Auth::user()->level="admin")
        {
            $validator=Validator::make($req->all(),
            [
                'nama_jenis'=>'required',
                'harga_perkilo'=>'required'
            ]);
        if($validator->fails()){
            return Response()->json($validator->errors());
        }
        $ubah=JenisCuciModel::where('id',$id)->update(
            [
                'nama_jenis'=>$req->nama_jenis,
                'harga_perkilo'=>$req->harga_perkilo
            ]);
        if($ubah){
            return Response()->json(['status'=>'sukses']);
        } else {
            return Response()->json(['status'=>'gagal']);
        }
        }
        else
        {
            echo "Maaf, anda bukan admin";
        }
    }

    public function destroy($id)
    {
        if(Auth::user()->level=="admin") {
        $hapus=JenisCuciModel::where('id',$id)->delete();
        if($hapus){
            return Response()->json(['status'=>'sukses']);
        } else {
            return Response()->json(['status'=>'gagal']);
        }
    } else 
        {
            echo "Maaf, anda bukan admin.";
        }
    }
}