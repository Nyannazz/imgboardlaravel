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


Route::get('/',function(){
    return view('index',['apiName'=>'Baizuo Imageboard']);
});


Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/user', 'UserController@getAuthenticatedUser'); 
    Route::get('/logged/favorite/{postId}','PostsController@toggleFavorite')->where('id', '[0-9]+');
    Route::get('/logged/posts/{postId}','PostsController@showPost');
    Route::get('/logged/favorite/posts/{postId}','PostsController@showFavoritePost');
    Route::get('/logged/profile/posts/{postId}','PostsController@showUserPost');
    Route::get('/logged/user','PostsController@getByUser');
    Route::post('/logged/posts','PostsController@store');
    Route::get('/logged/favorites','PostsController@getFavorites');
    Route::post('/logged/comments','CommentsController@store');
    Route::get('/logged/like/{postId}','PostsController@upvote');
    Route::get('/logged/dislike/{postId}','PostsController@downvote');

});

Route::get('/controller', "PagesController@index");

Route::post('/signup','RegistrationController@store');
Route::get('/login', function(){
    return "you are not logged in";
})->name('login');

/* Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy'); */


/* route to fill db */
Route::get('/testdata','PostsController@testData');


Route::get('/posts','PostsController@index');
Route::get('/posts/new','PostsController@getNew');
Route::get('/posts/popular','PostsController@getPopular');

Route::get('/posts/tag/{tagname}','PostsController@getByTag');
Route::get('/posts/search_strict/{tagname}','PostsController@searchStrict');
Route::get('/posts/search/{tagname}','PostsController@search');
Route::get('/posts/insearch/{tagname}','PostsController@showInSearch');

Route::get('/posts/{id}','PostsController@show')->where('id', '[0-9]+');

Route::get('/nextpost/{id}','PostsController@showNextPost')->where('id', '[0-9]+');
Route::get('/prevpost/{id}','PostsController@showPrevPost')->where('id', '[0-9]+');


Route::get('/test/{id}','PostsController@testUser')->where('id', '[0-9]+');

Route::post('/posts','PostsController@store');


/* Route::resource('posts','PostsController'); */
Route::resource('/comments','CommentsController');




