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





Route::post('api/register', 'UserController@register');
Route::post('api/login', 'UserController@authenticate');


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('api/user', 'UserController@getAuthenticatedUser'); 
    Route::get('api/favorite/{postId}','PostsController@toggleFavorite')->where('id', '[0-9]+');
    Route::get('api/logged/posts/{postId}','PostsController@getPost');
    Route::get('api/logged/user','PostsController@getByUser');
    Route::post('api/logged/posts','PostsController@store');
    Route::get('api/logged/favorites','PostsController@getFavorites');

});

Route::get('/api/controller', "PagesController@index");

Route::post('/api/signup','RegistrationController@store');
Route::get('/api/login', function(){
    return "you are not logged in";
})->name('login');

/* Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy'); */

Route::get('/api/posts','PostsController@index');
Route::get('/api/posts/new','PostsController@getNew');
Route::get('/api/posts/popular','PostsController@getPopular');
Route::get('/api/posts/tag/{tagname}','PostsController@getByTag');
Route::get('/api/posts/search_strict/{tagname}','PostsController@searchStrict');
Route::get('/api/posts/search/{tagname}','PostsController@search');
Route::get('/api/posts/{id}','PostsController@show')->where('id', '[0-9]+');

Route::post('/api/posts','PostsController@store');


/* Route::resource('posts','PostsController'); */
Route::resource('/api/comments','CommentsController');


Route::get('/', 'HomeController@index')->name('home');


