<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionSeries extends Model
{
    protected $table = 'auction_series';

    public function house()
    {
        return $this->belongsTo('App\AuctionHouse', 'auction_house_id');
    }

    public function details()
    {
        return $this->hasMany('App\AuctionSeriesDetail');
    }

    public function sales()
    {
        return $this->hasMany('App\AuctionSale');
    }

}
