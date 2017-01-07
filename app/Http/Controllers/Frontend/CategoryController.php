<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public $locale;

    public function index($slug)
    {
        $data = array(
            'slug' => $slug
        );
        return view('frontend.categories.categories', $data);
    }
}
