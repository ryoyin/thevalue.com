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

    public function article($slug)
    {
        $this->locale = App::getLocale();

        $article = App\Article::where('slug', $slug)->first();

        $articleDetails = $this->getArticleDetails($article);

        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get article photos list
        $articlePhotoList = $this->getArticlePhotoList($article);

        //get tags list
        $tagsList = $this->getTags($article);

        //get side banners
//        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get popularStories
        $popularStoriesList = $this->getPopularStories();

        $result = array(
            'categories' => $categoriesList,
            'articleSlug' => $article->slug,
            'published_at' => $article->published_at->format('M d, Y'),
            'articleDetails' => $articleDetails,
            'articlePhotos' => $articlePhotoList,
            'tags' => $tagsList,
//            'sideBanners' => $indexSideBannerList,
            'popularStories' => $popularStoriesList,
        );

//        dd($result);

        return $result;
    }

    public function category($slug)
    {
        $this->locale = App::getLocale();

        $category = App\Category::where('slug', $slug)->first();
        $categoryDetail = $category->details->where('lang', $this->locale)->first();
//        dd($categoryDetail);
        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get popularStories
        $categoryStories = $this->getCategoryStories($category);

        $result = array(
            'categories' => $categoriesList,
            'sideBanners' => $indexSideBannerList,
            'categoryStories' => $categoryStories,
            'category' => $categoryDetail
        );

//        dd($result);

        return $result;
    }

    public function tag($slug)
    {
        $this->locale = App::getLocale();

        $tag = App\Tag::where('slug', $slug)->first();
        $tagDetail = $tag->details->where('lang', $this->locale)->first();
//        dd($tagDetail);
        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get popularStories
        $tagStories = $this->getTagStories($tag);

        $result = array(
            'categories' => $categoriesList,
            'sideBanners' => $indexSideBannerList,
            'tagStories' => $tagStories,
            'tag' => $tagDetail
        );

//        dd($result);

        return $result;
    }

    public function search($keyword)
    {
        $this->locale = App::getLocale();

        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get popularStories
        $searchStories = $this->getSearchStories($keyword);

        //get search result
//        $searchDetail = App\ArticleDetail::where('lang', $this->locale)->where('title', 'like', '%'.$keyword.'%')->where('description', 'like', '%'.$keyword.'%')->get();
//        dd($searchDetail);

        $result = array(
            'categories' => $categoriesList,
            'sideBanners' => $indexSideBannerList,
            'searchStories' => $searchStories,
//            'searches' => $searchDetail
        );

//        dd($result);

        return $result;
    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

    public function getArticlePhotoList($article)
    {
        $articlePhotoList = array();
        $articlePhotos = $article->photos;
//        dd($articlePhotos);
        foreach($articlePhotos as $photo) {
            $articlePhotoList[] = array(
                'alt' => $photo->alt,
                'image_path' => $photo->image_path
            );
        }
        return $articlePhotoList;
    }

    public function getArticleDetails($article) {
        $articleDetails = $article->details->where('lang', $this->locale)->first();

        if(count($articleDetails) == 0) {
            $articleDetails = $article->details->where('lang', 'en')->first();
        }
        
        return $articleDetails;
    }

    public function getTags($article) {
        $tagsList = array();
        $tags = $article->tags;
        foreach($tags as $tag) {
            $tagDetail = $tag->details->where('lang', $this->locale)->first();
            $tagsList[] = array(
                'slug' => $tag->slug,
                'name' => $tagDetail->name
            );
        }

        return $tagsList;
    }

    public function getBannerList($position)
    {
        $bannerList = array();
        $banners = App\Banner::where('position', $position)->orderBy('sorting')->get();
        foreach($banners as $banner) {
            $photo = $banner->photo;
            $bannerList[] = array(
                'alt' => $photo->alt,
                'image_path' => $photo->image_path
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

            if(count($detail) == 0) {
                $detail = $article->details->where('lang', 'en')->first();
            }

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
        $articles = App\Article::limit(4)->orderBy('published_at', 'desc')->get();
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            if(count($detail) == 0) {
                $detail = $article->details->where('lang', 'en')->first();
            }

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
        $articles = App\Article::limit(4)->orderBy('hit_counter', 'desc')->get();
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            if(count($detail) == 0) {
                $detail = $article->details->where('lang', 'en')->first();
            }

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

    public function getCategoryStories($category) {
        $articleList = array();
        $articles = $category->articles()->orderBy('published_at', 'desc')->get();
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

    public function getTagStories($tag) {
        $articleList = array();
        $articles = $tag->articles;

//        dd($articles);
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

    public function getSearchStories($keyword) {
        $articleList = array();
        $articleDetail = App\ArticleDetail::where('lang', $this->locale)->orWhere('title', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%')->get();

        foreach($articleDetail as $detail) {
            $article = $detail->article;
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