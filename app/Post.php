<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $hidden=["users_with_favorite"];

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
    public function is_favorite_post(){
        $id=Auth::id();
        if($id){
            $favorite=$this->users_with_favorite->find($id);
            return $favorite? true : false;
        }return false;
        
    }

    

    public function nextPost(){
        return static::where('id', '>' ,$this->id)->select('id','thumbnail');
    }

    

    public function previousPost(){
        return static::where('id', '<' ,$this->id)->select('id','thumbnail')->orderBy('id','desc');

    }

    public function updateRating(){
        $upvotes=Vote::where('post_id',$this->id)->where('vote','1')->count();
        $downvotes=Vote::where('post_id',$this->id)->where('vote','-1')->count();
        
        $this->rating=$upvotes-$downvotes;
        $this->save();
    }
}
