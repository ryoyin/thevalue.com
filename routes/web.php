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

Route::get('/', 'Frontend\HomepageController@index')->name('frontend.index');
Route::get('/article/{slug}', 'Frontend\ArticleController@index')->name('frontend.article');
Route::get('/category/{slug}', 'Frontend\CategoryController@index')->name('frontend.category');
Route::get('/tag/{slug}', 'Frontend\TagController@index')->name('frontend.tag');

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', 'HomeController@index');

    Route::resource('tvadmin/photos', 'PhotoController');

});



