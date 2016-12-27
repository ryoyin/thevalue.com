<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function getCategoriesArray()
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

    public function getCategory($category, $locale)
    {

        $categoryDetail = $category->details()->where('lang', $locale)->first();

        if (sizeof($categoryDetail) == 0) {
            $categoryDetail = $category->details()->where('lang', 'en')->first();
        }

        return $categoryDetail;

    }

    public function getChild()
    {

    }

}
