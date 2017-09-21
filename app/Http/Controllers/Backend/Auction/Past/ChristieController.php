<?php

namespace App\Http\Controllers\Backend\Auction\Past;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Model\Auction\Past;

class ChristieController extends Controller
{
    public function sales(Request $request)
    {
        if(isset($request->filterJSON)) {
            if($request->filterJSON == 1) {
                $sales = App\ChristieSalesChecking::where('retrieve_server', '<>', 99)->where('year', '>', 1997)->where('json', 1)->paginate(10);
            } else {
                $sales = App\ChristieSalesChecking::where('retrieve_server', '<>', 99)->where('year', '>', 1997)->where('json', 0)->paginate(10);
            }
         } else {
            $sales = App\ChristieSalesChecking::where('retrieve_server', '<>', 99)->where('year', '>', 1997)->paginate(10);
        }

        $data = array(
            'menu' => array('auction', 'past', 'backend.auction.past.christie.sales'),
            'sales' => $sales
        );

        return view('backend.auctions.past.christie.sales', $data);
    }

    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\Backend\Auction\Past\ChristieController');
    // app()->call([$controller, 'checkJSONExists']);

    public function checkJSONExists()
    {
        $sales = App\ChristieSalesChecking::where('year', '>', 1997)->where('json', '=', null)->get();
        $count = 0;
        foreach($sales as $sale) {
            $count ++;
            echo "Checking Count ".$count." Int Sale ID: ".$sale->int_sale_id."\n";
            $sale->json = $sale->doesObjectExist() ? 1:0;
            $sale->save();
        }
    }

    public function importSale()
    {
        // get sale for import
        $sales = App\ChristieSalesChecking::where('year', '>', 1997)->where('json', '=', 1)->where('import', null)->limit(1)->get();

        foreach($sales as $sale) {
            $json = $sale->getJSON();

            $sale = New App\Model\Auction\Past\PastAuctionSale;
            $sale->slug = 1;
            $sale->image_path = 1;
            $sale->image_fit_path = 1;
            $sale->image_small_path = 1;
            $sale->image_medium_path = 1;
            $sale->image_large_path = 1;
            $sale->image_large_path = 1;
            $sale->number = 1;
            $sale->total_lots = 1;
            $sale->start_date = '1999-01-01 00:00:00';
            $sale->end_date = '1999-01-01 00:00:00';
            $sale->auction_house_id = 1;
            $sale->country_id = 1;
            $sale->city_id = 1;
            $sale->save();

        }
    }
}
