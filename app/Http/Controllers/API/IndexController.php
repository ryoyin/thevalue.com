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

        //get banners
        $bannerList = $this->getBannerList();

        //get featured articles
        $featuredArticleList = $this->getFeaturedArticleList();

        $result = array(
            'categories' => $categoriesList,
            'banners' => $bannerList,
            'featuredArticles' => $featuredArticleList
        );

        return $result;
    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

    public function getBannerList()
    {
        $bannerList = array();
        $banners = App\Banner::all();
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
        $featuredArticles = App\FeaturedArticle::all();
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
}