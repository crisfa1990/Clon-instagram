<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage; // Se agrega para usar el Storage
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function config (){

        return view('user.config');
    }

    public function update(Request $request){

        $user = \Auth::user();

        $id = $user ->id; //video 364 minuto 3 'por si falla'

        //Validacion
        $validate = $this->validate($request,[
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:users,nick,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id
        ]);
        //Recoger datos formulario
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');
        //Asignar nuevos valores al objeto del usuario
        $user -> name = $name;
        $user -> surname = $surname;
        $user -> nick = $nick;
        $user -> email = $email;
        //subir la imagen
        $image_path = $request->file('image_path');
        if($image_path){
            $image_path_name=time().$image_path->getClientOriginalName(); //lo sube con el mismo nombre que lo sube el cliente.

            Storage::disk('users')->put($image_path_name, File::get($image_path));
        //Seteo el nombre de la imagen en el objeto
            $user->image = $image_path_name;
        };
        //Ejecutar consulta y cambios en la DB
        $user->update();
        return redirect()->route('config')
            ->with(['message' => 'Usuario actulizado correctamente.']);
    }

    public function getImage($filename){
        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);
    }
}
