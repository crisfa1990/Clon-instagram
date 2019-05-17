<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function save(Request $request){
        //Validacion
        $validate= $this->validate($request, [
            'image_id' => 'integer|required',
            'content' => 'string|required'
        ]);
        //Recoger datos formulario
        $user = \Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');
        //Asignar valores
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id =$image_id;
        $comment->content = $content;
        //Guardar en la DB
        $comment->save();
            return redirect()->route('image.detail', [
                'id' => $image_id
            ])->with('Has publicado tu comentario correctamente');
    }
    public function delete($id){
        //conseguir datos del usuario logeado
        $user =\Auth::user();
        //Conseguir datos del comentario
        $comment = Comment::find($id);
        //Comprobar si soy dueño ddel comentario o de la publicación
        if($user &&($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
        $comment->delete();
        return redirect()->route('image.detail',[
        'id' => $comment->image->id
        ])->with(['message' => 'Comentario eliminado correctamente.']);
    }else{
        return redirect()->route('image.detail',[
            'id' => $comment->image->id
        ])->with(['message' =>'El comentario NO ha sido eliminado.']);

        }

    }

}
