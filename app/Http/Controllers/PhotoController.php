<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Photo;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::all();

        $data = array(
            'menu' => array('photo', 'photo.list'),
            'photos' => $photos,
        );

        return view('backend.photo.index', $data);
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
            'title' => '增加',
            'action' => url('tvadmin/photos'),
            'photo' => array(
                'alt' => old('alt'),
                'filePath' => old('filePath'),
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

                return redirect('tvadmin/photos/create')->with('errors', 'File upload failed!');

            }

        } else {
            return redirect('tvadmin/photos/create')->with('errors', 'File upload failed!');
        }

        $photo = new App\Photo;
        $photo->alt = $request->alt;


         $photo->file_path = $alternative_path.$filename;


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
        return 123;
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
}
