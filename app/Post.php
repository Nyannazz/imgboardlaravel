<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{


    public function tags(){
        return $this->belongsToMany('App\Tag');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
    public function votes(){
        return $this->hasMany('App\Vote');
    }
    public function user(){
        return $this->belongsTo('App\User')->select(array("id","name"));
    }
    public function users_with_favorite(){
        return $this->belongsToMany('App\User');
    }

    public function updateRating(){
        $upvotes=Vote::where('post_id',$this->id)->where('vote','1')->count();
        $downvotes=Vote::where('post_id',$this->id)->where('vote','-1')->count();
        
        $this->rating=$upvotes-$downvotes;
        $this->save();
    }
}
