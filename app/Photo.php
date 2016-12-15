<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public function banners()
    {
        return $this->belongsTo('App\Banner');
    }
}
