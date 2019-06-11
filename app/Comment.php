<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body'];


    public function posts(){
        return $this->belongsTo('App\Post');
    }
    public function users(){
        return $this->belongsTo('App\User')->select(array("id","name"));
    }
}
