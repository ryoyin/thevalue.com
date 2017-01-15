<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Tag;
use App\TagDetail;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();

        $data = array(
            'menu' => array('tag', 'tag.list'),
            'tags' => $tags,
        );

        return view('backend.tag.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = config('app.status');
        $data = array(
            'menu' => array('tag', 'tag.create'),
            'title' => 'Create',
            'action' => url('tvadmin/tags'),
            'tag' => array(
                'slug' => old('slug'),
                'name-en' => old('name-en'),
                'name-trad' => old('name-trad'),
                'name-sim' => old('name-sim'),
            ),
            'status' => $status
        );

        return view('backend.tag.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag;
        $tag->slug = $request->slug;
        $tag->save();

        $tagDetail = new TagDetail;
        $tagDetail->tag_id = $tag->id;
        $tagDetail->lang = 'en';
        $tagDetail->name = $request['name-en'];
        $tagDetail->save();

        $tagDetail = new TagDetail;
        $tagDetail->tag_id = $tag->id;
        $tagDetail->lang = 'trad';
        $tagDetail->name = $request['name-trad'];
        $tagDetail->save();

        $tagDetail = new TagDetail;
        $tagDetail->tag_id = $tag->id;
        $tagDetail->lang = 'sim';
        $tagDetail->name = $request['name-sim'];
        $tagDetail->save();

        return redirect('tvadmin/tags')->with('alert-success', 'Tag was successful added!');;
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
        $status = config('app.status');

        $tag = Tag::find($id);

        $tagDetail = $tag->details->where('lang', 'en')->first();
        $nameEN = $tagDetail->name;

        $tagDetail = $tag->details->where('lang', 'trad')->first();
        $nameTrad = $tagDetail->name;

        $tagDetail = $tag->details->where('lang', 'sim')->first();
        $nameSIM = $tagDetail->name;

        if(old('photo_id') === NULL) {
            $tag = array(
                'slug' => $tag->slug,
                'sorting' => $tag->sorting,
                'status' => $tag->status,
                'name-en' => $nameEN,
                'name-trad' => $nameTrad,
                'name-sim' => $nameSIM,
            );
        } else {
            $tag = array(
                'slug' => old('slug'),
                'sorting' => old('sorting'),
                'status' => old('status'),
                'name-en' => old('name-en'),
                'name-trad' => old('name-trad'),
                'name-sim' => old('name-sim'),
            );
        }

        $data = array(
            'title' => 'Modify',
            'menu' => array('tag', 'tag.list'),
            'tag' => $tag,
            'formMethod' => 'PUT',
            'action' => 'tvadmin/tags/'.$id,
            'status' => $status
        );

        return view('backend.tag.form', $data);
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

        $tag = Tag::find($id);
        $tag->slug = $request->slug;
        $tag->save();

        $tagDetail = TagDetail::where('tag_id', $tag->id)->where('lang', 'en')->first();
        $tagDetail->name = $request['name-en'];
        $tagDetail->save();

        $tagDetail = TagDetail::where('tag_id', $tag->id)->where('lang', 'trad')->first();
        $tagDetail->name = $request['name-trad'];
        $tagDetail->save();

        $tagDetail = TagDetail::where('tag_id', $tag->id)->where('lang', 'sim')->first();
        $tagDetail->name = $request['name-sim'];
        $tagDetail->save();

        return redirect('tvadmin/tags')->with('alert-success', 'Banner was successful updated!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $alt = $tag->alt;

        $tagDetail = TagDetail::where('tag_id', $tag->id);
        $tagDetail->delete();

        $tag->delete();

        return redirect('tvadmin/tags')->with('alert-warning', '"<b>'.$alt.'<b>" have been removed');
    }


}
