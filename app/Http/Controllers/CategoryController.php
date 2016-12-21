<?php

namespace App\Http\Controllers;

use App;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCategories($parent_id = null)
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
                'child' => $this->getCategories($category->id)
            );

//        break;
        }

        return $array;
    }

}
