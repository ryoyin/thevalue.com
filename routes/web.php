<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', 'HomeController@index');

    Route::resource('tvadmin/photos', 'PhotoController');

});

/*
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
        $html .= "<li>{$category->name}";
        $html .= getCategoriesHTML($category->id);
        $html .= "</li>";
    }
    $html .= "</ul>";

    return $html;
}

function getCategoriesArray($parent_id)
{
    $categories = Category::where('parent_id', $parent_id)->get();

    $array = array();
    foreach($categories as $category) {
        $array[$category->name] = array(
            'url'   => 'hyperlink',
            'child' => getCategoriesArray($category->id)
        );
    }

    return $array;
}*/



