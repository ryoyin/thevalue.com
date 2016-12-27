<?php

namespace App\Http\Controllers\API;

use App;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index() {

        //get categories list
        $categories = New Category();
        $categoriesList = $categories->getCategoriesArray();

        //get banners
        $bannerList = array();
        $banners = App\Banner::all();
        foreach($banners as $banner) {
            $photo = $banner->photo;
            $bannerList[] = array(
                'alt' => $photo->alt,
                'image_path' => 'images/'.$photo->image_path
            );
        }

        //get featured articles
        $featuredArticleList = array();
        $featuredArticles = App\FeaturedArticle::all();


        $result = array(
            'categories' => $categoriesList,
            'banners' => $bannerList
        );

        return $result;
    }
}