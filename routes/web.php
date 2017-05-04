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

Route::group(
[
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
],
function()
{

    Route::get('/', 'Frontend\HomepageController@index')->name('frontend.index');
    Route::get('/article/{slug}', 'Frontend\ArticleController@index')->name('frontend.article');
    Route::get('/category/videos', 'Frontend\CategoryController@video')->name('frontend.category.videos');
    Route::get('/category/{slug}', 'Frontend\CategoryController@index')->name('frontend.category');
    Route::get('/tag/{slug}', 'Frontend\TagController@index')->name('frontend.tag');
    Route::get('/search', 'Frontend\PageController@search')->name('frontend.search');
    Route::get('/contact-us', 'Frontend\PageController@aboutUS')->name('frontend.aboutus');
    Route::get('/disclaimer', 'Frontend\PageController@disclaimer')->name('frontend.disclaimer');
    Route::get('/auction', 'Frontend\AuctionController@index')->name('frontend.auction');
//    Route::get('/pre-auction', 'Frontend\AuctionController@pre')->name('frontend.auction.pre');
//    Route::get('/post-auction', 'Frontend\AuctionController@post')->name('frontend.auction.post');

});

Route::post('/share-the-value', 'API\SubscriptController@subscription');

//Route::get('image-resize-sync', 'ImageResizeSyncController@index')->name('system.imageResizeSync');

Route::get('/app-qr-code', 'Frontend\PageController@RedirectQRCode')->name('frontend.redirectQRCode');

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
//Route::get('register', '\App\Http\Controllers\Auth\LoginController@logout');
Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', 'HomeController@index');

    Route::resource('tvadmin/photos', 'Backend\PhotoController');
    Route::resource('tvadmin/banners', 'Backend\BannerController');
    Route::resource('tvadmin/articles', 'Backend\ArticleController');
    Route::resource('tvadmin/featuredArticles', 'Backend\FeaturedArticleController');
    Route::resource('tvadmin/categories', 'Backend\CategoryController');
    Route::resource('tvadmin/tags', 'Backend\TagController');
    Route::resource('tvadmin/notifications', 'Backend\NotificationController');

});




