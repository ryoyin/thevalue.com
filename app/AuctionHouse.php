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

    public function detail()
    {
        return $this->hasMany('App\AuctionHouseDetail');
    }

}
