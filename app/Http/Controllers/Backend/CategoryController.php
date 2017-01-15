<?php

namespace App\Http\Controllers\Backend;

use App\CategoryDetail;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        $data = array(
            'menu' => array('category', 'category.list'),
            'categories' => $categories,
        );

        return view('backend.category.index', $data);
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
            'menu' => array('category', 'category.create'),
            'title' => 'Create',
            'action' => url('tvadmin/categories'),
            'category' => array(
                'slug' => old('slug'),
                'sorting' => old('sorting'),
                'status' => old('status'),
                'name-en' => old('name-en'),
                'name-trad' => old('name-trad'),
                'name-sim' => old('name-sim'),
            ),
            'status' => $status
        );

        return view('backend.category.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category;
        $category->slug = $request->slug;
        $category->sorting = $request->sorting;
        $category->status = $request->status;
        $category->save();

        $categoryDetail = new CategoryDetail;
        $categoryDetail->category_id = $category->id;
        $categoryDetail->lang = 'en';
        $categoryDetail->name = $request['name-en'];
        $categoryDetail->save();

        $categoryDetail = new CategoryDetail;
        $categoryDetail->category_id = $category->id;
        $categoryDetail->lang = 'trad';
        $categoryDetail->name = $request['name-trad'];
        $categoryDetail->save();

        $categoryDetail = new CategoryDetail;
        $categoryDetail->category_id = $category->id;
        $categoryDetail->lang = 'sim';
        $categoryDetail->name = $request['name-sim'];
        $categoryDetail->save();

        return redirect('tvadmin/categories')->with('alert-success', 'Category was successful added!');;
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

        $category = Category::find($id);

        $categoryDetail = $category->details->where('lang', 'en')->first();
        $nameEN = $categoryDetail->name;

        $categoryDetail = $category->details->where('lang', 'trad')->first();
        $nameTrad = $categoryDetail->name;

        $categoryDetail = $category->details->where('lang', 'sim')->first();
        $nameSIM = $categoryDetail->name;

        if(old('photo_id') === NULL) {
            $category = array(
                'slug' => $category->slug,
                'sorting' => $category->sorting,
                'status' => $category->status,
                'name-en' => $nameEN,
                'name-trad' => $nameTrad,
                'name-sim' => $nameSIM,
            );
        } else {
            $category = array(
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
            'menu' => array('category', 'category.list'),
            'category' => $category,
            'formMethod' => 'PUT',
            'action' => 'tvadmin/categories/'.$id,
            'status' => $status
        );

        return view('backend.category.form', $data);
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

        $category = Category::find($id);
        $category->slug = $request->slug;
        $category->sorting = $request->sorting;
        $category->status = $request->status;
        $category->save();

        $categoryDetail = CategoryDetail::where('category_id', $category->id)->where('lang', 'en')->first();
        $categoryDetail->name = $request['name-en'];
        $categoryDetail->save();

        $categoryDetail = CategoryDetail::where('category_id', $category->id)->where('lang', 'trad')->first();
        $categoryDetail->name = $request['name-trad'];
        $categoryDetail->save();

        $categoryDetail = CategoryDetail::where('category_id', $category->id)->where('lang', 'sim')->first();
        $categoryDetail->name = $request['name-sim'];
        $categoryDetail->save();

        return redirect('tvadmin/categories')->with('alert-success', 'Banner was successful updated!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $alt = $category->alt;

        $categoryDetail = CategoryDetail::where('category_id', $category->id);
        $categoryDetail->delete();

        $category->delete();

        return redirect('tvadmin/categories')->with('alert-warning', '"<b>'.$alt.'<b>" have been removed');
    }


}
