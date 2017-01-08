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
            'menu' => array('photo', 'photo.create'),
            'title' => 'Create',
            'action' => url('tvadmin/photos'),
            'photo' => array(
                'alt' => old('alt'),
                'image_path' => old('image_path'),
            ),
        );

        return view('backend.photo.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate user file
        $uploaded_file = NULL;
        $isValidFile = FALSE;

        if ($request->hasFile('uploaded_file')) {

            $uploaded_file = $request->file('uploaded_file');

            $mimeType = $uploaded_file->getMimeType();

            $validFileType = array('png', 'pdf', 'jpeg');

            foreach($validFileType AS $fileType) {

                if(strpos($mimeType, $fileType)) {
                    $isValidFile = TRUE;
                }

            }

            if($isValidFile) {

                $alternative_path = 'images/';
                $destination_path = public_path().'/'.$alternative_path;
                $filename = $uploaded_file->getClientOriginalName();
                $fileExtension = $uploaded_file->getClientOriginalExtension();

                $filename = $this->checkFileName($destination_path, $filename, $fileExtension, 0);

                $uploaded_file->move($destination_path, $filename);

            } else {

                return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');

            }

        } else {
            return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');
        }

        $photo = new App\Photo;
        $photo->alt = $request->alt;
        $photo->image_path = $alternative_path.$filename;
        $photo->save();

        return redirect('tvadmin/photos')->with('alert-success', 'Photo was successful added!');;
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
        $photo = App\Photo::find($id);

        if(old('alt') === NULL) {
            $photo = array(
                'alt' => $photo->alt,
                'image_path' => $photo->image_path
            );
        } else {
            $photo = array(
                'alt' => old('alt'),
                'image_path' => $photo->image_path
            );
        }

        $data = array(
            'title' => 'Modify',
            'menu' => array('photo', 'photo.list'),
            'photo' => $photo,
            'formMethod' => 'PUT',
            'action' => 'tvadmin/photos/'.$id
        );

        return view('backend.photo.form', $data);
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
        //validate user file
        $uploaded_file = NULL;
        $isValidFile = FALSE;

        if ($request->hasFile('uploaded_file')) {

            $uploaded_file = $request->file('uploaded_file');

            $mimeType = $uploaded_file->getMimeType();

            $validFileType = array('png', 'pdf', 'jpeg');

            foreach($validFileType AS $fileType) {

                if(strpos($mimeType, $fileType)) {
                    $isValidFile = TRUE;
                }

            }

            if($isValidFile) {

                $alternative_path = 'document/research/';
                $destination_path = public_path().'/'.$alternative_path;
                $filename = $uploaded_file->getClientOriginalName();
                $fileExtension = $uploaded_file->getClientOriginalExtension();

                $filename = $this->checkFileName($destination_path, $filename, $fileExtension, 0);

                $uploaded_file->move($destination_path, $filename);

            } else {

                return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');

            }

        } else {

            return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');

        }

        $photo = App\Photo::find($id);
        $photo->alt = $request->alt;

        if($isValidFile) {
            $photo->image_path = $alternative_path.$filename;
        }

        $photo->save();

        return redirect('tvadmin/photos')->with('alert-success', 'Research was successful updated!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = App\Photo::findOrFail($id);
        $alt = $photo->alt;
        $photo->delete();

        return redirect('tvadmin/photos')->with('alert-warning', '"<b>'.$alt.'<b>" have been removed');
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
