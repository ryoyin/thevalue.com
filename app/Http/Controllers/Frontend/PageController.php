<?php

namespace App\Http\Controllers\Frontend;

//use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function search() {
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);
        return view('frontend.searches.searches');
    }

    public function aboutUS() {
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);
        return view('frontend.aboutUS.aboutUS');
    }
}
