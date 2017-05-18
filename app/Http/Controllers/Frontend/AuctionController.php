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

        return redirect()->route('frontend.auction.pre');

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
        $auctions = App\AuctionSeries::whereDate('end_date', $auctionDateLogic[$slug], Carbon::now())->get();

        $data = array(
            'locale' => App::getLocale(),
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'auctions' => $auctions,
        );

        return view('frontend.auction.pre.main', $data);

    }

    public function post()
    {

    }

    public function house($house)
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

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
        );

        return view('frontend.auction.company.main', $data);

    }

    public function event($house, $event, $exhibition)
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

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
        );

        return view('frontend.auction.details.main', $data);

    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

}
