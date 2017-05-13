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

        // Start: get description image for gallery
        preg_match_all('/(\<img[^>]+)(src\=\"[^\"]+\")([^>]+)(>)/', $articleDetails['description'], $matches);

        $gallery_image_array = array();

        //get article photos list
        $articlePhotoList = $this->getArticlePhotoList($article);

        $img_count = 0;
        foreach($articlePhotoList as $sKey => $photo) {
            $found_image_result = getimagesize($photo['image_path']);
            $gallery_image_array[$img_count] = $found_image_result;

            $found_image_path = $photo['s3'] ? config("app.s3_path").$photo['image_path'] : asset($photo['image_path']);
            $gallery_image_array[$img_count]['image_path'] = $found_image_path;
            $img_count ++;
        }

//        dd($matches[2]);
        foreach($matches[2] as $sKey => $src) {
            $found_image = str_replace('src=', '', $src);
            $found_image = str_replace('"', '', $found_image);

            $found_image_filename = basename($found_image);

            $photo = App\Photo::where('image_medium_path', 'like', '%'.$found_image_filename)->orwhere('image_path', 'like', '%'.$found_image_filename)->first();

            $display_image = $photo->image_large_path == "" ? $photo->image_path : $photo->image_large_path;


            $found_image_result = getimagesize($display_image);

            $gallery_image_array[$img_count] = $found_image_result;

            $found_image_path = $photo['push_s3'] ? config("app.s3_path").$display_image : asset($display_image);
            $gallery_image_array[$img_count]['image_path'] = $found_image_path;

            $img_count++;
        }

//        dd($gallery_image_array);

        $articleDetails['description'] = preg_replace('/(\<img[^>]+)(style\=\"[^\"]+\")([^>]+)(>)/', '${1} class="img-responsive" onclick="galleryInit(this)" ${3}${4}', $articleDetails['description']);
        // End: get description image for gallery

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
            'gallery' => $gallery_image_array,
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

