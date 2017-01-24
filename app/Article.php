<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $dates = ['created_at', 'updated_at', 'published_at'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function details()
    {
        return $this->hasMany('App\ArticleDetail');
    }

    public function featuredArticle()
    {
        return $this->belongsTo('App\FeaturedArticle');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function photo()
    {
        return $this->belongsTo('App\Photo');
    }

    public function photos()
    {
        return $this->belongsToMany('App\Photo');
    }
}
