<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function parent()
    {
        return $this->belongsTo('Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Category', 'parent_id');
    }

    public function details()
    {
        return $this->hasMany('App\CategoryDetail');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function getCategoriesArray($id = null)
    {

        $locale = App::getLocale();

        if($id == null) {
            $categories = Category::orderBy('parent_id')->orderBy('sorting')->get();
        } else {
            $categories = Category::where('id', $id)->get();
        }

        $array = array();
        foreach ($categories as $category) {

            $categoryDetail = $this->getCategory($category, $locale);
            $categoryName = $categoryDetail->name;

//        dd($category);

            // get parent detail
            $parent = null;
            if($category->parent_id != null) {
                $parent = Category::where('id', $category->parent_id)->first();
                $parentDetail = $this->getCategory($parent, $locale);
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
                    $childDetail = $this->getCategory($cate, $locale);
                    $child[] = array(
                        'id' => $cate->id,
                        'slug' => $childDetail->slug,
                        'name' => $childDetail->name
                    );
                }
            }

            $defaultCategoryDetail = $this->getCategory($category, 'en');

            $array[] = array(
                'id' => $category->id,
                'slug' => $category->slug,
                'default_name' => $defaultCategoryDetail->name,
                'name' => $categoryName,
                'url' => 'categories',
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
