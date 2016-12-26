<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/listapi', function () {
    $list = array(
        url('api/testtranslate'),
        url('api/categories/html'),
        url('api/categories/array')
    );

    echo '<pre>';
    print_r($list);
});

Route::get('/testtranslate', function () {
    return trans('general.test');
});

use App\Category;

Route::get('categories/html', function () {
    return getCategoriesHTML(null);
});

Route::get('categories/array', function () {
    dd(getCategoriesArray(null));
});

function getCategoriesHTML($parent_id)
{
    $categories = Category::where('parent_id', $parent_id)->get();
    $html = "<ul>";
    foreach ($categories as $category) {
        $html .= "<li>{$category->slug}";
        $html .= getCategoriesHTML($category->id);
        $html .= "</li>";
    }
    $html .= "</ul>";

    return $html;
}

function getCategoriesArray()
{

    $locale = App::getLocale();
    $categories = Category::orderBy('parent_id')->orderBy('sorting')->get();

    $array = array();
    foreach ($categories as $category) {

        $categoryDetail = getCategory($category, $locale);
        $categoryName = $categoryDetail->name;

//        dd($category);

        // get parent detail
        $parent = null;
        if($category->parent_id != null) {
            $parent = Category::where('id', $category->parent_id)->first();
            $parentDetail = getCategory($parent, $locale);
            $parent = array(
                'id' => $category->parent_id,
                'slug' => $parent->slug,
                'name' => $parentDetail->name
            );
        }

        //get child detail
        $child = null;
        $children = Category::where('parent_id', $category->id)->orderBy('sorting')->get();

//        dd($chil)

        if(count($children)) {
            $child = array();
            foreach($children as $cate) {
                $childDetail = getCategory($cate, $locale);
                $child[] = array(
                    'id' => $cate->id,
                    'slug' => $childDetail->slug,
                    'name' => $childDetail->name
                );
            }
        }

        $array[] = array(
            'id' => $category->id,
            'slug' => $category->slug,
            'name' => $categoryName,
            'url' => 'hyperlink',
            'parent' => $parent,
            'child' => $child
        );

//        break;
    }

    return $array;
}

function getCategory($category, $locale)
{

    $categoryDetail = $category->details()->where('lang', $locale)->first();

    if (sizeof($categoryDetail) == 0) {
        $categoryDetail = $category->details()->where('lang', 'en')->first();
    }

    return $categoryDetail;

}

function getChild()
{

}


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
