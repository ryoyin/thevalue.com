<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App;
use App\Article;
use App\Photo;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(50);

        $data = array(
            'menu' => array('article', 'article.list'),
            'articles' => $articles,
        );

        return view('backend.article.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langs = config('app.supported_languages');

        $detailFields = array(
            'title' => 'Title',
            'note' => 'Note',
            'short_desc' => 'Short Desc',
            'description' => 'Description',
            'source' => 'Source',
            'author' => 'Author',
            'photographer' => 'Photographer',
            'status' => 'Status');

        $data = array(
            'menu' => array('article', 'article.create'),
            'title' => 'Create',
            'action' => url('tvadmin/articles'),
            //category_id, slug, photo_id, hit_counter, share_counter, status
            'article' => array(
                'category_id' => old('category_id'),
                'slug' => old('slug'),
                'photo_id' => old('photo_id'),
                'hit_counter' => old('hit_counter'),
                'share_counter' => old('share_counter'),
                'published_at' => old('published_at'),
                'status' => old('status'),
            ),
            'langs' => $langs,
            'status' => config('app.status'),
            'gallery' => old('gallery'),
            'tags' => old('tags')
        );

        foreach($langs as $lang) {

            foreach($detailFields as $key => $field) {

                $data['article'][$key.'-'.$lang] = old($key.'-'.$lang);

            }

        }


        return view('backend.article.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $langs = config('app.supported_languages');

        // convert HK time to TUC
        $request->published_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->published_at)->subHours(8);

        $detailFields = array(
            'title' => 'Title',
            'note' => 'Note',
            'short_desc' => 'Short Desc',
            'description' => 'Description',
            'source' => 'Source',
            'author' => 'Author',
            'photographer' => 'Photographer',
            'status' => 'Status');

        //            category_id, slug, photo_id, hit_counter, share_counter
        $article = new App\Article;
        $article->category_id = $request->category_id;
        $article->slug = $request->slug;
        $article->photo_id = $request->photo_id;
        $article->hit_counter = $request->hit_counter;
        $article->share_counter = $request->share_counter;
        $article->published_at = $request->published_at;
        $article->status = $request->status;
        $description_en = 'description-en';
        if(trim($request->$description_en) != '') $article->en = 1;
        $description_trad = 'description-trad';
        if(trim($request->$description_trad) != '') $article->trad = 1;
        $description_sim = 'description-sim';
        if(trim($request->$description_sim) != '') $article->sim = 1;
        $article->save();

        if($request->gallery != "") {
            $gallery = explode(',', $request->gallery);
            $article->photos()->sync($gallery);
        }

//        exit;

        foreach($langs as $key => $lang) {
            $detail = new App\ArticleDetail;
            $detail->lang = $key;
            $detail->article_id = $article->id;
            foreach($detailFields as $dkey => $field) {
                $carrier = $dkey.'-'.$key;
                $detail->$dkey = $request->$carrier;
            }
            $detail->save();
        }

        return redirect('tvadmin/articles')->with('alert-success', 'Article was successful added!');;
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
        $article = App\Article::find($id);
//        $articleDetail = $article->details->where('lang', 'en')->first();
//        dd($articleDetail);
//        dd($article);
        $langs = config('app.supported_languages');

        $detailFields = array(
            'title' => 'Title',
            'note' => 'Note',
            'short_desc' => 'Short Desc',
            'description' => 'Description',
            'source' => 'Source',
            'author' => 'Author',
            'photographer' => 'Photographer',
            'status' => 'Status');

        if(old('photo_id') === NULL) {
//            category_id, slug, photo_id, hit_counter, share_counter
            $art = array(
                'category_id' => $article->category_id,
                'slug' => $article->slug,
                'photo_id' => $article->photo_id,
                'hit_counter' => $article->hit_counter,
                'share_counter' => $article->share_counter,
                'published_at' => $article->published_at,
                'status' => $article->status
            );

            foreach($langs as $lang) {
                $articleDetail = $article->details->where('lang', $lang)->first();
//                dd($articleDetail);
                foreach($detailFields as $key => $field) {

                    $art[$key.'-'.$lang] = $articleDetail->$key;

                }

            }
        } else {
            $art = array(
                'category_id' => old('category_id'),
                'slug' => old('slug'),
                'photo_id' => old('photo_id'),
                'hit_counter' => old('hit_counter'),
                'share_counter' => old('share_counter'),
                'published_at' => old('published_at'),
                'status' => old('status')
            );
        }


        $photos = $article->photos;

        $gallery = '';
        foreach($photos as $photo) {
            if($gallery != '') $gallery .= ',';
            $gallery .= $photo->id;
        }

        $tagsTemp = $article->tags;
        $tags = '';
        foreach($tagsTemp as $tag) {
            if($tags != '') $tags .= ',';
            $tags .= $tag->id;
        }

        $data = array(
            'title' => 'Modify',
            'menu' => array('article', 'article.list'),
            'article' => $art,
            'formMethod' => 'PUT',
            'action' => 'tvadmin/articles/'.$id,
            'langs' => $langs,
            'status' => config('app.status'),
            'gallery' => $gallery,
            'tags' => $tags,
        );

        return view('backend.article.form', $data);
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
        $langs = config('app.supported_languages');

        $detailFields = array(
            'title' => 'Title',
            'note' => 'Note',
            'short_desc' => 'Short Desc',
            'description' => 'Description',
            'source' => 'Source',
            'author' => 'Author',
            'photographer' => 'Photographer',
            'status' => 'Status');

        // convert HK time to TUC
        $request->published_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->published_at)->subHours(8);


//            category_id, slug, photo_id, hit_counter, share_counter
        $article = App\Article::find($id);
        $article->category_id = $request->category_id;
        $article->slug = $request->slug;
        $article->photo_id = $request->photo_id;
        $article->hit_counter = $request->hit_counter;
        $article->share_counter = $request->share_counter;
        $article->published_at = $request->published_at;
        $article->status = $request->status;
        $description_en = 'description-en';
        $article->en = trim($request->$description_en) == '' ?  0 : 1;
        $description_trad = 'description-trad';
        $article->trad = trim($request->$description_trad) == '' ?  0 : 1;
        $description_sim = 'description-sim';
        $article->sim = trim($request->$description_sim) == '' ?  0 : 1;
        $article->save();

        if($request->gallery != "") {
            $gallery = explode(',', $request->gallery);
            $article->photos()->sync($gallery);
        }

        if($request->tags != "") {
            $tags = explode(',', $request->tags);
//            dd($tags);
            $article->tags()->sync($tags);
        }

        foreach($langs as $key => $lang) {
            $detail = App\ArticleDetail::where('article_id', $article->id)->where('lang', $lang)->first();
//            dd($detail);
            $detail->lang = $key;
            $detail->article_id = $article->id;
            foreach($detailFields as $dkey => $field) {
                $carrier = $dkey.'-'.$key;
                $detail->$dkey = $request->$carrier;
            }
            $detail->save();
        }

        return redirect('tvadmin/articles')->with('alert-success', 'Article was successful updated!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = App\Article::findOrFail($id);
        $slug = $article->slug;
        $article->delete();

        App\ArticleDetail::where('article_id', $id)->delete();

        $article->photos()->detach();
        $article->tags()->detach();

        return redirect('tvadmin/articles')->with('alert-warning', '"<b>'.$slug.'<b>" have been removed');
    }

    public function checkFileName($destination_path, $filename, $fileExtension, $count = 0) {

        $image_path = $destination_path.$filename;

        if(file_exists($image_path)) {

            $count ++;
            $pos = strpos($filename, $fileExtension);

            if($count == 1) {
                $filename = substr($filename, 0, $pos-1).'_'.$count.'.'.$fileExtension;
            } else {
                $filename = substr($filename, 0, $pos - 2).$count.'.'.$fileExtension;
            }

            return $this->checkFileName($destination_path, $filename, $fileExtension, $count);

        }

        return $filename;

    }
}
