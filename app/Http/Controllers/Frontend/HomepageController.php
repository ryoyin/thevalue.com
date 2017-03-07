<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function index() {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com",
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊123",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray,
        );

        $lang = $_COOKIE['lang'];
        App::setLocale($lang);
        return view('frontend.homepage.homepage', $data);
    }
}
