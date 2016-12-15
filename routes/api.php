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

Route::get('/listapi', function() {
    $list = array(
        url('api/testtranslate'),
        url('api/categories/html'),
        url('api/categories/array')
    );

    echo '<pre>';
    print_r($list);
});

Route::get('/testtranslate', function() {
   return trans('general.test');
});

use App\Category;
Route::get('categories/html', function() {
    return getCategoriesHTML(null);
});

Route::get('categories/array', function() {
    dd(getCategoriesArray(null));
});

function getCategoriesHTML($parent_id)
{
    $categories = Category::where('parent_id', $parent_id)->get();
    $html = "<ul>";
    foreach($categories as $category) {
        $html .= "<li>{$category->slug}";
        $html .= getCategoriesHTML($category->id);
        $html .= "</li>";
    }
    $html .= "</ul>";

    return $html;
}

function getCategoriesArray($parent_id)
{
    $locale = App::getLocale();
    $categories = Category::where('parent_id', $parent_id)->get();

    $array = array();
    foreach($categories as $category) {
        $categoryDetail = $category->details()->where('lang', $locale)->first();

        if(sizeof($categoryDetail) == 0) {
            $categoryDetail = $category->details()->where('lang', 'en')->first();
        }

        $categoryName = $categoryDetail->name;

        $array[$categoryName] = array(
            'url'   => 'hyperlink',
            'child' => getCategoriesArray($category->id)
        );

//        break;
    }

    /*
    array:3 [
        "TV" => array:2 [
        "url" => "hyperlink"
        "child" => array:2 [
        "LCD" => array:2 [
        "url" => "hyperlink"
            "child" => []
          ]
          "Plasma" => array:2 [
        "url" => "hyperlink"
            "child" => []
          ]
        ]
      ]
      "Cell Phone" => array:2 [
        "url" => "hyperlink"
        "child" => array:2 [
        "iPhone" => array:2 [
        "url" => "hyperlink"
            "child" => []
          ]
          "Android" => array:2 [
        "url" => "hyperlink"
            "child" => array:1 [
        "Samsung Note 7" => array:2 [
        "url" => "hyperlink"
                "child" => []
              ]
            ]
          ]
        ]
      ]
      "Computer" => array:2 [
        "url" => "hyperlink"
        "child" => []
      ]
    ]
    */

    return $array;
}


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
