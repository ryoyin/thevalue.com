<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use App\Category;
use Carbon\Carbon;

class AuctionController extends Controller
{

    public function index()
    {

        return redirect()->route('frontend.auction.auction', ['slug' => 'upcoming']);

    }

    public function auction($slug)
    {

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => route('frontend.disclaimer'),
            'type' => "website",
            'title' => "TheValue ".$slug." Auction",
            "description" => "The Value 收取我們最新資訊",
            "image" => asset('images/rocketfellercenter.jpg'),
            "app_id" => "1149533345170108"
        );

        $auctionDateLogic = array('upcoming' => '>', 'post' => '<');
        $series = App\AuctionSeries::whereDate('end_date', $auctionDateLogic[$slug], Carbon::now())->get();

        $data = array(
            'locale' => App::getLocale(),
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'series' => $series,
        );

        return view('frontend.auction.pre.main', $data);

    }

    public function post()
    {

    }

    public function houseUpcoming($house)
    {
        $locale = App::getLocale();

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => route('frontend.disclaimer'),
            'type' => "website",
            'title' => "TheValue Upcoming Auction",
            "description" => "The Value 收取我們最新資訊",
            "image" => asset('images/rocketfellercenter.jpg'),
            "app_id" => "1149533345170108"
        );

        $house = App\AuctionHouse::where('slug', $house)->first();
        $houseDetail = $house->details()->where('lang', $locale)->first();
        $seriesArray = $house->series()->whereDate('end_date', '>', Carbon::now())->orderBy('start_date')->get();
        $presetSeries = $house->series()->whereDate('end_date', '>', Carbon::now())->orderBy('start_date')->first();

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'house' => $house,
            'locale' => $locale,
            'houseDetail' => $houseDetail,
            'seriesArray' => $seriesArray,
            'presetSeries' => $presetSeries
        );

        return view('frontend.auction.company.main', $data);

    }

    public function sale($slug)
    {

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => route('frontend.disclaimer'),
            'type' => "website",
            'title' => "TheValue Upcoming Auction",
            "description" => "The Value 收取我們最新資訊",
            "image" => asset('images/rocketfellercenter.jpg'),
            "app_id" => "1149533345170108"
        );

        $locale = App::getLocale();
        $sale = App\AuctionSale::where('slug', $slug)->first();
        $saleDetail = $sale->details()->where('lang', $locale)->first();
        $series = $sale->series;
        $seriesDetail = $series->details()->where('lang', $locale)->first();
        $house = $series->house;
        $houseDetail = $house->details()->where('lang', $locale)->first();
        $items = $sale->items()->orderBy('id')->paginate(48);

        $data = array(
            'slug' => $slug,
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'locale' => $locale,
            'sale' => $sale,
            'saleDetail' => $saleDetail,
            'house' => $house,
            'houseDetail' => $houseDetail,
            'series' => $series,
            'seriesDetail' => $seriesDetail,
            'items' => $items
        );

        return view('frontend.auction.details.main', $data);

    }

    public function item($slug, $lot)
    {

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => route('frontend.disclaimer'),
            'type' => "website",
            'title' => "TheValue Upcoming Auction",
            "description" => "The Value 收取我們最新資訊",
            "image" => asset('images/rocketfellercenter.jpg'),
            "app_id" => "1149533345170108"
        );

        $locale = App::getLocale();
        $sale = App\AuctionSale::where('slug', $slug)->first();
        $saleDetail = $sale->details()->where('lang', $locale)->first();
        $series = $sale->series;
        $seriesDetail = $series->details()->where('lang', $locale)->first();
        $rSales = $series->sales()->inRandomOrder()->limit(4)->get();
        $house = $series->house;
        $houseDetail = $house->details()->where('lang', $locale)->first();

//        $allItems = $sale->items()->orderBy('id')->get();
        $lot = App\AuctionItem::where('id', $lot)->first();
        $lotDetail = $lot->details()->where('lang', $locale)->first();
        $items = $sale->items()->where('id', '>', $lot->id)->orderBy('id')->limit(6)->get();

        $data = array(
            'slug' => $slug,
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'locale' => $locale,
            'sale' => $sale,
            'saleDetail' => $saleDetail,
            'house' => $house,
            'houseDetail' => $houseDetail,
            'series' => $series,
            'seriesDetail' => $seriesDetail,
            'sales' => $rSales,
            'items' => $items,
//            'allItems' => $allItems,
            'lot' => $lot,
            'lotDetail' => $lotDetail
        );

        return view('frontend.auction.item.main', $data);

    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

}
