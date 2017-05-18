<?php

namespace App;
use App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Auction extends Model
{
    public function getAuction($slug)
    {

        $locale = App::getLocale();

        $auctionDateLogic = array('upcoming' => '>', 'post' => '<');

        $auctions = App\AuctionSeries::whereDate('end_date', $auctionDateLogic[$slug], Carbon::now())->get();

        foreach($auctions as $auction) {

        }

    }
}
