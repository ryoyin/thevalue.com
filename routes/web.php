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
    Route::get('/auction/{slug}', 'Frontend\AuctionController@auction')->name('frontend.auction.auction');
    Route::get('/auction/{house}/upcoming', 'Frontend\AuctionController@houseUpcoming')->name('frontend.auction.house.upcoming');
    Route::get('/auction/{house}/post', 'Frontend\AuctionController@housePost')->name('frontend.auction.house.post');
//    Route::get('/auction/{house}/about', 'Frontend\AuctionController@house')->name('frontend.auction.house');
//    Route::get('/auction/{house}/{event}', 'Frontend\AuctionController@event')->name('frontend.auction.house.event');
    Route::get('/auction/exhibition/{slug}', 'Frontend\AuctionController@sale')->name('frontend.auction.house.sale');
    Route::get('/auction/exhibition/{slug}/{lot}', 'Frontend\AuctionController@item')->name('frontend.auction.house.sale.item');
//    Route::get('/post-auction', 'Frontend\AuctionController@post')->name('frontend.auction.post');

});

Route::post('/share-the-value', 'API\SubscriptController@subscription');
//Route::get('/christie-spider', 'Scripts\ImportChristieSaleController@index');
//Route::get('/christie-spider-to-db', 'Scripts\ImportChristieSaleController@insertSaleToDB');
//Route::get('/christie-spider-get-image', 'Scripts\ImportChristieSaleController@getImage');
//Route::get('/christie-image-resize', 'Scripts\ImportChristieSaleController@imgResize');
//Route::get('/christie-image-resize-fit', 'Scripts\ImportChristieSaleController@imgFitResize');
//Route::get('/christie-image-uploads3', 'Scripts\ImportChristieSaleController@uploadS3');
//Route::get('/christie-item-get-content', 'Scripts\ImportChristieSaleController@insertItemMissingDetail');
//Route::get('/christie-import-dimension', 'Scripts\ImportChristieSaleController@importDimension');

//Route::get('image-resize-sync', 'ImageResizeSyncController@index')->name('system.imageResizeSync');
//Route::get('/fix-auction', 'Backend\FixLanguageController@fixAuction');

Route::get('/app-qr-code', 'Frontend\PageController@RedirectQRCode')->name('frontend.redirectQRCode');

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('register', '\App\Http\Controllers\Auth\LoginController@logout');
Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', 'HomeController@index');

    Route::resource('tvadmin/photos', 'Backend\PhotoController');
    Route::resource('tvadmin/banners', 'Backend\BannerController');
    Route::resource('tvadmin/articles', 'Backend\ArticleController');
    Route::resource('tvadmin/featuredArticles', 'Backend\FeaturedArticleController');
    Route::resource('tvadmin/categories', 'Backend\CategoryController');
    Route::resource('tvadmin/tags', 'Backend\TagController');
    Route::resource('tvadmin/notifications', 'Backend\NotificationController');

    // Auction Item
    Route::get('tvadmin/auction/item/list/{saleID?}', 'Backend\AuctionController@itemList')->name('backend.auction.itemList');
    Route::get('tvadmin/auction/item/{itemID}', 'Backend\AuctionController@itemEdit')->name('backend.auction.itemEdit');
    Route::post('tvadmin/auction/item/{itemID}', 'Backend\AuctionController@itemUpdate')->name('backend.auction.itemUpdate');

    //get realized-price
    Route::get('/christie-get-realized-price', 'Scripts\ImportChristieSaleController@getRealizedPrice');

});




