<?php

namespace App\Http\Controllers\Scripts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

// php artisan tinker
// $controller = app()->make('App\Http\Controllers\Scripts\CheckPhotoController');
// app()->call([$controller, 'checkPhoto'], []);

class CheckPhotoController extends Controller
{
    public function checkPhoto()
    {
        $photos = App\Photo::all();

        $checkArray = array();

        foreach($photos as $photo) {

            $fileName = basename($photo->image_path);

            $sPhoto = App\Photo::where('image_path', 'like', '%'.$fileName)->get();
            if(count($sPhoto) > 1) {
                foreach($sPhoto as $sP) {
                    if($photo->id > $sP->id) {
//                        echo $photo->id.'<br>';
                        if(!in_array($photo->id, $checkArray)) {
                            $checkArray[] = $photo->id;
                        }
                    }
                }
            }

//            break;
        }

        echo '<pre>';
        print_r($checkArray);
    }
}
