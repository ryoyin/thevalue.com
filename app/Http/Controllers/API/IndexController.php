<?php

namespace App\Http\Controllers\API;

use App;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public $locale;

    public function index()
    {
        $this->locale = App::getLocale();

        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get top banners
        $indexTopBannerList = $this->getBannerList('indexTopBanner');

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get featured articles
        $featuredArticleList = $this->getFeaturedArticleList();

        //get latesStories
        $latestStoriesList = $this->getLatestStories();

        //get popularStories
        $popularStoriesList = $this->getPopularStories();

        $result = array(
            'categories' => $categoriesList,
            'topBanners' => $indexTopBannerList,
            'sideBanners' => $indexSideBannerList,
            'featuredArticles' => $featuredArticleList,
            'latestStories' => $latestStoriesList,
            'popularStories' => $popularStoriesList,
        );

//        dd($result);

        return $result;
    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

    public function getBannerList($position)
    {
        $bannerList = array();
        $banners = App\Banner::where('position', $position)->orderBy('sorting')->get();
        foreach($banners as $banner) {
            $photo = $banner->photo;
            $bannerList[] = array(
                'alt' => $photo->alt,
                'image_path' => 'images/'.$photo->image_path
            );
        }
        return $bannerList;
    }

    public function getFeaturedArticleList() {
        $featuredArticleList = array();
        $featuredArticles = App\FeaturedArticle::limit(4)->orderBy('created_at')->get();
        foreach($featuredArticles as $featuredArticle) {
            $article = $featuredArticle->article;
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

//            dd($article);

            $featuredArticleList[] = array(
//                'id' => $article->id,
                'url' => 'article',
                'slug' => $article->slug,
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $photo->image_path
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id
            );
        }

//        dd($featuredArticleList);

        return $featuredArticleList;
    }

    public function getLatestStories() {
        $articleList = array();
        $articles = App\Article::limit(4)->orderBy('created_at')->get();
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $articleList[] = array(
                'url' => 'article',
                'slug' => $article->slug,
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $photo->image_path
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id
            );
        }

        return $articleList;
    }

    public function getPopularStories() {
        $articleList = array();
        $articles = App\Article::limit(4)->orderBy('hit_counter', 'DESC')->get();
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $articleList[] = array(
                'url' => 'article',
                'slug' => $article->slug,
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $photo->image_path
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id
            );
        }

        return $articleList;
    }
}