<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Post;
use App\Tag;
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
        $posts= Post::select('id','thumbnail')->get();//::orderBy("created_at","asc")->get();
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
        $newPost->body=$request->body;
        $newPost->resourceurl='http://image-board.local/storage/files/'.$request->file->hashName();
        $newPost->thumbnail='http://image-board.local/storage/thumbnails/thumbnail_'.$request->file->hashName();

        $newPost->save();
        // create tags
        $tagArr=json_decode($request->tags);
        $newTag=new Tag;
        /* foreach($tagArr as $tags){
            $tag=newTag::firstOrCreate([])
        } */


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
        $post=Post::where('id',$id)->get();
        
        return $post;
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
