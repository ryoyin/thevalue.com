<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public $locale;

    public function index($slug)
    {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $article = App\Article::where('slug', $slug)->first();

        $articleDetails = $this->getArticleDetails($article);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com/article/".$slug,
            'type' => "article",
            'title' => $articleDetails->title,
            "description" => $articleDetails->short_desc,
            "image" => url($article->photo->image_path),
            "app_id" => "1149533345170108"
        );

        $data = array(
            'slug' => $slug,
            'fbMeta' => $fbMetaArray
        );
        return view('frontend.article.article', $data);
    }

    public function getArticleDetails($article) {
        $articleDetails = $article->details->where('lang', $this->locale)->first();

        if(count($articleDetails) == 0) {
            $articleDetails = $article->details->where('lang', 'en')->first();
        }

        return $articleDetails;
    }
}

