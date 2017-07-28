<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChristieSpider extends Model
{
    protected $table = 'christie_spiders';

    public function sales()
    {
        return $this->hasMany('App\ChristieSpiderSale');
    }
}
