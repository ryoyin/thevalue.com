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

        $this->locale = App::getLocale();

        $sideBanners = $this->getBannerList('indexSideBanner', 'medium');

        $category = App\Category::where('slug', $slug)->first();
        $categoryDetail = $this->getCategoriesList($category->id);

//        dd($categoryDetail);
        $categoryStories = $this->getCategoryStories($category);
//        dd($categoryStories);

        $categoryPagination = $this->getArticlePagination($category);

        //get categories list
        $categoriesList = $this->getCategoriesList();

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
            'slug' => $slug,
            'fbMeta' => $fbMetaArray,
            'categories' => $categoriesList,
            'categoryDetail' => $categoryDetail[0],
            'categoryStories' => $categoryStories,
            'sideBanners' => $sideBanners,
            'categoryPagination' => $categoryPagination
        );
        return view('frontend.categories.categories', $data);
    }

    public function video()
    {
        $this->locale = App::getLocale();

        $categoryDetail = array(
            'slug' => 'videos',
            'default_name' => 'Video',
            'name' => trans('thevalue.video'),
        );

        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get popularStories
        $categoryStories = $this->getSearchVideo();

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
            'categoryDetail' => $categoryDetail,
            'categoryStories' => $categoryStories,
            'sideBanners' => $indexSideBannerList,
        );

//        dd($result);

        return view('frontend.categories.categories', $data);
    }

    public function getCategoriesList($id = null)
    {
        $categories = New Category();
        return $categories->getCategoriesArray($id);
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

            $image_path = "image_medium_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $getCategoryDetail = $this->getCategoriesList($article->category_id);
            $categoryDetail = $getCategoryDetail[0];
            $categoryName = $categoryDetail['name'] == $categoryDetail['default_name'] ? $categoryDetail['name'] : $categoryDetail['default_name']." ".$categoryDetail['name'];

            $articleList[] = array(
                'url' => 'article',
                'slug' => $article->slug,
                'category' => array(
                    'slug' => $categoryDetail['slug'],
                    'name' => $categoryName
                ),
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $image_path,
                    's3' => $photo->push_s3
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id,
                'hit_counter' => $article->hit_counter,
                'share_counter' => $article->share_counter,
                'published_at' => $article->published_at->format('M d, Y')
            );
        }

        return $articleList;
    }

    public function getArticlePagination($category)
    {
        if($category->slug == 'News') {
            $articles = App\Article::where('status', 'published')->where($this->locale, 1)->orderBy('published_at', 'desc')->paginate(20);
        } else {
            $articles = $category->articles()->where('status', 'published')->where($this->locale, 1)->orderBy('published_at', 'desc')->paginate(20);
        }

        return $articles;
    }

    public function getCategoryStories($category, $size = 'medium') {
        $articleList = array();

        if($category->slug == 'News') {
            $articles = App\Article::where('status', 'published')->where($this->locale, 1)->orderBy('published_at', 'desc')->paginate(20);
        } else {
            $articles = $category->articles()->where('status', 'published')->where($this->locale, 1)->orderBy('published_at', 'desc')->paginate(20);
        }

        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $getCategoryDetail = $this->getCategoriesList($article->category_id);
            $categoryDetail = $getCategoryDetail[0];
            $categoryName = $categoryDetail['name'] == $categoryDetail['default_name'] ? $categoryDetail['name'] : $categoryDetail['default_name']." ".$categoryDetail['name'];

            $articleList[] = array(
                'url' => 'article',
                'slug' => $article->slug,
                'category' => array(
                    'slug' => $categoryDetail['slug'],
                    'name' => $categoryName
                ),
                'photo' => array(
                    'alt' => $photo->alt,
                    'image_path' => $image_path,
                    's3' => $photo->push_s3
                ),
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category_id' => $article->category_id,
                'hit_counter' => $article->hit_counter,
                'share_counter' => $article->share_counter,
                'published_at' => $article->published_at->format('M d, Y')
            );
        }

        return $articleList;
    }


}
