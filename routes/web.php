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

Route::post('/signup','RegistrationController@store');
Route::get('/login', function(){
    return "you are not logged in";
})->name('login');

Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy');


Route::get('/posts/new','PostsController@getNew');
Route::get('/posts/popular','PostsController@getPopular');

Route::get('/logged/posts/{postId}','PostsController@getPost')->middleware('auth');

Route::resource('posts','PostsController');
Route::resource('comments','CommentsController');
/* ->middleware('cors'); */
/* Auth::routes(); */

Route::get('/home', 'HomeController@index')->name('home');


/* protected routes */

/* Route::group(['middleware'=>'auth'],function (){
    Route::get('favorite/{postId}',['PostsController@addFavorite','HomeController@index']);
}); */

Route::get('favorite/{postId}','PostsController@addFavorite')->middleware('auth');