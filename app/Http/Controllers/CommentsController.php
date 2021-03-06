<?php

namespace App\Http\Controllers;
use Auth;
use JWTAuth;
use App\Post;
use App\Comment;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "search for comments";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $post=Post::findOrFail($request->postId);
        
            $comment=new Comment;
            $comment->body=$request->body;
            
            $userId=Auth::id();
            if($userId){
                $comment->user_id=$userId;
            }
    
            $post->comments()->save($comment);
            return $post;
        }
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$request->postId,404);
        }
             
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $post=Post::findOrFail($id);
            $comments=$post->comments;
            return $post;
        }catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
