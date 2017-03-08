<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public $locale;

    public function index($slug)
    {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com".$lang,
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'slug' => $slug,
            'fbMeta' => $fbMetaArray
        );
        return view('frontend.tags.tags', $data);
    }
}
