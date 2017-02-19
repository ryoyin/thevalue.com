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
        return view('frontend.searches.searches');
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
