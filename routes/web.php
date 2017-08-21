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

    $locale = App::getLocale();

//    Route::get('/', 'Frontend\HomepageController@index')->name('frontend.index');
    Route::get('/', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com'); })->name('frontend.index');

    Route::get('/category/News', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/categories/news'); });
    Route::get('/category/Feature-Series', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/categories/feature-series'); });
    Route::get('/category/Education', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/categories/education'); });
    Route::get('/category/Auctions', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/categories/auctions'); });
    Route::get('/category/Exhibitions', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/categories/exhibitions'); });
    Route::get('/category/videos', function() {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/categories/videos'); });
    Route::get('/disclaimer', function() { return Redirect::to('https://hk.thevalue.com/disclaimer'); })->name('frontend.disclaimer');

//    Route::get('/', 'Frontend\HomepageController@index')->name('frontend.index');
//    Route::get('/article/{slug}', 'Frontend\ArticleController@index')->name('frontend.article');
    Route::get('/preview/{slug}', 'Frontend\ArticleController@index')->name('frontend.preview');

    Route::get('/article/{slug}', function($slug) {
        $locale = App::getLocale();
        $localeArr = array(
            'trad' => 'hk',
            'sim' => 'cn',
            'en' => 'en'
        );
        return Redirect::to('https://'.$localeArr[$locale].'.thevalue.com/articles/'.$slug);
    })->name('frontend.article');

//    Route::get('/category/videos', 'Frontend\CategoryController@video')->name('frontend.category.videos');
    Route::get('/category/{slug}', 'Frontend\CategoryController@index')->name('frontend.category');
    Route::get('/tag/{slug}', 'Frontend\TagController@index')->name('frontend.tag');
//    Route::get('/search', 'Frontend\PageController@search')->name('frontend.search');
    Route::get('/contact-us', 'Frontend\PageController@aboutUS')->name('frontend.aboutus');
    Route::get('/disclaimer', 'Frontend\PageController@disclaimer')->name('frontend.disclaimer');
    Route::get('/auction', 'Frontend\AuctionController@index')->name('frontend.auction');
    Route::any('/auction/{slug}', 'Frontend\AuctionController@auction')->name('frontend.auction.auction');
    Route::get('/auction/{house}/upcoming', 'Frontend\AuctionController@houseUpcoming')->name('frontend.auction.house.upcoming');
    Route::get('/auction/{house}/post', 'Frontend\AuctionController@housePost')->name('frontend.auction.house.post');
//    Route::get('/auction/{house}/about', 'Frontend\AuctionController@house')->name('frontend.auction.house');
//    Route::get('/auction/{house}/{event}', 'Frontend\AuctionController@event')->name('frontend.auction.house.event');
    Route::get('/auction/exhibition/{slug}', 'Frontend\AuctionController@sale')->name('frontend.auction.house.sale');
    Route::get('/auction/exhibition/{slug}/{lot}', 'Frontend\AuctionController@item')->name('frontend.auction.house.sale.item');
//    Route::get('/post-auction', 'Frontend\AuctionController@post')->name('frontend.auction.post');

});

Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path('public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::post('/share-the-value', 'API\SubscriptController@subscription');
//Route::get('/christie-spider', 'Scripts\ImportChristieSaleController@index');
//Route::get('/christie-spider-2', 'Scripts\ImportChristieSaleController@getRealized2');
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

    //Auction House
    Route::resource('tvadmin/auction/house', 'Backend\AuctionHouseController');
    Route::resource('tvadmin/auction/series', 'Backend\AuctionSeriesController');

    // Auction Christie Crawler
    Route::get('tvadmin/auction/crawler/christie', 'Backend\Crawler\ChristieController@index')->name('backend.auction.christie.index');
    Route::post('tvadmin/auction/crawler/christie/getList', 'Backend\Crawler\ChristieController@getList')->name('backend.auction.christie.getList');
    Route::get('tvadmin/auction/crawler/christie/autoGetList', 'Backend\Crawler\ChristieController@autoGetList')->name('backend.auction.christie.autoGetList');
    Route::post('tvadmin/auction/crawler/christie/crawler', 'Backend\Crawler\ChristieController@crawler')->name('backend.auction.christie.crawler');
    Route::get('tvadmin/auction/crawler/christie/crawler/remove/{intSaleID}', 'Backend\Crawler\ChristieController@crawlerRemove')->name('backend.auction.christie.crawler.remove');
    Route::get('tvadmin/auction/crawler/christie/capture', 'Backend\Crawler\ChristieController@capture')->name('backend.auction.christie.capture');
    Route::get('tvadmin/auction/crawler/christie/capture/{intSaleID}/itemlist', 'Backend\Crawler\ChristieController@captureItemList')->name('backend.auction.christie.capture.itemList');
    Route::get('tvadmin/auction/crawler/christie/capture/list/downloadImages', 'Backend\Crawler\ChristieController@listDownloadImages')->name('backend.auction.christie.capture.listDownloadImages');
    Route::get('tvadmin/auction/crawler/christie/capture/{intSaleID}/downloadImages', 'Backend\Crawler\ChristieController@downloadImages')->name('backend.auction.christie.capture.downloadImages');
    Route::get('tvadmin/auction/crawler/christie/capture/{intSaleID}/uploadS3', 'Backend\Crawler\ChristieController@uploadS3')->name('backend.auction.christie.capture.uploadS3');
    Route::get('tvadmin/auction/crawler/christie/capture/{intSaleID}/getRealizedPrice', 'Backend\Crawler\ChristieController@getRealizedPrice')->name('backend.auction.christie.capture.getRealizedPrice');
    Route::get('tvadmin/auction/crawler/christie/capture/{intSaleID}/getRealizedPrice2', 'Backend\Crawler\ChristieController@getRealizedPrice2')->name('backend.auction.christie.capture.getRealizedPrice2');
    Route::post('tvadmin/auction/crawler/christie/import/sale/{intSaleID}', 'Backend\Crawler\ChristieController@importSale')->name('backend.auction.christie.import.sale');

    Route::get('tvadmin/auction/crawler/christie/dbDownloadImages', 'Backend\Crawler\ChristieController@dbDownloadImages')->name('backend.auction.christie.dbDownloadImages');

    // Auction YiDu Crawler
    Route::get('tvadmin/auction/crawler/yidu', 'Backend\Crawler\YiDuController@index')->name('backend.auction.yidu.index');
    Route::post('tvadmin/auction/crawler/yidu/crawler', 'Backend\Crawler\YiDuController@crawler')->name('backend.auction.yidu.crawler');
    Route::get('tvadmin/auction/crawler/yidu/capture/{intSaleID}', 'Backend\Crawler\YiDuController@makeSaleInfo')->name('backend.auction.yidu.crawler.capture');
    Route::get('tvadmin/auction/crawler/yidu/capture/items/{intSaleID}', 'Backend\Crawler\YiDuController@parseItems')->name('backend.auction.yidu.crawler.capture.items');
    Route::get('tvadmin/auction/crawler/yidu/capture/items/images/{intSaleID}', 'Backend\Crawler\YiDuController@downloadImages')->name('backend.auction.yidu.crawler.capture.images');
    Route::get('tvadmin/auction/crawler/yidu/capture/items/resize/{intSaleID}', 'Backend\Crawler\YiDuController@resize')->name('backend.auction.yidu.crawler.capture.resize');
    Route::get('tvadmin/auction/crawler/yidu/capture/items/uploadS3/{intSaleID}', 'Backend\Crawler\YiDuController@uploadS3')->name('backend.auction.yidu.crawler.capture.uploadS3');
    Route::get('tvadmin/auction/crawler/yidu/capture/items/examine/{intSaleID}', 'Backend\Crawler\YiDuController@examine')->name('backend.auction.yidu.crawler.capture.examine');
    Route::post('tvadmin/auction/crawler/yidu/capture/items/import/{intSaleID}', 'Backend\Crawler\YiDuController@import')->name('backend.auction.yidu.crawler.capture.import');
    Route::get('tvadmin/auction/crawler/yidu/crawler/remove/{intSaleID}', 'Backend\Crawler\YiDuController@crawlerRemove')->name('backend.auction.yidu.crawler.remove');

    // Auction Sothebys Crawler
    Route::get('tvadmin/auction/crawler/sothebys', 'Backend\Crawler\SothebysController@index')->name('backend.auction.sothebys.index');
    Route::post('tvadmin/auction/crawler/sothebys/importURL', 'Backend\Crawler\SothebysController@importURL')->name('backend.auction.sothebys.importURL');
    Route::get('tvadmin/auction/crawler/sothebys/deleteImportURL/{id}', 'Backend\Crawler\SothebysController@deleteImportURL')->name('backend.auction.sothebys.deleteImportURL');
    Route::get('tvadmin/auction/crawler/sothebys/crawler', 'Backend\Crawler\SothebysController@crawler')->name('backend.auction.sothebys.crawler');
    Route::get('tvadmin/auction/crawler/sothebys/capture/{intSaleID}', 'Backend\Crawler\SothebysController@downloadData')->name('backend.auction.sothebys.crawler.capture');
    Route::get('tvadmin/auction/crawler/sothebys/capture/items/{intSaleID}', 'Backend\Crawler\SothebysController@parseItems')->name('backend.auction.sothebys.crawler.capture.items');
    Route::post('tvadmin/auction/crawler/sothebys/capture/items/images/{intSaleID}', 'Backend\Crawler\SothebysController@downloadImages')->name('backend.auction.sothebys.crawler.capture.images');
    Route::get('tvadmin/auction/crawler/sothebys/capture/items/resize/{intSaleID}', 'Backend\Crawler\SothebysController@resize')->name('backend.auction.sothebys.crawler.capture.resize');
    Route::get('tvadmin/auction/crawler/sothebys/capture/items/uploadS3/{intSaleID}', 'Backend\Crawler\SothebysController@uploadS3')->name('backend.auction.sothebys.crawler.capture.uploadS3');
    Route::get('tvadmin/auction/crawler/sothebys/capture/items/examine/{intSaleID}', 'Backend\Crawler\SothebysController@examine')->name('backend.auction.sothebys.crawler.capture.examine');
    Route::post('tvadmin/auction/crawler/sothebys/capture/items/import/{intSaleID}', 'Backend\Crawler\SothebysController@import')->name('backend.auction.sothebys.crawler.capture.import');
    Route::post('tvadmin/auction/crawler/sothebys/capture/items/getRealizedPrice/{intSaleID}', 'Backend\Crawler\SothebysController@getRealizedPrice')->name('backend.auction.sothebys.crawler.capture.getRealizedPrice');
    Route::get('tvadmin/auction/crawler/sothebys/capture/items/confirmRealizedPrice/{intSaleID}', 'Backend\Crawler\SothebysController@confirmRealizedPrice')->name('backend.auction.sothebys.crawler.capture.confirmRealizedPrice');
    Route::get('tvadmin/auction/crawler/sothebys/capture/items/sorting/{intSaleID}', 'Backend\Crawler\SothebysController@sorting')->name('backend.auction.sothebys.crawler.capture.sorting');
    Route::get('tvadmin/auction/crawler/sothebys/crawler/remove/{intSaleID}', 'Backend\Crawler\SothebysController@crawlerRemove')->name('backend.auction.sothebys.crawler.remove');

    Route::get('tvadmin/auction/crawler/sothebys/importSaleIndex', 'Backend\Crawler\SothebysController@importSaleIndex')->name('backend.auction.sothebys.sale.importSaleIndex');
    Route::post('tvadmin/auction/crawler/sothebys/uploadSaleFile', 'Backend\Crawler\SothebysController@uploadSaleFile')->name('backend.auction.sothebys.sale.uploadSaleFile');
    Route::post('tvadmin/auction/crawler/sothebys/importSaleFile/{intSaleID}', 'Backend\Crawler\SothebysController@importSaleFile')->name('backend.auction.sothebys.sale.importSaleFile');

    //Auction Sale
    Route::any('tvadmin/auction/sale/list', 'Backend\AuctionController@saleList')->name('backend.auction.sale.saleList');

    // Auction Item
    Route::any('tvadmin/auction/item/list/{saleID}', 'Backend\AuctionController@itemList')->name('backend.auction.itemList');
    Route::get('tvadmin/auction/item/{itemID}', 'Backend\AuctionController@itemEdit')->name('backend.auction.itemEdit');
    Route::post('tvadmin/auction/item/{itemID}', 'Backend\AuctionController@itemUpdate')->name('backend.auction.itemUpdate');

    // Auction Sale Push S3 - backend.auction.sale.pushS3
    Route::get('tvadmin/auction/sale/pushS3', 'Backend\AuctionController@pushS3Index')->name('backend.auction.sale.pushS3');
    Route::get('tvadmin/auction/sale/pushS3/process', 'Backend\AuctionController@pushS3Process')->name('backend.auction.sale.pushS3Process');

    //get realized-price
//    Route::get('/christie-get-realized-price', 'Scripts\ImportChristieSaleController@getRealizedPrice');
//    Route::get('/christie-convert-price', 'Scripts\ImportChristieSaleController@convertPrice');




    // beijing antique city
//    Route::get('/bjac-saleInjection', 'Backend\BJAntiqueCityController@saleInjection');
//    Route::get('/bjac-insertItem1', 'Backend\BJAntiqueCityController@insertItem1');
//    Route::get('/bjac-insertItem2', 'Backend\BJAntiqueCityController@insertItem2');
//    Route::get('/bjac-insertItem3', 'Backend\BJAntiqueCityController@insertItem3');
//    Route::get('/bjac-insertItem4', 'Backend\BJAntiqueCityController@insertItem4');
//    Route::get('/bjac-insertItem5', 'Backend\BJAntiqueCityController@insertItem5');
//    Route::get('/bjac-imgResize', 'Backend\BJAntiqueCityController@imgResize');
//    Route::get('/bjac-imgResizeFill', 'Backend\BJAntiqueCityController@imgResize');

    Route::get('tvadmin/articles/imageResize', 'ImageResizeSyncController@index')->name('backend.articles.imageResize');
    Route::get('tvadmin/readJSON', function() {

        $intSaleID = 'n09674';

        $path = 'spider/sothebys/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        echo $saleArray['sale']['image_path'];

        echo '<br>';

        dd($saleArray);

    });

});




