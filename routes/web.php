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
    return 'welcome';

});



Route::get('/users/{id}/{name}',function($id, $name){
    return 'searching for user: '.$name.'<br/> with an id of: '.$id; 
});


Route::get('/controller', "PagesController@index");

Route::get('/posts/new','PostsController@getNew');
Route::get('/posts/popular','PostsController@getPopular');
Route::resource('posts','PostsController');
Route::resource('comments','CommentsController');
/* ->middleware('cors'); */