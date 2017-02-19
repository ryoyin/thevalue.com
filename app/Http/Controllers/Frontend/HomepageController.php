<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function index() {
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);
        return view('frontend.homepage.homepage');
    }
}
