<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App;
use App\Photo;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

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

                chdir(base_path());
                $baseDirectory = 'public';

                $newImagePath = $this->createPath($baseDirectory); //create path to images/yyyy/mm

                $filename = $uploaded_file->getClientOriginalName();

                // upload file
                $uploaded_file->move($newImagePath, $filename);

                $imagePath = $newImagePath.'/'.$filename;

                $fullFilename = basename($imagePath);

                $imageOrg = $newImagePath.'/'.$fullFilename;

                $imageOrg = str_replace('public/', '', $imageOrg);

                $filename = $this->getFilename($imagePath);
                $fileExtension = $this->getFileExtension($imagePath);

                // resize image to 1140px
                $imageLarge = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 1140);

                // resize image to 500px
                $imageMedium = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 500);

                // create 265px image
                $imageSmall = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 265);

                //create 42px image
                $imageBlur = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 42);

                $this->pushS3($baseDirectory, $imageOrg);
                $this->pushS3($baseDirectory, $imageBlur);
                $this->pushS3($baseDirectory, $imageSmall);
                $this->pushS3($baseDirectory, $imageMedium);
                $this->pushS3($baseDirectory, $imageLarge);

            } else {

                return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');

            }

        } else {
            return redirect('tvadmin/photos/create')->with('fileerrors', 'File upload failed!');
        }

        $photo = new App\Photo;
        $photo->alt = $request->alt;
        $photo->image_path = config('app.s3_path').$imageOrg;
        $photo->image_blur_path = config('app.s3_path').$imageBlur;
        $photo->image_small_path = config('app.s3_path').$imageSmall;
        $photo->image_medium_path = config('app.s3_path').$imageMedium;
        $photo->image_large_path = config('app.s3_path').$imageLarge;
        $photo->resized = '1';
        $photo->push_s3 = '1';
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

                chdir(base_path());
                $baseDirectory = 'public';

                $newImagePath = $this->createPath($baseDirectory); //create path to images/yyyy/mm

                $filename = $uploaded_file->getClientOriginalName();

                // upload file
                $uploaded_file->move($newImagePath, $filename);

                $imagePath = $newImagePath.'/'.$filename;

                $fullFilename = basename($imagePath);

                $imageOrg = $newImagePath.'/'.$fullFilename;

                $imageOrg = str_replace('public/', '', $imageOrg);

                $filename = $this->getFilename($imagePath);
                $fileExtension = $uploaded_file->getClientOriginalExtension();

                // resize image to 1140px
                $imageLarge = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 1140);

                // resize image to 500px
                $imageMedium = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 500);

                // create 265px image
                $imageSmall = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 265);

                //create 42px image
                $imageBlur = $this->resizeImage($imagePath, $newImagePath, $filename, $fileExtension, 42);

                $this->pushS3($baseDirectory, $imageOrg);
                $this->pushS3($baseDirectory, $imageBlur);
                $this->pushS3($baseDirectory, $imageSmall);
                $this->pushS3($baseDirectory, $imageMedium);
                $this->pushS3($baseDirectory, $imageLarge);

            }

        }

        $photo = App\Photo::find($id);
        $photo->alt = $request->alt;

        if($isValidFile) {
            $photo->image_path = config('app.s3_path').$imageOrg;
            $photo->image_blur_path = config('app.s3_path').$imageBlur;
            $photo->image_small_path = config('app.s3_path').$imageSmall;
            $photo->image_medium_path = config('app.s3_path').$imageMedium;
            $photo->image_large_path = config('app.s3_path').$imageLarge;
            $photo->resized = '1';
            $photo->push_s3 = '1';
        }

        $photo->save();

        return redirect('tvadmin/photos')->with('alert-success', 'Photo was successful updated!');;
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

    public function createPath($baseDirectory) {
        //check and create backup path
        $backupPath = $baseDirectory.'/images/';
        if(!file_exists($backupPath)) mkdir($backupPath);

        $backupPath = $baseDirectory.'/images/'.date('Y');
        if(!file_exists($backupPath)) mkdir($backupPath);

        $backupPath = $baseDirectory.'/images/'.date('Y').'/'.date('m');
        if(!file_exists($backupPath)) mkdir($backupPath);

        return $backupPath;
    }

    public function resizeImage($file, $resizePath, $filename, $fileExtension, $width)
    {
        $img = Image::make($file);

        $newPath = $resizePath.'/'.$filename.'-'.$width.'.'.$fileExtension;

        $img->widen($width, function ($constraint) {
            $constraint->upsize();
        })->save($newPath);

        $img = null;

        return str_replace('public/', '', $newPath);
    }

    public function getFilename($file)
    {
        $fullFilename = basename($file);
        $exFullFilename = explode('.', $fullFilename);
        return $exFullFilename[0];
    }

    public function getFileExtension($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    public function pushS3($baseDirectory, $filePath)
    {
        $s3 = \Storage::disk('s3');
        $localPath = $baseDirectory.'/'.$filePath;
        $image = fopen($localPath, 'r+');
        $s3->put('/'.$filePath, $image, 'public');
    }

}
