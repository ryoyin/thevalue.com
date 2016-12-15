<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function parent()
    {
        return $this->belongsTo('Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Category', 'parent_id');
    }

    public function details()
    {
        return $this->hasMany('App\CategoryDetail');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

}
