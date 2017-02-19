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
Route::get('/tag/{slug}', 'API\IndexController@tag');
Route::get('/search/{keyword}', 'API\IndexController@search');
Route::get('/about-us', 'API\IndexController@aboutUS');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/share-the-value', function(Request $request) {

    $share = new App\ShareEmail;
    $share->email = $request->email;
    $share->save();
});
