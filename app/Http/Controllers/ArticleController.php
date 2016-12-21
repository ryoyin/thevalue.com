<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Article;

class ArticleController extends Controller
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

    public function getArticleByID(Request $request, $id, $status = 'published', $shortDesc = false, $desc = true)
    {
        $locale = App::getLocale();

//        echo $id;

        $article = Article::where('id', $id)->first();
        $detail = $article->details->where('lang', $locale)->first();

//        dd($detail);

        $category = $article->category;
        $categoryDetail = $category->details->where('lang', $locale)->first();

//        dd($categoryDetail);

        $return = array(
            'article' => array(
                'id' => $article->id,
                'slug' => $article->slug,
                'title' => $detail->title,
                'short_desc' => $detail->short_desc,
                'description' => $detail->description,
                'category' => array(
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'name' => $categoryDetail->name
                ),
                'image' => array(
                    'small' => 'small_url',
                    'medium' => 'medium_url',
                    'original' => 'original_url'
                )
            ),
        );

        return $return;

    }

    public function getTagsByID($id)
    {
        //
    }

    public function getArticlesByCategoryID($category_id)
    {
        //
    }

    public function getRelatedArticles($id)
    {
        //
    }

    public function getArticleCategory()
    {
        //
    }

}
