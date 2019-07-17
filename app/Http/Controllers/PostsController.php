<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Auth;
use App\Post;
use App\Tag;
use App\Comment;
use App\User;
use App\Vote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\File;
use JWTAuth;


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

    /* public function getNew(){
        $posts=Post::where("id","2")->with("tags","comments.users","users")->get();
        return $posts;
    } */
    public function getPopular(){
        $posts= Post::orderBy("views","desc")->select('id','thumbnail')->paginate(40);
        return $posts;
    }
    public function getByUser(){
        $user=Auth::user();
        $posts=$user->posts()->paginate(40);
        return $posts;
    }

    public function showUserPost($id)
    {
        try{
            $user=Auth::user();
            if($user){
                $post=$user->posts()->with("tags","comments.user","user")->findOrFail($id);
                $prev=$post->previousPost()->first();
                $next=$post->nextPost()->first();
    
                $post->increment("views");
                
                $userId = $user->id;
                $userVote=0;
                if($userId){
                    $hasFavorite=$post->users_with_favorite->contains($userId);
                    $vote=$post->votes()->where('user_id',$userId)->first();
                    if($vote){
                        $userVote=$vote->vote;
                    }
                }
        
                $post->save();
                $postResponse=json_decode($post);
                $postResponse->users_with_favorite=$hasFavorite;
                $postResponse->vote=$userVote;
                $postResponse->prev=$prev;
                $postResponse->next=$next;
                return json_encode($postResponse);
            }

        }
        
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
    }

    public function getFavorites(){
        $user=Auth::user();
        $posts=$user->favorite_posts()->paginate(40, ["id","thumbnail"]);
        return $posts;
    }

    public function getByTag($name){
        try{
            $tags=Tag::where("name",$name)->firstOrFail()/* ->posts() */;
            $posts=$tags->posts()->paginate(40);
            return $posts;
        }
        catch(ModelNotFoundException $e){
            return response("no tag found", 404);
        }
        
    }

    public function search($name){
        // return posts with at least 1 keyword matching
        $keywords=explode(",",$name);
        $posts=Post::with('tags')->whereHas('tags',function($q) use($keywords){
            $q->whereIn('name', $keywords);
        })->paginate(40, ["id","thumbnail"]);
            
        return $posts;
  
    }
    public function searchStrict($name){
        // return posts with all keywords matching
        $keywords=explode(",",$name);
        $posts=Post::with('tags')->whereHas('tags',function($q) use($keywords){
            $q->whereIn('name', $keywords);
        }, '=', count($keywords))->paginate(40, ["id","thumbnail"]);
            
        return $posts;
  
    }
    public function showInSearch($name){
        
        try{
            $id=2;
            // return posts with at least 1 keyword matching
            $keywords=explode(",",$name);
            $posts=Post::with('tags')->whereHas('tags',function($q) use($keywords){
                $q->whereIn('name', $keywords);
            })->findOrFail($id);
            
        return $posts;
        }
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
  
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

        $path = $request->file->store('public/files');
        $thumbnail=Image::make($request->file)->fit(320,240)->encode('jpg')->save('storage/thumbnails/thumbnail_'.$request->file->hashName());
        $newPost=new Post;
        $newPost->title=$request->title;
        /* $newPost->user_id=$request->createdBy; */
        $newPost->resourceurl='http://image-board.local/storage/files/'.$request->file->hashName();
        $newPost->thumbnail='http://image-board.local/storage/thumbnails/thumbnail_'.$request->file->hashName();

        $userId=Auth::id();
        if($userId){
            $newPost->user_id=$userId;
        }

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
            if($userId){
                $newComment->user_id=$userId;
            }
            //$newComment->save();
            $newPost->comments()->save($newComment);
        }
        //send response
        return json_encode($newPost);
    }


    /* create testdata */
    public function testData(){
        $json = json_decode(file_get_contents("https://picsum.photos/v2/list"), true);
        $one = $json[7];
        $dlUrl = $one["download_url"];
        /* $imageFile=file_get_contents($dlUrl); */
        $name = substr($dlUrl, strrpos($dlUrl, '/') + 1);
        /* $storeItem=Storage::put($name.".jpg", $imageFile, 'public'); */
        file_put_contents(public_path("storage/files/".$name.".jpg"),file_get_contents($dlUrl));
        /* Storage::putFileAs('public/files', $imageFile, $name.".jpg"); */
        /* return $imageFile; */
        return $name;
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
            $post=Post::with("tags","comments.user","user","votes")->findOrFail($id);
            $post->increment("views");

            $prev=$post->previousPost()->first();
            $next=$post->nextPost()->first();
            $post->save();

            $post=json_decode($post);
            $finishedPost=new \stdClass();
            $post->prev=$prev;
            $post->next=$next;

            return json_encode($post);
        }
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
    }
    public function showPost($id)
    {
        try{
            $post=Post::with("tags","comments.user","user")->findOrFail($id);

            $prev=$post->previousPost()->first();
            $next=$post->nextPost()->first();

            $post->increment("views");
            
            $userId = Auth::id();
            $userVote=0;
            if($userId){
                $hasFavorite=$post->users_with_favorite->contains($userId);
                $vote=$post->votes()->where('user_id',$userId)->first();
                if($vote){
                    $userVote=$vote->vote;
                }
            }
    
            $post->save();
            $postResponse=json_decode($post);
            $postResponse->users_with_favorite=$hasFavorite;
            $postResponse->vote=$userVote;
            $postResponse->prev=$prev;
            $postResponse->next=$next;
            return json_encode($postResponse);
        }
        
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
    }

    public function showFavoritePost($id)
    {
        try{
            $post=Post::with("tags","comments.user","user")->findOrFail($id);

            $prev=$post->previousPost()->first();
            $next=$post->nextPost()->first();

            $post->increment("views");
            
            $userId = Auth::id();
            $userVote=0;
            if($userId){
                $hasFavorite=$post->users_with_favorite->contains($userId);
                $vote=$post->votes()->where('user_id',$userId)->first();
                if($vote){
                    $userVote=$vote->vote;
                }
            }
    
            $post->save();
            $postResponse=json_decode($post);
            $postResponse->users_with_favorite=$hasFavorite;
            $postResponse->vote=$userVote;
            $postResponse->prev=$prev;
            $postResponse->next=$next;
            return json_encode($postResponse);
        }
        
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
    }

    

    public function showCreateFeed($id){
        try{
            $post=Post::with("tags","comments.user","user","votes")->findOrFail($id);
            $post->increment("views");

            $prev=$post->previousPost()->take(10)->get();
            $next=$post->nextPost()->paginate(40);
            $post->save();

            $finishedPost=new \stdClass();
            $finishedPost->post=$post;
            $finishedPost->prev=$prev;
            $finishedPost->next=$next;

            return json_encode($finishedPost);
        }
        catch(ModelNotFoundException $e){
            return response("could not find post with the id ".$id, 404);
        }
    }

    /* add and delete post to your favorites */
    public function toggleFavorite($id){
        try{
            $userId=Auth::id();
            $post=Post::findOrFail($id);
            if(!$post->users_with_favorite->contains($userId)){
                $post->users_with_favorite()->attach($userId);
                return "succesfully added";
            }else{
                $post->users_with_favorite()->detach($userId);
                return "succesfully deleted".$userId;
            }
            
        }
        catch(ModelNotFoundException $e){
            return "something went wrong";
        }
        
    }

    
    public function vote($id,$myVote){
        try{
            $userId=Auth::id();
            $post=Post::findOrFail($id);
            
            $vote=$post->votes()->where('user_id',$userId)->first();
            if($vote){
                if($vote->vote==$myVote){
                    $vote->delete();
                    $post->updateRating();
                    return '0';
                }
                else{
                    $vote->vote=$myVote;
                    $vote->save();
                    $post->updateRating();
                    return $myVote;
                }
                
            }else{
                $newVote=new Vote();
                $newVote->vote=$myVote;
                $newVote->user_id=$userId;
                $newVote->post_id=$id;

                $newVote->save();
                $post->updateRating();
                return $myVote;
            }
        }
        catch(ModelNotFoundException $e){
            return "could not find post with this id";
        }
    }
    public function upvote($id){
        return $this->vote($id,1);
    }
    public function downvote($id){
        return $this->vote($id,-1);
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
