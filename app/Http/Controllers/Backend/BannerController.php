<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App;
use App\Banner;
use App\Photo;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();

        $data = array(
            'menu' => array('banner', 'banner.list'),
            'banners' => $banners,
        );

        return view('backend.banner.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'menu' => array('banner', 'banner.create'),
            'title' => 'Create',
            'action' => url('tvadmin/banners'),
            'banner' => array(
                'photo_id' => old('photo_id'),
                'position' => old('position'),
                'sorting' => old('sorting'),
                'status' => old('status')
            ),
        );

        return view('backend.banner.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $banner = new App\Banner;
        $banner->photo_id = $request->photo_id;
        $banner->position = $request->position;
        $banner->sorting = $request->sorting;
        $banner->status = $request->status;
        $banner->save();

        return redirect('tvadmin/banners')->with('alert-success', 'Banner was successful added!');;
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
        $banner = App\Banner::find($id);

        if(old('photo_id') === NULL) {
            $banner = array(
                'photo_id' => $banner->photo_id,
                'position' => $banner->position,
                'sorting' => $banner->sorting,
                'status' => $banner->status
            );
        } else {
            $banner = array(
                'photo_id' => old('photo_id'),
                'position' => old('position'),
                'sorting' => old('sorting'),
                'status' => old('status')
            );
        }

        $data = array(
            'title' => 'Modify',
            'menu' => array('banner', 'banner.list'),
            'banner' => $banner,
            'formMethod' => 'PUT',
            'action' => 'tvadmin/banners/'.$id
        );

        return view('backend.banner.form', $data);
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

        $banner = App\Banner::find($id);
        $banner->photo_id = $request->photo_id;
        $banner->position = $request->position;
        $banner->sorting = $request->sorting;
        $banner->status = $request->status;
        $banner->save();

        return redirect('tvadmin/banners')->with('alert-success', 'Banner was successful updated!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = App\Banner::findOrFail($id);
        $alt = $banner->alt;
        $banner->delete();

        return redirect('tvadmin/banners')->with('alert-warning', '"<b>'.$alt.'<b>" have been removed');
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
