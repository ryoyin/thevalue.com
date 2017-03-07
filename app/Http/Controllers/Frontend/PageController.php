<?php

namespace App\Http\Controllers\Frontend;

//use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function search() {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com",
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray
        );

        return view('frontend.searches.searches', $data);
    }

    public function aboutUS() {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);
        return view('frontend.aboutUS.aboutUS');
    }
}
