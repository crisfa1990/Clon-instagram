<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model{

    protected $table = 'images';
    //Relacion uno a muchos
    public function comments(){
        return $this->hasMany('App\Comment');
    }
        //Relacion Uno a Muchos
    public function likes(){
        return $this->hasMany('App\Like');
    }
    // Relacion Muchos a uno
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
