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

Route::get('/listapi', function () {
    $list = array(
        url('api/index'),
        url('api/categories')
    );

    echo '<pre>';
    print_r($list);
});

Route::get('/index', 'API\IndexController@index');
Route::get('/categories', 'API\IndexController@getCategoriesList');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
