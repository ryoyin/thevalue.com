<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function index() {
        if(isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
            App::setLocale($lang);
        } else {
//            echo "laravel change";
            $lang = App::getLocale();
            $_COOKIE['lang'] = $lang;
        }

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com/".$lang,
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray,
        );


        return view('frontend.homepage.homepage', $data);
    }
}
