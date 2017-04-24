<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class ArticleController extends Controller
{
    public $locale;

    public function index($slug, Request $request)
    {
        /*if(isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
            App::setLocale($lang);
        } else {
//            echo "laravel change";
            $lang = App::getLocale();
            $_COOKIE['lang'] = $lang;
        }*/

        $lang = App::getLocale();

        $this->locale = $lang;

        $article = App\Article::where('slug', $slug)->first();

        $articleDetails = $this->getArticleDetails($article);

        $articleDetails['description'] = preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1} class="img-responsive" ${3}${4}', $articleDetails['description']);

        //get article photos list
        $articlePhotoList = $this->getArticlePhotoList($article);

        //get tags list
        $tagsList = $this->getTags($article);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com/".$lang."/article/".$slug,
            'type' => "article",
            'title' => $articleDetails->title,
            "description" => $articleDetails->short_desc,
            "image" => url($article->photo->image_path),
            "app_id" => "1149533345170108"
        );

        $article->published_at = $article->published_at->addHours(8);

//        echo $article->published_at;

        $data = array(
            'slug' => $slug,
            'fbMeta' => $fbMetaArray,
            'articleSlug' => $article->slug,
            'published_at' => $article->published_at->format('M d, Y'),
            'article' => array(
                'shares' => $article->share_counter,
                'hit' => $article->hit_counter,
                'published_at' => $article->published_at->format('M d, Y')
            ),
            'articleDetails' => $articleDetails,
            'articlePhotos' => $articlePhotoList,
            'tags' => $tagsList,
            'article_photo' => $article->photo->image_path,
            'appMode' => false,
            'categories' => $this->getCategoriesList(),
        );

        if($request->input('type') !== null) {
            switch ($request->input('type')) {
                case 'appview':
                    $data['appMode'] = true;
                    return view('mobile.article', $data);
                    break;
            }
        }

        return view('frontend.article.article', $data);
    }

    public function getArticleDetails($article)
    {
        $lang = App::getLocale();
        $articleDetails = $article->details->where('lang',  $lang)->first();

        if(count($articleDetails) == 0) {
            $articleDetails = $article->details->where('lang', 'en')->first();
        }

//        dd($articleDetails);

        return $articleDetails;
    }

    public function getArticlePhotoList($article, $size = 'large')
    {
        $articlePhotoList = array();
        $articlePhotos = $article->photos;
//        dd($articlePhotos);
        foreach($articlePhotos as $photo) {
            $image_path = "image_".$size."_path";
            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $articlePhotoList[] = array(
                'alt' => $photo->alt,
                'image_path' => $image_path,
                's3' => $photo->push_s3,
            );
        }
        return $articlePhotoList;
    }

    public function getTags($article) {
        $tagsList = array();
        $tags = $article->tags;
//        dd($tags);
        foreach($tags as $tag) {
            $tagDetail = $tag->details->where('lang', $this->locale)->first();
//            echo $this->locale;
//            dd($tagDetail);
            $tagsList[] = array(
                'slug' => $tag->slug,
                'name' => $tagDetail->name
            );
        }

        return $tagsList;
    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }
}

