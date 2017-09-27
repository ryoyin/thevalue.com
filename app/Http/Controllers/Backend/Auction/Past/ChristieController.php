<?php

namespace App\Http\Controllers\Backend\Auction\Past;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Model\Auction\Past;
use Illuminate\Support\Facades\Storage;

class ChristieController extends Controller
{
    public $languages = array('trad', 'en', 'sim');

    public $house = null;

    public $intSaleID = null;
    public $saleArray = null;
    public $sale = null;
    public $slug = null;

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

    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\Backend\Auction\Past\ChristieController');
    // app()->call([$controller, 'importSale']);
    public function importSale()
    {
        // get Christie's house id
        $this->house = App\AuctionHouse::where('slug', 'christies')->first();

        // get sale for import
        $sales = App\ChristieSalesChecking::where('year', '>', 1997)->where('json', '=', 1)->where('import', null)->get();

        foreach($sales as $sale) {

            $this->intSaleID = null;
            $this->saleArray = null;
            $this->sale = null;

            // get internal sale id
            $this->intSaleID = $sale->int_sale_id;

            // get sale content in json format
            $this->saleArray = $sale->getJSON();

            $saleArray = $this->saleArray;

//            dd($this->saleArray);

            // insert data to past_auction_sales table
            $this->insertPastAuctionSale();

            // insert data to past_auction_sale_details table
            $this->importPastAuctionSaleDetails();

            // insert data to past_auction_items table
            foreach($saleArray['lots'] as $lot) {

                $item = New App\Model\Auction\Past\PastAuctionItem;

                //item info
                $item->slug = $this->slug.'-'.$lot['number'];
                $item->number = $lot['number'];
                $item->dimension = $lot['medium-dimensions'];

                $estimate = $this->getEstimate($lot['estimate']);

                $item->estimate_value_initial = $estimate['estimate_value_initial'];
                $item->estimate_value_end = $estimate['estimate_value_end'];
                $item->sold_value = $this->convertValue($lot['price']);
                $item->auction_sale_id = $this->sale->id;

                // item image
                $item->image_path = $lot['image_path'];
                $item->image_fit_path = null;
                $item->image_small_path = null;
                $item->image_medium_path = null;
                $item->image_large_path = null;

                //pending, sold, bought in, withdraw, noshow
                if($item->sold_value == null) {
                    $item->status = 4;
                } else {
                    $item->status = 1;
                }

                $item->save();

                foreach($this->languages as $language) {
                    
                }

            }

            // insert data to past_auction_item_details table

            exit;

        }
    }

    public function getEstimate($estimate)
    {

        if($estimate == 'Estimate on request' || $estimate == '') {

            $currencyCode = null;
            $estimate_value_initial = null;
            $estimate_value_end = null;

        } else {

            $exEstimate = explode('-', $estimate);

            $estimate_value_initial = $this->convertValue($exEstimate[0]);
            $estimate_value_end = $this->convertValue($exEstimate[1]);

        }

        return array('estimate_value_initial' => $estimate_value_initial, 'estimate_value_end' => $estimate_value_end);

    }

    private function convertValue($value)
    {
        trim($value);

        $value = str_replace(',', '', $value);
        $value = str_replace('Â£', '', $value);
        $value = str_replace('â¬', '', $value);
        $value = str_replace('$', '', $value);
        $value = str_replace(',', '', $value);
        $value = str_replace('HK', '', $value);

        $value = trim($value);

        return $value;
    }

    public function insertPastAuctionSale()
    {
        $intSaleID = $this->intSaleID;

        $saleArray = $this->saleArray;

        $sale = New App\Model\Auction\Past\PastAuctionSale;

        // create slug

        $datetime = $saleArray['sale']['date']['datetime'];
        $exDatetime = explode(' ', $datetime);
        $date = $exDatetime[0];

        $this->slug = 'christie-auction-'.$date.'-'.$saleArray['sale']['id'];

        $sale->slug = $this->slug;

        $sale->image_path = $saleArray['sale']['image_path'];
        $sale->image_fit_path = 1;
        $sale->image_small_path = 1;
        $sale->image_medium_path = 1;
        $sale->image_large_path = 1;

        $sale->number = $intSaleID;
        $sale->ref_number = $saleArray['sale']['id'];
        $sale->total_lots = $saleArray['sale']['total_lots'];
        $sale->start_date = $saleArray['sale']['date']['datetime'];
        $sale->end_date = $saleArray['sale']['date']['datetime'];
        $sale->auction_house_id = $this->house->id;

        $locationName = $this->getLocation($saleArray['sale']['country']);

        $location = App\Model\AuctionLocation::where('name', $locationName)->first();

        $sale->country_id = $location->country_id;
        $sale->auction_location_id = $location->id;

        $sale->save();

        $this->sale = $sale;
    }

    public function importPastAuctionSaleDetails()
    {
        $saleArray = $this->saleArray;

        foreach($this->languages as $language) {

            $saleDetail = New App\Model\Auction\Past\PastAuctionSaleDetail;

            $saleDetail->title = $saleArray['sale'][$language]['title'];
            $saleDetail->location = $saleArray['sale']['country'];
            $saleDetail->lang = $language;
            $saleDetail->past_auction_sale_id = $this->sale->id;

            $saleDetail->save();

        }
    }

    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\Backend\Auction\Past\ChristieController');
    // app()->call([$controller, 'importLocation']);
    public function importLocation()
    {
        set_time_limit(60000);

        // get Christie's house id
        $house = App\AuctionHouse::where('slug', 'christies')->first();

        // get sale for import
        $sales = App\ChristieSalesChecking::where('year', '>', 1997)->where('json', '=', 1)->where('import', null)->get();

        $lang = array('trad', 'en', 'sim');

        $counter = 0;

        foreach($sales as $sale) {

            $counter ++;

//            if($counter < 9730) continue;

            $intSaleID = $sale->int_sale_id;

            $saleArray = $sale->getJSON();

//            dd($saleArray['sale']);

            $location = $saleArray['sale']['country'];
            $location = $this->getLocation($location); // fix location

            echo $location;

            exit;

        }

    }

    public function getLocation($location)
    {
        switch($location) {
            case 'South Kensington':
            case 'London,King Street':
            case 'London, King Street.':
            case 'London,South Kensington-King Street Offsite sale':
            case '25 St. Barnabas st, Pimlico':
            case 'London, King Street-South Kensington Offsite sale':
                $location = 'London, South Kensington';
                break;
            case '22nd Floor':
            case 'Hong Kong, HKCEC Grand Hall':
            case 'Convention Hall':
            case 'Alexandra House, Hong Kong':
            case 'Spink Hong Kong':
                $location = 'Hong Kong';
                break;
            case 'Jumeirah Emirates Towers Hotel':
                // Dubai
                $location = 'Dubai';
                break;
            case 'Milan, Palazzo Clerici':
            case 'Palazzo Clerici, Milano':
            case 'Milan,San Paolo Converso':
                $location = 'Milan';
                break;
            case 'The Peninsula Hotel Shanghai':
                $location = 'Shanghai';
                break;
            case 'Kunsthaus Zurich':
            case 'Zurich, Vortragssaal Kunsthaus':
            case 'Kunsthaus Zurich, Grosser Vortragssaal':
            case 'Miller\'s Studio':
                $location = 'Zurich';
                break;
            case 'Cowdray Park':
            case 'Edinburgh,The Assembly Rooms':
            case 'Llanasa, North Wales':
            case 'London,The Jack Barclay Showroom':
            case 'Gables Service Station':
            case '\'Ashurst, Kent\'':
            case 'Woburn Abbey':
            case 'Chirk Castle':
            case 'Kasteel van \'s-Gravenwezel, Antwerp':
            case 'Spink London':
            case 'Lloyd\'s Building':
            case 'London,The Institute of Chartered Accountants':
            case 'Oxford,The Manor House at Clifton Hampden':
                $location = 'London';
                break;
            case 'Beverly Hills':
            case 'Monterey, Jet Center':
            case 'The Pebble Beach Equestrian Center':
            case 'Ford Product Development Center':
                $location = 'Los Angeles';
                break;
            case 'Tel Aviv':
            case 'Tel Aviv, Hilton Hotel':
                $location = 'Tel Aviv';
                break;
            case 'Madrid Westin Palace':
                $location = 'Madrid';
                break;
            case 'Christie\'s Redstone':
            case 'Christie\'s Special Exhibition Gallery':
            case 'The Atwater Estate, 66 Seafield Lane':
            case 'Greenwich Concours':
            case 'Rockefeller Center':
            case 'New York, East':
            case 'Spink America':
            case 'New York, Park Avenue':
                $location = 'New York';
                break;
            case 'St. Moritz, Palace Hotel':
            case 'Hotel Richemond, Geneva':
            case 'Hotel Richemond':
                $location = 'Geneva';
                break;
            case 'Le Circuit des 24 Heures, Le Mans':
            case 'Paris / Porte de Versailles - France':
                $location = 'Paris';
                break;
            case 'Rome, Palazzo Massimo Lancellotti':
            case 'Rome,':
            case 'Geneva,ProprietÃ  Galletto':
                $location = 'Rome';
                break;
            case 'Meridiaan, 55 Gouda':
            case 'Amsterdam,Kunstfabriek':
            case 'Rotterdam,Kunsthal':
                $location = 'Amsterdam';
                break;
            case 'Edgecliff  NSW':
            case 'Paddington Town Hall Sydney':
            case 'Tarrytown,Lyndhurst Property':
            case 'Sydney,Museum of Contemporary Art':
            case 'Spink Australia':
                $location = 'Sydney';
                break;
            case '43-49 Elizabeth Street':
                $location = 'Melbourne';
                break;
            case 'Spink Singapore':
                $location = 'Singapore';
                break;
        }

        $validLocation = array('Amsterdam','Beaune','Belgium','Dubai','Geneva','Germany','Glasgow','Hong Kong','London','London, South Kensington','Los Angeles','Madrid','Mallorca','Melbourne','Milan','Monaco','Mumbai','New York','Online','Paris','Rome','Shanghai','Singapore','Sydney','Taipei','Tel Aviv','Zurich',);

        if(!in_array($location, $validLocation)) {
            echo 'Internal Sale ID: '.$intSaleID."\n";
            echo 'not found: '.$location."\n";
            exit;
        }

        return $location;
    }

    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\Backend\Auction\Past\ChristieController');
    // app()->call([$controller, 'getJSON']);
    public function getJSON()
    {

        $sales = App\ChristieSalesChecking::where('year', '>', 1997)->where('json', '=', 1)->get();

        echo count($sales);

        $counter = 0;

        foreach($sales as $sale) {

            $counter ++;

            $intSaleID = $sale->int_sale_id;

            echo "Getting count ".$counter.". JSON: ".$intSaleID."\n";

            $json = $sale->getJSON(false);

            Storage::disk('local')->put('spider/christie/past/json/'.$intSaleID.'.json', $json);

        }

    }

}
