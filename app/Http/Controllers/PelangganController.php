<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PelangganModel;
use Validator;
use JWTAuth;
use Auth;

class PelangganController extends Controller
{
    public function store(Request $request)
    {
        if(Auth::user()->level=="admin") {
        $validator=Validator::make($request->all(),[
            'nama'=>'required',
            'alamat'=>'required',
            'telp'=>'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        } else {
            $insert=PelangganModel::insert([
                'nama'=>$request->nama,
                'alamat'=>$request->alamat,
                'telp'=>$request->telp
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
                'nama'=>'required',
                'alamat'=>'required',
                'telp'=>'required'
            ]);
        if($validator->fails()){
            return Response()->json($validator->errors());
        }
        $ubah=PelangganModel::where('id',$id)->update(
            [
                'nama'=>$req->nama,
                'alamat'=>$req->alamat,
                'telp'=>$req->telp
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
        $hapus=PelangganModel::where('id',$id)->delete();
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

