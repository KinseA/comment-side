<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@homepage');

Route::get('/about', 'HomeController@index');

Route::get('/article/create', 'ArticleController@create');

Route::get('/opinion/create', 'ArticleController@create');

Route::get('/opinion/create/{slug}', 'ArticleController@create');

Route::get('/opinion/edit/{slug}', 'ArticleController@edit');

Route::get('/login/facebook','LoginController@facebook');

Route::get('/login/twitter','LoginController@twitter');

Route::get('/home/test','HomeController@test');

Route::get('/profile/{id}', 'UserController@profile');

Route::get('/article/view/{slug}', 'ArticleController@view');

Route::get('/article/view/{slug}/{code}', 'ArticleController@view');

Route::get('/user/logout','LoginController@logout');

Route::post('/ajax/comment/create','CommentController@submitComment');

Route::get('/ajax/comment/get','CommentController@getComments');

Route::get('/ajax/comment/getCount/{pid}/{aid}','CommentController@getCount');

Route::get('/comment/get','CommentController@index');

Route::post('/ajax/article','ArticleController@addArticle');

Route::post('/ajax/comment/create','CommentController@submitComment');

Route::post('/ajax/interaction/vote','CommentController@receiveVote');

Route::get('/youtube','LoginController@youtube');




