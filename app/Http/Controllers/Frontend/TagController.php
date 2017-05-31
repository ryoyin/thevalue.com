<?php

namespace App\Http\Controllers\Frontend;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;

class TagController extends Controller
{
    public $locale;

    public function index($slug)
    {
        $this->locale = App::getLocale();

        $sideBanners = $this->getBannerList('indexSideBanner', 'medium');
        $menuBanner = $this->getBannerList('indexMenuBanner', 'medium');

        $tag = App\Tag::where('slug', $slug)->first();
        $tagDetail = $tag->details->where('lang', $this->locale);

        if(count($tagDetail) == 0) {
            $tagDetail = $tag->details->where('lang', 'trad');
        }

//        dd($tagDetail);

        $tagStories = $this->getTagStories($tag);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => route('frontend.tag', ['slug' => $slug]),
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => asset('images/rocketfellercenter.jpg'),
            "app_id" => "1149533345170108",

        );

        $data = array(
            'slug' => $slug,
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'tagDetail' => $tagDetail[0],
            'tagStories' => $tagStories,
            'sideBanners' => $sideBanners,
            'menuBanner' => $menuBanner,
        );

        return view('frontend.tags.tags', $data);
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
                'published_at' => $article->published_at->format('M d, Y')
            );
        }

        return $articleList;
    }
}
