<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionHouse extends Model
{
    protected $table = 'auction_houses';

    public function series()
    {
        return $this->hasMany('App\AuctionSeries');
    }

    public function details()
    {
        return $this->hasMany('App\AuctionHouseDetail');
    }

    public function getDetailByLang($lang)
    {
        return $this->details()->where('lang', $lang)->first();
    }

}
