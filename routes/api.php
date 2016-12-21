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

Route::get('/listapi', function() {
    $list = array(
        url('api/testtranslate'),
        url('api/categories/html'),
        url('api/categories/array')
    );

    echo '<pre>';
    print_r($list);
});

Route::get('/testtranslate', function() {
   return trans('general.test');
});

use App\Category;
Route::get('categories/html', function() {
    return getCategoriesHTML(null);
});

Route::get('categories/array', 'CategoryController@getCategories');
Route::resource('categories', 'CategoryController');

Route::get('article/{id}', 'ArticleController@getArticleByID');
Route::resource('article', 'ArticleController');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
