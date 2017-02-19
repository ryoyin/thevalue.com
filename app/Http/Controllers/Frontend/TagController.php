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
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);
        $data = array(
            'slug' => $slug
        );
        return view('frontend.tags.tags', $data);
    }
}
