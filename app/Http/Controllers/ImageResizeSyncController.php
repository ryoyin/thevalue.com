<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Photo;
use App\PhotoDetail;

class ImageResizeSyncController extends Controller
{

    public function index()
    {
        ini_set('memory_limit','256M');

        $blurVal = 15;
        $baseDirectory = 'public';

        $backupPath = $this->createBackupPath($baseDirectory); //create backup path to images/backup/yyyy/mm

        $resizePath = $this->createResizePath($baseDirectory); //create resize path to images/yyyy/mm

        $fileArray = array();
        $photos = Photo::where('resized', 0)->limit(10)->get();
        foreach($photos as $photo) {
            $imagePath = $baseDirectory.'/'.$photo->image_path;

            // backup original image
            $fullFilename = basename($imagePath);

            $imageOrg = $resizePath.'/'.$fullFilename;
//            echo $imagePath;
//            echo "\n";
//
//            echo $imageOrg;
//            echo "\n";
//            return;

            copy($imagePath, $imageOrg);
            $imageOrg = str_replace('public/', '', $imageOrg);

            $filename = $this->getFilename($imagePath);
            $fileExtension = $this->getFileExtension($imagePath);

            //create original size blur image
//            $imageBlur = $img->blur($blurVal)->save($resizePath.'/'.$filename.'-blur.'.$fileExtension);

            // resize image to 1140px
            $imageLarge = $this->resizeImage($imagePath, $resizePath, $filename, $fileExtension, 1140, $blurVal);

            // resize image to 500px
            $imageMedium = $this->resizeImage($imagePath, $resizePath, $filename, $fileExtension, 500, $blurVal);

            // create 265px image
            $imageSmall = $this->resizeImage($imagePath, $resizePath, $filename, $fileExtension, 265, $blurVal);

            //create 42px image
            $imageBlur = $this->resizeImage($imagePath, $resizePath, $filename, $fileExtension, 42, $blurVal);

            // move image to backup directory
            rename($imagePath, $backupPath.'/'.$fullFilename);

            $this->pushS3($baseDirectory, $imageOrg);
            $this->pushS3($baseDirectory, $imageBlur);
            $this->pushS3($baseDirectory, $imageSmall);
            $this->pushS3($baseDirectory, $imageMedium);
            $this->pushS3($baseDirectory, $imageLarge);

            $photo->image_path = $imageOrg;
            $photo->image_blur_path = $imageBlur;
            $photo->image_small_path = $imageSmall;
            $photo->image_medium_path = $imageMedium;
            $photo->image_large_path = $imageLarge;
            $photo->resized = '1';
            $photo->push_s3 = '1';
            $photo->save();

        }

    }

    public function resizeImage($file, $resizePath, $filename, $fileExtension, $width, $blurVal)
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

    public function createBackupPath($baseDirectory) {
        //check and create backup path
        $backupPath = $baseDirectory.'/images/backup';
        if(!file_exists($backupPath)) mkdir($backupPath);

        $backupPath = $baseDirectory.'/images/backup/'.date('Y');
        if(!file_exists($backupPath)) mkdir($backupPath);

        $backupPath = $baseDirectory.'/images/backup/'.date('Y').'/'.date('m');
        if(!file_exists($backupPath)) mkdir($backupPath);

        return $backupPath;
    }

    public function createResizePath($baseDirectory) {
        //check and create resize path
        $resizePath = $baseDirectory.'/images/'.date('Y');
        if(!file_exists($resizePath)) mkdir($resizePath);

        $resizePath = $baseDirectory.'/images/'.date('Y').'/'.date('m');
        if(!file_exists($resizePath)) mkdir($resizePath);

        return $resizePath;
    }
}
