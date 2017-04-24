<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FeaturedArticle;

class FeaturedArticleController extends Controller
{
    public function index()
    {
        $featureArticles = FeaturedArticle::all();

        $data = array(
            'menu' => array('featuredArticle', 'featuredArticle.list'),
            'featureArticles' => $featureArticles,
        );

        return view('backend.featuredArticle.index', $data);
    }

    public function create()
    {
        $data = array(
            'menu' => array('featuredArticles', 'featuredArticles.create'),
            'title' => 'Create',
            'action' => url('tvadmin/featuredArticles'),
            'featuredArticle' => array(
                'article_id' => old('article_id'),
                'sorting' => old('sorting'),
            ),
        );

        return view('backend.featuredArticle.form', $data);
    }

    public function store(Request $request)
    {
        $featuredArticle = new FeaturedArticle;
        $featuredArticle->article_id = $request->article_id;
        $featuredArticle->sorting = $request->sorting;
        $featuredArticle->save();

        return redirect('tvadmin/featuredArticles')->with('alert-success', 'Featured Article was successful added!');;
    }

    public function edit($id)
    {
        $featuredArticle = FeaturedArticle::find($id);

        if(old('photo_id') === NULL) {
            $featuredArticle = array(
                'article_id' => $featuredArticle->article_id,
                'sorting' => $featuredArticle->sorting,
            );
        } else {
            $featuredArticle = array(
                'article_id' => old('article_id'),
                'sorting' => old('sorting'),
            );
        }

        $data = array(
            'title' => 'Modify',
            'menu' => array('featuredArticles', 'featuredArticles.list'),
            'featuredArticle' => $featuredArticle,
            'formMethod' => 'PUT',
            'action' => 'tvadmin/featuredArticles/'.$id,
        );

        return view('backend.featuredArticle.form', $data);
    }

    public function update(Request $request, $id)
    {
        $featuredArticle = FeaturedArticle::find($id);
        $featuredArticle->article_id = $request->article_id;
        $featuredArticle->sorting = $request->sorting;
        $featuredArticle->save();

        return redirect('tvadmin/featuredArticles')->with('alert-success', 'Featured Article was successful updated!');;
    }

    public function destroy($id)
    {
        $featuredArticle = FeaturedArticle::findOrFail($id);
        $featuredArticle->delete();

        return redirect('tvadmin/featuredArticles')->with('alert-warning', '"<b>Featured Article<b>" have been removed');
    }
}
