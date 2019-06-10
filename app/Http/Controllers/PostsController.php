<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;
use App\Post;
use App\Tag;
use App\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\File;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts= Post::select('id','thumbnail')->paginate(40);//::orderBy("created_at","asc")->get();
        return $posts;
    }

    public function getNew(){
        $posts= Post::orderBy("created_at","desc")->select('id','thumbnail')->paginate(40);;
        return $posts;
    }
    public function getPopular(){
        $posts= Post::orderBy("views","desc")->select('id','thumbnail')->paginate(40);;
        return $posts;
    }
    public function getByUser($id){
        $posts= Post::orderBy("views","desc")->select('id','thumbnail')->paginate(40);;
        return $posts;
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
        //Storage::put('files.jpeg', $request->file('file'));
        $path = $request->file->store('public/files');
        $thumbnail=Image::make($request->file)->fit(320,240)->encode('jpg')->save('storage/thumbnails/thumbnail_'.$request->file->hashName());
        $newPost=new Post;
        $newPost->title=$request->title;
        $newPost->createdBy=$request->createdBy;
        $newPost->resourceurl='http://image-board.local/storage/files/'.$request->file->hashName();
        $newPost->thumbnail='http://image-board.local/storage/thumbnails/thumbnail_'.$request->file->hashName();

        $newPost->save();
        // create tags
        if($request->tags){
            $tagArr=json_decode($request->tags);
            $newTag=new Tag;
            foreach($tagArr as $tag){
                if(strlen($tag)>0){
                    $currentTag=Tag::firstOrCreate(['name'=>$tag]);
                    $newPost->tags()->attach($currentTag);
                }
            }
        }
        //create comment and attach it to post if initial comment is received
        if($request->body){
            $newComment=new Comment;
            $newComment->body=$request->body;
    
            //$newComment->save();
            $newPost->comments()->save($newComment);
        }
        //send response
        return json_encode($newPost);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $post=Post::where('id',$id)->first();
        $post->increment("views");
        $tags=$post->tags;
        $comments=$post->comments;
  
        $post->save();
        return json_encode($post);
    }
    public function getPost($id)
    {
        
        $post=Post::where('id',$id)->first();
        $post->increment("views");
        $tags=$post->tags;
        $comments=$post->comments;
        $userId=Auth::id();
        if($userId){
            $hasFavorite=$post->users_with_favorite->contains($userId);
        }
        $post->save();
        return json_encode($post);
    }

    public function showCreateFeed($id){
        $post=Post::where('id',$id)->first();
        $post->increment("views");
        $tags=$post->tags;
        $post->save();
        return json_encode($post);
    }

    /* add and delete post to your favorites */
    public function toggleFavorite($id){
        try{
            $userId=Auth::id();
            $post=Post::findOrFail($id);
            if(!$post->users_with_favorite->contains($id)){
                $post->users_with_favorite()->attach($userId);
                return "succesfully added";
            }else{
                $post->users_with_favorite()->detach($userId);
                return "succesfully deleted";
            }
            
        }
        catch(ModelNotFoundException $e){
            return "something went wrong";
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
