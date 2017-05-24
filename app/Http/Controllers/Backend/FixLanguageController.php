<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

// Run in tinker
// php artisan tinker
// $controller = app()->make('App\Http\Controllers\Backend\FixLanguageController');
// app()->call([$controller, 'fix'], []);

class FixLanguageController extends Controller
{
    public function fix()
    {
        $articles = App\Article::all();
        foreach($articles as $article) {
            $articleDetails = $article->details;
            foreach($articleDetails as $articleDetail)
            {
                $lang = $articleDetail->lang;
                if(trim($articleDetail->description) == '') {
                    $article->$lang = 0;
                } else {
                    $article->$lang = 1;
                }
            }
            $article->save();
        }
    }
}
