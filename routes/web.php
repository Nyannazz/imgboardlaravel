<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $name="heyho";
    return view('about')->with('title',$name);
});

Route::get('/api', function () {
    return 'welcome';
});



Route::get('/about',function(){
    $name="heyho";
    return view('about')->with('title',$name);
});

Route::get('/users/{id}/{name}',function($id, $name){
    return 'searching for user: '.$name.'<br/> with an id of: '.$id; 
});


Route::get('/controller', "PagesController@index");

Route::resource('posts','PostsController');
/* ->middleware('cors'); */