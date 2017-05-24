<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/index', 'API\IndexController@index');
Route::get('/category/{slug}', 'API\IndexController@category');
//Route::get('/categories', 'API\IndexController@getCategoriesList');
Route::get('/article/{slug}', 'API\IndexController@article');
//Route::get('/article/test/{slug}', 'API\TestIndexController@article');
Route::get('/tag/{slug}', 'API\IndexController@tag');
Route::get('/search/{keyword}', 'API\IndexController@search');
Route::get('/video', 'API\IndexController@video');
Route::get('/about-us', 'API\IndexController@aboutUS');
Route::get('/disclaimer', 'API\IndexController@disclaimer');
Route::get('/getLatestStories', 'API\IndexController@getLatestStories');
Route::get('/getLatestStoriesPaginationInfo', 'API\IndexController@getLatestStoriesPaginationInfo');
Route::get('/getPopularStories', 'API\IndexController@getPopularStories');
Route::get('/getPopularStoriesPaginationInfo', 'API\IndexController@getPopularStoriesPaginationInfo');
Route::get('/getStories', 'API\IndexController@getStories');
Route::get('/updateCounter', 'API\IndexController@updateCounter');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/share-the-value', function(Request $request) {

    $share = new App\ShareEmail;
    $share->email = $request->email;
    $share->save();
});

Route::post('/register-endpoint', 'API\SubscriptController@registerEndpoint');
//Route::post('/unsubscribe', 'API\SubscriptController@unsubscribe');
