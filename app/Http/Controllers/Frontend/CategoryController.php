<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class CategoryController extends Controller
{
    public $locale;

    public function index($slug)
    {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $sideBanners = $this->getBannerList('indexSideBanner', 'medium');

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
            'slug' => $slug,
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'sideBanners' => $sideBanners,
        );
        return view('frontend.categories.categories', $data);
    }

    public function video()
    {
        $this->locale = App::getLocale();

        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get popularStories
        $searchVideo = $this->getSearchVideo();

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com/".$this->locale,
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'slug' => 'videos',
            'fbMeta' => $fbMetaArray,
            'categories' => $categoriesList,
            'sideBanners' => $indexSideBannerList,
//            'searchVideo' => $searchVideo,
//            'searches' => $searchDetail
        );

//        dd($result);

        return view('frontend.categories.categories', $data);
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

    public function getSearchVideo() {
        $articleList = array();
        $articleDetail = App\ArticleDetail::join('articles', 'articles.id', '=', 'article_details.article_id')->where('lang', $this->locale)->where('description', 'like', '%iframe%')->orderBy('articles.published_at', 'desc')->get();

        foreach($articleDetail as $detail) {
            $article = $detail->article;
            $photo = $article->photo;

            $articleList[] = array(
                'url' => 'article',
                'slug' => $article->slug,
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $photo->image_path,
                    's3' => $photo->push_s3
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id,
                'published_at' => $article->published_at->format('M d, Y')
            );
        }

        return $articleList;
    }


}
