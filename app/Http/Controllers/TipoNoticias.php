<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoNoticia;

class TipoNoticias extends Controller{

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nome' =>'required|min:6|max:255'
        ]);

       

        if($validator->fails()) {
            $error = $validator->errors();
            return response()->json($error, 400);

        } else {
            $jornalistaId = Auth::user()->id;

            TipoNoticia::create([
                'id_jornalista'=> $jornalistaId,
                'nome'=>$request->nome,
            ]);
            return response()->json("Tipo de notícia inserido com sucesso!", 200);
        }
    }


    public function show(){
        $jornalistaId = Auth::user()->id;
        $tipos = TipoNoticia::where('id_jornalista',$jornalistaId)->get()->toJson(JSON_PRETTY_PRINT);
        return response($tipos, 200);
    }


    public function update(Request $request, $id){   
        $jornalistaId = Auth::user()->id;
        $getJornalistaId = TipoNoticia::where('id',$id)->select('id_jornalista')->first();
        $tipoJornalistaId = $getJornalistaId['id_jornalista'];


        if($jornalistaId!=$tipoJornalistaId){
            return response("Você não pode editar o tipo de noticia de outro jornalista", 401);

        } else {
            $validator = Validator::make($request->all(),[
                'nome' =>'required|min:6|max:255'
            ]);

            if($validator->fails()) {
                $error = $validator->errors();
                return response()->json($error, 400);

            } else {
                $data = [
                    'nome'=>$request->nome,
                ];
                TipoNoticia::where('id',$id)->update($data);
                return response()->json("Tipo de noticia editado com sucesso!", 200);
            }
        }
    }


    public function destroy($id){
        $jornalistaId = Auth::user()->id;
        $getJornalistaId = TipoNoticia::where('id',$id)->select('id_jornalista')->first();
        $tipoJornalistaId = $getJornalistaId['id_jornalista'];

        if($jornalistaId!=$tipoJornalistaId){
            return response("Você não pode deletar o tipo de noticia de outro jornalista", 401);

        } else {
            TipoNoticia::where('id',$id)->delete();
            return response()->json("Tipo de noticia deletado com sucesso!", 200);
        }
    }
}
