<?php

namespace App\Http\Controllers\API;

use App;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class IndexController extends Controller
{
    public $locale;

    public function index()
    {
        $this->locale = App::getLocale();

        //get categories list
        $categoriesList = $this->getCategoriesList();

        //get top banners
        $indexTopBannerList = $this->getBannerList('indexTopBanner', 'large');

        //get side banners
        $indexSideBannerList = $this->getBannerList('indexSideBanner');

        //get featured articles
        $featuredArticleList = $this->getFeaturedArticleList();

        //get latesStories
        $latestStoriesList = $this->getLatestStories();
        $latestStoriesPaginationInfo = $this->getLatestStoriesPaginationInfo();

        //get popularStories
        $popularStoriesList = $this->getPopularStories();

/*        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com",
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊123",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );*/

        $result = array(
            'categories' => $categoriesList,
            'topBanners' => $indexTopBannerList,
            'sideBanners' => $indexSideBannerList,
            'featuredArticles' => $featuredArticleList,
            'latestStories' => $latestStoriesList,
            'latestStoriesPaginationInfo' => $latestStoriesPaginationInfo,
            'popularStories' => $popularStoriesList,
            's3_path' => 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/',
//            'fbMeta' => $fbMetaArray
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
            'published_at' => $article->published_at->format('M d, Y h:i:s'),
            'article' => array(
                'shares' => $article->share_counter,
                'hit' => $article->hit_counter
            ),
            'articleDetails' => $articleDetails,
            'articlePhotos' => $articlePhotoList,
            'tags' => $tagsList,
//            'sideBanners' => $indexSideBannerList,
            'popularStories' => $popularStoriesList,
            'article_photo' => $article->photo->image_path,
            's3_path' => 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/',
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
            'category' => $categoryDetail,
            's3_path' => 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/',
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
            'tag' => $tagDetail,
            's3_path' => 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/',
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
            's3_path' => 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/',
        );

//        dd($result);

        return $result;
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

        //get search result
//        $searchDetail = App\ArticleDetail::where('lang', $this->locale)->where('title', 'like', '%'.$keyword.'%')->where('description', 'like', '%'.$keyword.'%')->get();
//        dd($searchDetail);

        $result = array(
            'categories' => $categoriesList,
            'sideBanners' => $indexSideBannerList,
            'searchVideo' => $searchVideo,
//            'searches' => $searchDetail,
            's3_path' => 'https://s3-ap-southeast-1.amazonaws.com/laravel-storage/',
        );

//        dd($result);

        return $result;
    }

    public function getCategoriesList()
    {
        $categories = New Category();
        return $categories->getCategoriesArray();
    }

    public function getArticlePhotoList($article, $size = 'medium')
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
                's3' => $photo->push_s3
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

    public function getStories(Request $request)
    {
        $type = $request->input('type');

        $result = array();

        switch($type) {
            case 'latest':
                $result['latestStories'] = $this->getLatestStories();
                break;
            case 'popular':
                $result['popularStories'] = $this->getPopularStories();
                break;
        }

        $result['categories'] = $this->getCategoriesList();
        
        return $result;
    }

    public function getFeaturedArticleList($size = 'medium') {
        $featuredArticleList = array();
        $featuredArticles = App\FeaturedArticle::orderBy('sorting')->get();

        foreach($featuredArticles as $featuredArticle) {
            $article = $featuredArticle->article;
            $detail = $article->details->where('lang', $this->locale)->first();

            if($this->locale == 'en') {
                if(trim($detail->description) == '') continue;
            } else {
                $checkEnDetail = $article->details->where('lang', 'en')->first();
                if(trim($checkEnDetail->description) != '' ) continue;
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
                'published_at' => $article->published_at->format('M d, Y h:i:s')
            );
        }

//        dd($featuredArticleList);

        return $featuredArticleList;
    }

    public function getLatestStoriesPaginationInfo()
    {
        return App\Article::where(App::getLocale(), 1)->where('status', 'published')->orderBy('published_at', 'desc')->paginate(6);
    }

    public function getLatestStories($size = 'medium')
    {
        $this->locale = App::getLocale();

        $articleList = array();
        $articles = App\Article::where($this->locale, 1)->where('status', 'published')->orderBy('published_at', 'desc')->paginate(6);
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $customTime = $article->published_at->getTimestamp() + 60*60*20;
//            $customTime = strtotime($test2) + 60*60*20;

//            $customTime = \date('M d, Y h:i:s', $customTime);

            $articleList[] = array(
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
                'published_at' => $article->published_at->format('M d, Y H:i:s'),
                'published_at_test_1' => $article->published_at->getTimestamp(),
                'published_at_test_2' => date('M d, Y H:i:s', $customTime),
            );
        }

        return $articleList;
    }

    public function getPopularStoriesPaginationInfo()
    {
        return $articles = App\Article::where(App::getLocale(), 1)->where('status', 'published')->orderBy('hit_counter', 'desc')->paginate(6);
    }

    public function getPopularStories($size = 'medium')
    {
        $this->locale = App::getLocale();

        $articleList = array();
        $articles = App\Article::where($this->locale, 1)->where('status', 'published')->orderBy('hit_counter', 'desc')->paginate(6);
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $articleList[] = array(
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
                'published_at' => $article->published_at->format('M d, Y h:i:s')
            );
        }

        return $articleList;
    }

    public function getCategoryStories($category, $size = 'medium') {
        $articleList = array();
        $articles = $category->articles()->orderBy('published_at', 'desc')->get();
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $articleList[] = array(
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

        return $articleList;
    }

    public function getTagStories($tag, $size = 'medium') {
        $articleList = array();
        $articles = $tag->articles;

//        dd($articles);
        foreach($articles as $article) {
            $detail = $article->details->where('lang', $this->locale)->first();

            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $articleList[] = array(
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

        return $articleList;
    }

    public function getSearchStories($keyword, $size = 'medium') {
        $articleList = array();
//        $articleDetail = App\ArticleDetail::where('lang', $this->locale)->where('title', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%')->get();
        $articleDetail = App\ArticleDetail::where('title', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%')->where('lang', $this->locale)->where('status', 'published')->get();

        foreach($articleDetail as $detail) {
            $article = $detail->article;
            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $articleList[] = array(
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

        return $articleList;
    }

    public function getSearchVideo($size = 'medium') {
        $articleList = array();
        $articleDetail = App\ArticleDetail::join('articles', 'articles.id', '=', 'article_details.article_id')->where('lang', $this->locale)->where('description', 'like', '%iframe%')->orderBy('articles.published_at', 'desc')->get();

        foreach($articleDetail as $detail) {
            $article = $detail->article;
            $photo = $article->photo;

            $image_path = "image_".$size."_path";

            if($photo->$image_path != null) {
                $image_path = $photo->$image_path;
            } else {
                $image_path = $photo->image_path;
            }

            $articleList[] = array(
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

        return $articleList;
    }

    public function aboutUS() {

        $this->locale = App::getLocale();

        $categoriesList = $this->getCategoriesList();

        $aboutUS = array(
            'title' => trans('thevalue.contact-us'),
            'content' => trans('thevalue.aboutUSContent'),
            'address' => '中環威靈頓街1號荊威廣場',
            'tel' => '(852) 1234 5678',
            'fax' => '(852) 8765 4321',
            'email' => 'itsupport@thevalue.com',
            'googleMap' => trans('thevalue.googleMap')
        );

        $result = array(
            'categories' => $categoriesList,
            'aboutUS' => $aboutUS
        );

        return $result;

    }

    public function disclaimer() {

        $this->locale = App::getLocale();

        $categoriesList = $this->getCategoriesList();

        $disclaimer = array(
            'title' => trans('thevalue.disclaimer'),
            'content' => trans('thevalue.disclaimerContent'),
        );

        $result = array(
            'categories' => $categoriesList,
            'disclaimer' => $disclaimer
        );

        return $result;

    }

    public function updateCounter(Request $request)
    {
        $slug = $request->input('slug');
        $type = $request->input('type');

        $article = App\Article::where('slug', $slug)->first();
        $article->$type = $article->$type + 1;
        $article->save();
    }

}