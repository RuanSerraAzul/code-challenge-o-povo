<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\TipoNoticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Noticias extends Controller{
    public function store(Request $request){
        $jornalistaId = Auth::user()->id;
        $tipoId =  $request->id_tipo;
        $getJornalistaTiposId = TipoNoticia::where('id',$tipoId)->select('id_jornalista')->first();
        $tipoJornalistaId = $getJornalistaTiposId['id_jornalista'];

        if($jornalistaId!=$tipoJornalistaId){
            return response("Você não pode adicionar esta noticia, pois esse tipo de noticia pertence a outro jornalista", 401);
    
        } else {
            $validator = Validator::make($request->all(),[
                'id_tipo' =>'required|numeric',
                'titulo' => 'required|string|max:255|min:10',
                'descricao' => 'required|string',
                'corpo_da_noticia' => 'required|string'
            ]);

            if($validator->fails()) {
                $error = $validator->errors();
                return response()->json($error, 400);

            } else {
                Noticia::create([
                    'id_jornalista'=>$jornalistaId,
                    'id_tipo' => $request->id_tipo,
                    'titulo' =>  $request->titulo,
                    'descricao' => $request->descricao,
                    'corpo_da_noticia' => $request->corpo_da_noticia,
                    ]);
                    return response()->json("Noticia adicionada com sucesso!", 200);
            }
        }
    }

   
    public function show(){
        $jornalistaId = Auth::user()->id;
        $noticias = Noticia::where('id_jornalista',$jornalistaId)->get()->toJson(JSON_PRETTY_PRINT);
        return response($noticias, 200);
    }

   
    public function update(Request $request, $id){
        $jornalistaId = Auth::user()->id;
        $getJornalistaNoticia = Noticia::where('id', $id)->first();
        $jornalistaNoticia = $getJornalistaNoticia['id_jornalista'];

        if($jornalistaNoticia!=$jornalistaId){
            return response("Você não pode editar a noticia de outro jornalista", 401);

        } else {

            $requestIdTipo = $request->id_tipo;
            $getTipoJornalista = TipoNoticia::where('id', $requestIdTipo)->first();
            $tipoJornalista = $getTipoJornalista['id_jornalista'];

            if($tipoJornalista!=$jornalistaId){
                return response("Você não pode editar para o tipo de noticia de outro jornalista", 401);

            } else {
                $data = [
                    'id_tipo' => $request->id_tipo,
                    'titulo' =>  $request->titulo,
                    'descricao' => $request->descricao,
                    'corpo_da_noticia' => $request->corpo_da_noticia,
                ];
                Noticia::where('id',$id)->update($data);
                return response()->json("Noticia editado com sucesso!", 200);
            }
        }
    }

    public function destroy($id){
        $jornalistaId = Auth::user()->id;
        $getJornalistaNoticia = Noticia::where('id', $id)->first();
        $jornalistaNoticia = $getJornalistaNoticia['id_jornalista'];
        if($jornalistaNoticia!=$jornalistaId){
            return response("Você não pode deletar a noticia de outro jornalista", 401);
        } else {
            Noticia::where('id',$id)->delete();
            return response()->json("Tipo de noticia deletado com sucesso!", 200);
        }
    }

    public function filter($id){
        $jornalistaId = Auth::user()->id;
        $noticias = Noticia::where('id_jornalista',$jornalistaId)->whereIn('id_tipo',[$id])->get()->toJson(JSON_PRETTY_PRINT);
        return response($noticias, 200);
    }
}
