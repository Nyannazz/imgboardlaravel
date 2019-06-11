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
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser'); 
    Route::get('favorite/{postId}','PostsController@toggleFavorite');
    Route::get('/logged/posts/{postId}','PostsController@getPost');
});

Route::get('/controller', "PagesController@index");

Route::post('/signup','RegistrationController@store');
Route::get('/login', function(){
    return "you are not logged in";
})->name('login');

/* Route::post('/login', 'SessionsController@store');
Route::get('/logout', 'SessionsController@destroy'); */

Route::get('/posts','PostsController@index');
Route::get('/posts/new','PostsController@getNew');
Route::get('/posts/popular','PostsController@getPopular');
Route::get('/posts/tag/{tagname}','PostsController@getByTag');

Route::get('/posts/{id}','PostsController@show')->where('id', '[0-9]+');


/* Route::resource('posts','PostsController'); */
Route::resource('comments','CommentsController');


Route::get('/home', 'HomeController@index')->name('home');


