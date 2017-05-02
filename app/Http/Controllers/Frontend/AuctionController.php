<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use App\Category;

class AuctionController extends Controller
{

    public function index()
    {

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => route('frontend.disclaimer'),
            'type' => "website",
            'title' => "TheValue Auction Page",
            "description" => "The Value 收取我們最新資訊",
            "image" => asset('images/rocketfellercenter.jpg'),
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
        );

        return view('frontend.auction.index.main', $data);

    }

    public function pre()
    {

    }

    public function post()
    {

    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

}
