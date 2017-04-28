<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;
use App\Category;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Redirect;

class PageController extends Controller
{
    public function search() {
        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $sideBanners = $this->getBannerList('indexSideBanner', 'medium');

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com".$lang,
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
            'sideBanners' => $sideBanners,
        );

        return view('frontend.searches.searches', $data);
    }

    public function aboutUS(Request $request) {

        if($request->input('type') !== null) {
            switch ($request->input('type')) {
                case 'appview':
                    return view('mobile.aboutUS');
                    break;
            }
        }

        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com".$lang,
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
        );

        return view('frontend.aboutUS.aboutUS', $data);
    }

    public function disclaimer(Request $request) {

        if($request->input('type') !== null) {
            switch ($request->input('type')) {
                case 'appview':
                    return view('mobile.disclaimer');
                    break;
            }
        }

        if(!isset($_COOKIE['lang'])) {
            $_COOKIE['lang'] = 'trad';
        }
        $lang = $_COOKIE['lang'];
        App::setLocale($lang);

        $fbMetaArray = array(
            'site_name' => "TheValue",
            'url' => "http://www.thevalue.com".$lang,
            'type' => "website",
            'title' => "TheValue",
            "description" => "The Value 收取我們最新資訊",
            "image" => "http://www.thevalue.com/images/rocketfellercenter.jpg",
            "app_id" => "1149533345170108"
        );

        $data = array(
            'fbMeta' => $fbMetaArray,
            'categories' => $this->getCategoriesList(),
        );

        return view('frontend.disclaimer.disclaimer', $data);
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

    public function RedirectQRCode() {

        $agent = new Agent();

        //redirect iphone to apple app store
        if($agent->isIphone()) {
            return Redirect::to('https://www.apple.com/itunes/');
        }

        //redirect android to google app store
        if($agent->isAndroidOS()) {
            return Redirect::to('https://store.google.com/');
        }

        //redirect PC user to web page
        return redirect('/');

    }
}
