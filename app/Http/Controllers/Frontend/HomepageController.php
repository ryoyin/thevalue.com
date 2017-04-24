<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class HomepageController extends Controller
{
    public $locale;

    public function index() {
        if(isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
            App::setLocale($lang);
        } else {
//            echo "laravel change";
            $lang = App::getLocale();
            $_COOKIE['lang'] = $lang;
        }

        $this->locale = $lang;

        $indexTopBannerList = $this->getBannerList('indexTopBanner', 'large');
        $indexSideBannerList = $this->getBannerList('indexSideBanner', 'medium');
        //get featured articles
        $featuredArticleList = $this->getFeaturedArticleList();

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
            'categories' => $this->getCategoriesList(),
            'topBanners' => $indexTopBannerList,
            'sideBanners' => $indexSideBannerList,
            'featuredArticles' => $featuredArticleList,
        );

//        dd($data['categories']);

        return view('frontend.homepage.homepage', $data);
    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

    public function getBannerList($position, $size = 'medium')
    {
        $bannerList = array();
        $banners = App\Banner::where('position', $position)->orderBy('sorting')->get();

        foreach($banners as $banner) {
            $photo = $banner->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $bannerList[] = array(
                'alt' => $photo->alt,
                'image_path' => $image_path,
                's3' => $photo->push_s3
            );
        }
        return $bannerList;
    }

    public function getFeaturedArticleList($size = 'medium') {
        $featuredArticleList = array();
        $featuredArticles = App\FeaturedArticle::limit(4)->orderBy('sorting', 'desc')->get();
        foreach($featuredArticles as $featuredArticle) {
            $article = $featuredArticle->article;
            $detail = $article->details->where('lang', $this->locale)->first();

            if(count($detail) == 0) {
                $detail = $article->details->where('lang', 'en')->first();
            }

            $photo = $article->photo;

//            dd($article);

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $featuredArticleList[] = array(
//                'id' => $article->id,
                'url' => 'article',
                'slug' => $article->slug,
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $image_path,
                    's3' => $photo->push_s3
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id,
                'published_at' => $article->published_at->format('M d, Y')
            );
        }

//        dd($featuredArticleList);

        return $featuredArticleList;
    }
}
