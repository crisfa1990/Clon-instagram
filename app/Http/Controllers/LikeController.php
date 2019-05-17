<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;


class LikeController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    public function like($image_id){
        //Recoger dartos del usuario y la imagen
        $user = \Auth::user();
        //Condicion revisar existentcia like y no duplicar
        $isset_like= Like::where('user_id', $user->id)
            ->where('image_id', $image_id)
            ->count();
        if($isset_like == 0) {
        //Tomar datos
        $like = new Like();
        $like->user_id = $user->id;
        $like->image_id = (int)$image_id;
        //Guardar en BD
        $like->save();
        return response()->json([
            'like' => $like
        ]);
        }else{
            return response()->json([
                'message' => 'El like ya existe'
            ]);
        }
    }
    public function dislike($image_id){

        //Recoger dartos del usuario y la imagen
        $user = \Auth::user();
        //Condicion revisar existentcia like y no duplicar
        $like= Like::where('user_id', $user->id)
            ->where('image_id', $image_id)
            ->first();
            var_dump($like);
        if($like) {
        //Eliminar Like
        $like->delete();

        return response()->json([
            'like' => $like,
            'message' => "Has dado Dislike correctamente"
        ]);
        }else{
            return response()->json([
                'message' => 'El like no existe'
            ]);
        }
    }
    public function index(){
        $user = \Auth::user();

        $likes = Like::where('user_id', $user->id)->
            orderBy('id','desc')->paginate(5);
        return view('like.index',[
            'likes' => $likes

        ]);
    }

}
