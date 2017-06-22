<?php

namespace App\Http\Controllers\Backend\Crawler;

use App\Http\Controllers\Controller;
use App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

// Run in tinker
// php artisan tinker
// $controller = app()->make('App\Http\Controllers\Backend\Crawler\ChristieController');
// app()->call([$controller, 'downloadImages'], ['intSaleID' => 26906]);

class ChristieController extends Controller
{
    public function index()
    {
        $locale = App::getLocale();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'crawler', 'christie.index'),
        );

        return view('backend.auctions.crawler.christie.index', $data);
    }

    public function crawler(Request $request)
    {
        $intSaleID = trim($request->int_sale_id);

        $this->getSaleByIntSaleID($intSaleID);

        return redirect()->route('backend.auction.christie.capture');
    }

    public function crawlerRemove($intSaleID)
    {

        $intSaleID = trim($intSaleID);

        $path = 'spider/christie/sale/'.$intSaleID;

        Storage::disk('local')->delete($path.'/'.$intSaleID.'.json');
        Storage::disk('local')->deleteDirectory($path);

        return redirect()->route('backend.auction.christie.capture');

    }

    public function capture()
    {
        $locale = App::getLocale();

//        echo base_path();

        $sales = File::directories(base_path().'/storage/app/spider/christie/sale');

        $salesArray = array();

        foreach($sales as $sale) {
            $sale = str_replace(base_path().'/storage/app/', '', $sale);
            $sale = str_replace('\\', '/', $sale);

            $exSale = explode('/', $sale);
            $intSaleID = $exSale[count($exSale) - 1];
            $fileName = $intSaleID.'.json';

            $json = Storage::disk('local')->get($sale.'/'.$fileName);

            $salesArray[$intSaleID] = json_decode($json, true);
        }

        //dd($salesArray);

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'christie.capture'),
            'salesArray' => $salesArray,
        );

        return view('backend.auctions.crawler.christie.capture', $data);
    }

    public function captureItemList($intSaleID)
    {
        $intSaleID = trim($intSaleID);

        $locale = App::getLocale();

        $path = 'spider/christie/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

//        dd($saleArray);

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'christie.capture'),
            'saleArray' => $saleArray,
            'intSaleID' => $intSaleID,
        );

//        dd($saleArray);

        return view('backend.auctions.crawler.christie.captureItemList', $data);
    }

    public function getSaleByIntSaleID($intSaleID)
    {
        set_time_limit(6000);

        echo "<p>";
        echo 'Spider '.$intSaleID.' start';
        echo "<br>";

        $content = $this->getContent($intSaleID); // get content from christie

        $saleArray = $this->makeSaleInfo($intSaleID, $content, true);

        if ($saleArray === false) {
            return redirect('backend.auction.christie.index')->with('warning', 'Sale not exist!');
        }

        $saleJSON = json_encode($saleArray);

        $storePath = 'spider/christie/sale/' . $intSaleID . '/';

        Storage::disk('local')->put($storePath . $intSaleID . '.json', $saleJSON);

        echo 'Spider '.$intSaleID.' end';
        echo "<br>";

        return $saleArray;

    }

    private function getContent($intSaleID)
    {
        $url = 'http://www.christies.com/lotfinder/print_sale.aspx?saleid='.$intSaleID.'&lid=1';

        echo "<br>";
        echo "Getting content from: ".$url;
        echo "<br>";

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $result=curl_exec($cSession);

        return $result;
    }

    private function makeSaleInfo($saleNumber, $content, $getLang = true)
    {
        $sale = array();

        // create new DOMDocument
        // $document = new \DOMDocument('1.0', 'UTF-8');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($content);

        // Start: get sale info
        $saleInfo = $dom->getElementById('sale_info');

        //echo "-- Sale Info --\n";
        //echo $saleInfo->ownerDocument->saveHTML($saleInfo);
        //echo "-- Sale Info --\n";

        $saleInfo_span = $saleInfo->getElementsByTagName('span');

        // get Sale ID and Location
        $sale_id_location = $saleInfo_span->item(0)->textContent;
        $explode_sale_id_location = explode('|', $sale_id_location);

        $sale['sale']['id'] = trim(str_replace('Sale', '', $explode_sale_id_location[0]));

        if($sale['sale']['id'] == '') return false;

        $sale['sale']['country'] = trim($explode_sale_id_location[1]);

        // get Sale Title
        $sale['sale']['en']['title'] = trim($saleInfo_span->item(1)->textContent);

        // get date
        $sale_date_timestamp = strtotime(trim($saleInfo_span->item(2)->textContent));

        $year = date('Y', $sale_date_timestamp);

        $sale['sale']['date']['datetime'] = date('Y-m-d H:i:s', $sale_date_timestamp);
        $sale['sale']['date']['timestamp'] = $sale_date_timestamp;

        // spider for time

        // get exhibition time from en template
        $saleLandingEN = $this->GetSaleLanding($saleNumber, 'en');

        $saleImagePathBlock = $saleLandingEN->getElementById('MainSaleImage');
        $saleImagePath = $saleImagePathBlock->getElementsByTagName('img');
        $saleImagePath = $saleImagePath[0];
        $saleImagePath = $saleImagePath->getAttribute('src');
        $sale['sale']['image_path'] = $saleImagePath;

        $saleLandingEN_sale_info = $saleLandingEN->getElementById('SaleInformation');

        if ($saleLandingEN_sale_info != null) {
            //    echo $saleLandingEN_sale_info->ownerDocument->saveHTML($saleLandingEN_sale_info);

            $saleLandingEN_ul = $saleLandingEN_sale_info->getElementsByTagName('ul');
            $saleLandingEN_auction_info = $saleLandingEN_ul[1]->getElementsByTagName('p');

            foreach ($saleLandingEN_auction_info as $skey => $auction_info) {
                if ($skey == 0) {
                    $sale['sale']['en']['location'] = $auction_info->textContent;
                    continue;
                }

                //        $auction_info->textContent;
                $ex_auction_info = explode(',', $auction_info->textContent);

                $auction_date_timestamp = strtotime(trim($ex_auction_info[0]) . ' ' . $year);
                $auction_date_datetime = date('Y-m-d', $auction_date_timestamp);

                $ex_auction_time_lots = explode(' (', $ex_auction_info[1]);
                $auction_time = trim($ex_auction_time_lots[0]);
                $auction_lots = str_replace(')', '', $ex_auction_time_lots[1]);
                $auction_lots = str_replace('Lots', '', $auction_lots);

                $auction_datetime = $auction_date_datetime . ' ' . $auction_time; //2017-05-12 10am

                $auction_timestamp = strtotime($auction_datetime);

                $sale['sale']['time'][$skey]['date_datetime'] = date('Y-m-d H:i:s', $auction_timestamp);
                $sale['sale']['time'][$skey]['date_timestamp'] = $auction_timestamp;
                $sale['sale']['time'][$skey]['lots'] = trim($auction_lots);
            }
        }

        if($getLang) {
            // Get sale trad-chinese title
            $saleLandingTrad = $this->GetSaleLanding($saleNumber, 'trad');
            $sale['sale']['trad'] = $this->GetSaleInfoByLang($saleLandingTrad, 'trad');

            // Get sale sim-chinese title
            $saleLandingSim = $this->GetSaleLanding($saleNumber, 'sim');
            $sale['sale']['sim'] = $this->GetSaleInfoByLang($saleLandingSim, 'sim');
        }

        // Start: get Viewing Times & Location - viewing_times
        // Sale viewing time, location
        $viewingInfo = $dom->getElementById('viewing_times');
        if (count($viewingInfo) > 0) {

            //echo "-- Viewing Times & Location --\n";
            //echo $viewingInfo->ownerDocument->saveHTML($viewingInfo);
            //echo "-- Viewing Times & Location --\n";

            // get viewing location
            $viewingInfo_span = $viewingInfo->getElementsByTagName('span');
            $sale['viewing']['location'] = trim($viewingInfo_span->item(1)->textContent);

            // get viewing time
            $viewing_time_array = array();

            $viewingInfo_th = $viewingInfo->getElementsByTagName('th');
            foreach ($viewingInfo_th as $key => $date) {
                $viewing_timestamp = strtotime(trim($date->textContent));
                $viewing_time_array[$key]['date_datetime'] = date('Y-m-d', $viewing_timestamp);;
                $viewing_time_array[$key]['date_timestamp'] = $viewing_timestamp;
            }

            $viewingInfo_td = $viewingInfo->getElementsByTagName('td');
            foreach ($viewingInfo_td as $key => $time) {

                $ex_time = explode('-', trim($time->textContent));

                $start_time = $viewing_time_array[$key]['date_datetime'] . ' ' . trim($ex_time[0]); // 2017-05-12 10am

                $end_time = $viewing_time_array[$key]['date_datetime'] . ' ' . trim($ex_time[1]);

                $start_timestamp = strtotime($start_time);
                $start_datetime = date('Y-m-d H:i:s', $start_timestamp);
                $end_timestamp = strtotime($end_time);
                $end_datetime = date('Y-m-d H:i:s', $end_timestamp);

                $viewing_time_array[$key]['start_datetime'] = $start_datetime;
                $viewing_time_array[$key]['start_timestamp'] = $start_timestamp;
                $viewing_time_array[$key]['end_datetime'] = $end_datetime;
                $viewing_time_array[$key]['end_timestamp'] = $end_timestamp;

            }

            $sale['viewing']['time'] = $viewing_time_array;
        }

        // get total lots for sale lots_for_sale
        // total lots
        $rawLFS = trim($dom->getElementById('lots_for_sale')->textContent);
        $exLFS = explode(' ', $rawLFS);
        $total_lots = trim($exLFS[0]);

        $sale['sale']['total_lots'] = $total_lots;

        // get each Lot Info
        $lotListInfo = $dom->getElementById('lot-list');

        //echo "-- Lots Info --\n";
        //echo $lotListInfo->ownerDocument->saveHTML($lotListInfo);
        //echo "-- Lots Info --\n";

        $lots = $lotListInfo->getElementsByTagName('tr');

        $lots_array = array();

        foreach ($lots as $key => $lot) {

            $lot_tds = $lot->getElementsByTagName('td');

            $raw_lot_image = trim($lot_tds[0]->ownerDocument->saveHTML($lot_tds[0]));

            //     echo $raw_lot_image;

            $lot_image = substr($raw_lot_image, 32, -8);

            $lots_array['image_path'] = $lot_image;

            // lot number description maker medium-dimensions
            $lot_info = $lot_tds[1]->ownerDocument->saveHTML($lot_tds[1]);

            //     echo $lot_info;

            $lot_info_span = $lot_tds[1]->getElementsByTagName('span');

            //     $lot_number = $lot_info_span[0];
            $lots_array['number'] = trim(str_replace('Lot', '', $lot_info_span[0]->textContent));

            $lots_array['description'] = trim($lot_info_span[1]->textContent);
            $lots_array['maker'] = trim($lot_info_span[2]->textContent);
            $lots_array['medium-dimensions'] = trim($lot_info_span[3]->textContent);

            if($getLang) {
                $localeENResult = $this->getLotLocale($saleNumber, 'en', $lots_array['number']);
                $lots_array['en'] = $localeENResult;
                $localeTradResult = $this->getLotLocale($saleNumber, 'trad', $lots_array['number']);
                $lots_array['trad'] = $localeTradResult;
                $localeSimResult = $this->getLotLocale($saleNumber, 'sim', $lots_array['number']);
                $lots_array['sim'] = $localeSimResult;
            }

            //     echo $lot_tds[2]->ownerDocument->saveHTML($lot_tds[2]);

            $lot_price = $lot_tds[2]->getElementsByTagName('span');

            $lots_array['estimate'] = $lot_price[1]->textContent;
            if($lot_price[4] != null) {
                $lots_array['price'] = $lot_price[4]->textContent;
            } else {
                $lots_array['price'] = null;
            }

            $sale['lots'][] = $lots_array;

            //     break;

        }

        return $sale;
    }

    private function GetSaleLanding($saleNumber, $locale)
    {
        $localeArray = array('en' => '',  'trad' => 'zh/', 'sim' => 'zh-CN/');

        $url = 'http://www.christies.com/'.$localeArray[$locale].'salelanding/index.aspx?intsaleid=' . $saleNumber;

        echo "<br>".$url."<br>";

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $result = curl_exec($cSession);
        curl_close($cSession);

//        echo $result;


        $dom = new \DOMDocument();
        $dom->loadHTML($result);

        return $dom;
    }

    private function GetSaleInfoByLang($dom)
    {
        $sale = array();
        $titleBlock = $dom->getElementById('main_center_0_ctl00_lblSaleTitle');
        $sale['title'] = $titleBlock->textContent;

        /*$saleInfo = $dom->getElementById('SaleInformation');

        $locationBlock = $saleInfo->getElementsByTagName('strong');
        $sale['location'] = $locationBlock[0]->textContent;*/

        return $sale;
    }

    private function getLotLocale($saleNumber, $locale, $lotNumber)
    {
        $localeArr = array('trad'=>'zh/', 'sim'=>'zh-CN/', 'en' => '');
        $url = 'http://www.christies.com/'.$localeArr[$locale].'lotfinder/lot_details.aspx?hdnsaleid='.$saleNumber.'&ln='.str_replace(' ', '', $lotNumber).'&intsaleid='.$saleNumber;
        echo "Getting Lot Locale From: ".$url."<br>\n";

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $spider_result = curl_exec($cSession);
        curl_close($cSession);

//        echo $spider_result;
//        exit;

        // get exhibition time
        $spider = new \DOMDocument();
        libxml_use_internal_errors(true);
        $spider->loadHTML($spider_result);

        $contentArray = array();

        // main_center_0_lblLotPrimaryTitle
        $title = $spider->getElementByID('main_center_0_lblLotPrimaryTitle');

        if($title == null) return false; // the lot withdraw

        $contentArray['title'] = $title->textContent;

        // main_center_0_lblLotSecondaryTitle
        $stitle = $spider->getElementByID('main_center_0_lblLotSecondaryTitle');
        $contentArray['secondary_title'] = $stitle->textContent;

        // main_center_0_lblPriceEstimatedPrimary
        $estimate = $spider->getElementByID('main_center_0_lblPriceEstimatedPrimary');
        $contentArray['estimate'] = $estimate->textContent;

        // main_center_0_lblLotDescription
        $description = $spider->getElementByID('main_center_0_lblLotDescription');
        $contentArray['description'] = $description->ownerDocument->saveHTML($description);

        // main_center_0_lblLotProvenance
        $provenance = $spider->getElementByID('main_center_0_lblLotProvenance');
        if($provenance == null) {
            $provenance = null;
        } else {
            $provenance = $provenance->ownerDocument->saveHTML($provenance);
        }
        $contentArray['provenance'] = $provenance;

        // main_center_0_lblPriceRealizedPrimary
        $sold_value = $spider->getElementByID('main_center_0_lblPriceRealizedPrimary');

        if($sold_value == null) {
            $sold_value = null;
        } else {
            $sold_value = trim($sold_value->textContent);
        }

        $contentArray['sold_value'] = $sold_value;

        return $contentArray;

    }

    public function downloadImages($intSaleID)
    {
        set_time_limit(6000);

        $intSaleID = trim($intSaleID);

        $locale = App::getLocale();

        $path = 'spider/christie/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

//        dd($saleArray);

        $storePath = 'spider/christie/sale/'.$intSaleID.'/';

        $downloadedImages = array();

        foreach($saleArray['lots'] as $index => $lot) {
            $link = str_replace('s.jpg', 'a.jpg', $lot['image_path']);

            if(basename($link) == 'no-image-75.jpg') {

                $link = 'images/thevalue-no-image.jpeg';
                $image_small_path = $link;
                $image_medium_path = $link;
                $image_large_path = $link;
                $image_fit_path = $link;

            } else {

                // search for existing image
                $searchArray = array_search($link, $downloadedImages);
                if ($searchArray) {

//                $storagePath = base_path().'/public/images/auctions/christie/sale/'.$saleArray['sale']['id'].'/';
                    $spiderStoragePath = 'images/auctions/christie/sale/' . $saleArray['sale']['id'] . '/';

                    $image_small_path = $spiderStoragePath . $searchArray . '-150.jpg';
                    $image_medium_path = $spiderStoragePath . $searchArray . '-500.jpg';
                    $image_large_path = $spiderStoragePath . $searchArray . '-1140.jpg';
                    $image_fit_path = $spiderStoragePath . $searchArray . '-fit-250.jpg';

                } else {
                    $downloadedImages[$lot['number']] = $link;

                    echo $storePath . $lot['number'] . '.jpg<br>';

//                $publicStoragePath = base_path().'/public/images/auctions/christie/sale/'.$saleArray['sale']['id'].'/';
                    $spiderStoragePath = 'images/auctions/christie/sale/' . $saleArray['sale']['id'] . '/';

                    $existImage = array();

                    $image_small_path = $spiderStoragePath . $lot['number'] . '-150.jpg';
                    $image_medium_path = $spiderStoragePath . $lot['number'] . '-500.jpg';
                    $image_large_path = $spiderStoragePath . $lot['number'] . '-1140.jpg';
                    $image_fit_path = $spiderStoragePath . $lot['number'] . '-fit-250.jpg';

                    $existImage[] = $image_small_path;
                    $existImage[] = $image_medium_path;
                    $existImage[] = $image_large_path;
                    $existImage[] = $image_fit_path;

                    $imageExists = false;

                    foreach ($existImage as $image) {
                        echo $image;
                        echo '<br>';
                        if (file_exists($image)) {
                            $imageExists = true;
                            echo 'file exist';
                            echo '<br>';
                            continue;
                        }
                    }

                    if ($imageExists) {
                        continue;
                    }

                    $image_path = $this->GetImageFromUrl($storePath, $link, $lot['number']);
                    $resize = $this->imgResize($intSaleID, $lot['number'], $saleArray['sale']['id']);

                }
            }

            $saleArray['lots'][$index]['saved_image_path'] = array(
                'image_small_path' => $image_small_path,
                'image_medium_path' => $image_medium_path,
                'image_large_path' => $image_large_path,
                'image_fit_path' => $image_fit_path
            );

            //test 10 photos
            // if($index > 10) break;

        }

        Storage::disk('local')->put($path, json_encode($saleArray));

        return redirect('tvadmin/auction/crawler/christie/capture/'.$intSaleID.'/itemlist');

    }

    public function uploadS3($intSaleID)
    {
        set_time_limit(6000);

        $intSaleID = trim($intSaleID);

        $locale = App::getLocale();

        $path = 'spider/christie/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

//        echo $saleArray['sale']['id'];
//        exit;

        $saleID = $saleArray['db']['sale']['main']['id'];

        $sale = App\AuctionSale::find($saleID);

//        dd($sale);

        $items = $sale->items;

        $uploadedImages = array();

        foreach($items as $item) {

            if(in_array($item->image_fit_path, $uploadedImages)) {
                echo $item->image_fit_path;
                echo '<br>';
                echo 'duplicated';
                echo '<br>';
                continue;
            }

            if($item->image_fit_path == 'thevalue-no-image.jpeg') {
                continue;
            }

            $uploadedImages[] = $item->image_fit_path;

            $baseDirectory = base_path().'/public';

            $this->pushS3($baseDirectory, $item->image_fit_path);
            $this->pushS3($baseDirectory, $item->image_large_path);
            $this->pushS3($baseDirectory, $item->image_medium_path);
            $this->pushS3($baseDirectory, $item->image_small_path);

        }

//        return redirect()->route('backend.auction.itemList', ['saleID' => $saleID]);

    }

    private function GetImageFromUrl($storePath, $link, $image_name)
    {
        $image_path = $storePath.$image_name.'.jpg';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $image=curl_exec($ch);

        curl_close($ch);

        Storage::disk('local')->put($image_path, $image);

        return $image_path;
    }

    public function imgResize($intSaleID, $lotNumber, $saleNumber)
    {
        $file = 'spider/christie/sale/'.$intSaleID.'/'.$lotNumber.'.jpg';

        echo $file;
        echo "\n";
        echo '<br>';

        $exFile = explode('/', $file);
        $christieIntSaleID = $exFile[3];

        $storePath = 'images/auctions/christie/sale/' . $saleNumber . '/';

        if(!file_exists(base_path().'/public/'.$storePath)) mkdir(base_path().'/public/'.$storePath);

        $image_large_path = $this->resizeImage($file, $storePath, 1140);
        $image_medium_path = $this->resizeImage($file, $storePath, 500);
        $image_small_path = $this->resizeImage($file, $storePath, 150);
        $image_fit_path = $this->createFitImage($file, $storePath, 250);

        $image_path = array(
            'large' => $image_large_path,
            'medium' => $image_medium_path,
            'small' => $image_small_path,
            'fit' => $image_fit_path
        );

        return $image_path;
    }

    private function resizeImage($file, $resizePath, $width)
    {

        echo $file;
        echo '<br>';

        echo base_path();
        echo '<br>';

        $img = Image::make(base_path().'/'.'storage/app/'.$file);

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $newPath = $resizePath.str_replace('.'.$fileExtension, '', basename($file)).'-'.$width.'.'.$fileExtension;

        echo $newPath;
        echo "\n";

        $img->widen($width, function ($constraint) {
            $constraint->upsize();
        })->save(base_path().'/public/'.$newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $newPath;
    }

    private function createFitImage($file, $resizePath, $width)
    {
        $img = Image::make(base_path().'/'.'storage/app/'.$file);

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $newPath = $resizePath.str_replace('.'.$fileExtension, '', basename($file)).'-fit-'.$width.'.'.$fileExtension;

        echo $newPath;
        echo "\n";

        $img->fit($width)->save(base_path().'/public/'.$newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $newPath;
    }

    public function importSale(Request $request, $intSaleID)
    {
        $intSaleID = trim($intSaleID);
        $auctionSeriesID = trim($request->auction_series_id);

        $series = App\AuctionSeries::find($auctionSeriesID);
        $seriesDetails = $series->details();

        $house = $series->house;

        if(count($series) == 0) exit;

        $path = 'spider/christie/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        // dd($saleArray);

        // download sale cover image
        $storePath = 'spider/christie/sale/'.$intSaleID.'/';
        $link = $saleArray['sale']['image_path'];
        $saleNumber = $saleArray['sale']['id'];

        $image_path = $this->GetImageFromUrl($storePath, $link, 'christies-sale-'.$saleNumber);
        $resize = $this->imgResize($intSaleID, 'christies-sale-'.$saleNumber, $saleNumber);

        // insert auction_sales
        // slug, source_image_path, image_path, number, total_lots, start_date, end_date, auction_series_id
        $sale = New App\AuctionSale;

        $slug = str_replace(' ', '-', $saleArray['sale']['en']['title']);
        $slug = str_replace(' & ', '-and-', $slug);
        $slug = str_replace('&', '-and-', $slug);

        $sale->slug = $slug;
        $sale->source_image_path = $saleArray['sale']['image_path'];
        $sale->image_path = $resize['medium'];
        $sale->number = $saleArray['sale']['id'];
        $sale->total_lots = count($saleArray['lots']);
        $sale->start_date = $saleArray['sale']['date']['datetime'];
        $sale->end_date = $saleArray['sale']['date']['datetime'];
        $sale->auction_series_id = $auctionSeriesID;

        $sale->save();

        // echo "sale id: ".$sale->id;
        // echo '<br>';
        $saleID = $sale->id;

        // insert auction_sale_details
        // type, title, country, location, lang, auction_sale_id
        $supported_languages = config('app.supported_languages');
        // sale detail type sale
        foreach($supported_languages as $lang) {
            $saleDetail = New App\AuctionSaleDetail;
            $saleDetail->type = 'sale';
            $saleDetail->title = $saleArray['sale'][$lang]['title'];
            $houseDetail = $house->getDetailByLang($lang);
            $saleDetail->country = $houseDetail->country;
            $saleDetail->location = $saleArray['sale']['country'];
            $saleDetail->lang = $lang;
            $saleDetail->auction_sale_id = $saleID;
            $saleDetail->save();
        }

        // sale detail type viewing
        foreach($supported_languages as $lang) {
            $saleDetail = New App\AuctionSaleDetail;
            $saleDetail->type = 'viewing';
            $saleDetail->title = $saleArray['sale'][$lang]['title'];
            $houseDetail = $house->getDetailByLang($lang);
            $saleDetail->country = $houseDetail->country;
            $saleDetail->location = $saleArray['viewing']['location'];
            $saleDetail->lang = $lang;
            $saleDetail->auction_sale_id = $saleID;
            $saleDetail->save();
        }

        // insert auction_sale_times
        // type, lots, start_date, end_date, auction_sale_id
        // sale date
        $saleTime = New App\AuctionSaleTime;
        $saleTime->type = 'sale';
        $saleTime->start_date = $saleArray['sale']['date']['datetime'];
        $saleTime->end_date = $saleArray['sale']['date']['datetime'];
        $saleTime->auction_sale_id = $saleID;

        $saleTime->save();

        foreach($saleArray['viewing']['time'] as $viewTime) {
            $viewingTime = New App\AuctionSaleTime;
            $viewingTime->type = 'viewing';
            $viewingTime->start_date = $viewTime['start_datetime'];
            $viewingTime->end_date = $viewTime['end_datetime'];
            $viewingTime->auction_sale_id = $saleID;
            $viewingTime->save();
        }

        // insert auction_items
        // slug, dimension, number,
        // source_image_path, image_path, image_fit_path, image_large_path, image_medium_path, image_small_path,
        // currency_code, estimate_value_initial, estimate_value_end, sold_value, sorting, status, auction_sale_id

        $image_path = 'images/auctions/christie/sale/'.$saleArray['sale']['id'].'/';

        $counter = 10;

        foreach($saleArray['lots'] as $lot) {
            // filter dimension
            $exMediumDimensions = explode("\r\n", $lot['medium-dimensions']);
            $dimension = null;
            foreach($exMediumDimensions as $dItem) {
                if (stripos($dItem, "cm.") !== false) {
                    $dimension = str_replace('Â', '', trim($dItem));
                    $dimension = str_replace('Ã¨', 'è', $dimension);
                    //echo $dimension."\n";
                    //echo '<br>';
                    break;
                }
            }

            $item = New App\AuctionItem;
            $item->slug = $slug.'-'.$lot['number'];
            $item->dimension = $dimension;
            $item->number = $lot['number'];
            $item->source_image_path = $lot['image_path'];
            $item->image_path = $lot['image_path'];
            $item->image_fit_path = $lot['saved_image_path']['image_fit_path'];
            $item->image_large_path = $lot['saved_image_path']['image_large_path'];
            $item->image_medium_path = $lot['saved_image_path']['image_medium_path'];
            $item->image_small_path = $lot['saved_image_path']['image_small_path'];
            $item->currency_code = $house->currency_code;

            $estimate = $lot['estimate'];

            if($estimate == 'Estimate on request' || $estimate == '') {
                $currencyCode = null;
                $estimate_value_initial = null;
                $estimate_value_end = null;
            } else {
                $exEstimate = explode('-', $estimate);
                $estimate_value_initial = str_replace('Â£', '', trim($exEstimate[0]));
                $estimate_value_initial = str_replace('â¬', '', $estimate_value_initial);
                $estimate_value_initial = str_replace('$', '', $estimate_value_initial);
                $estimate_value_initial = str_replace(',', '', $estimate_value_initial);
                $estimate_value_end = str_replace('Â£', '', trim($exEstimate[1]));
                $estimate_value_end = str_replace('â¬', '', $estimate_value_end);
                $estimate_value_end = str_replace('$', '', $estimate_value_end);
                $estimate_value_end = str_Replace(',', '', $estimate_value_end);
            }

            $item->estimate_value_initial = $estimate_value_initial;
            $item->estimate_value_end = $estimate_value_end;
            $item->sorting = $counter;
            $item->status = 'pending';
            $item->auction_sale_id = $saleID;

            $item->save();

            $itemID = $item->id;
            echo '<br>';
            echo $itemID.'<br>';

            // insert auction_item_details
            // title, description, maker, misc, provenance, post_lot_text, lang, auction_item_id
            foreach($supported_languages as $lang) {
                $itemDetail = New App\AuctionItemDetail;
                $itemDetail->title = $lot[$lang]['title'];
                $itemDetail->description = $lot[$lang]['description'];
                $itemDetail->maker = $lot['maker'];
                $itemDetail->misc = $lot[$lang]['secondary_title'];
                $itemDetail->lang = $lang;
                $itemDetail->auction_item_id = $itemID;
                $itemDetail->save();
            }

            $counter += 10;

        }


        $saleArray['db']['series'] = array(
            'main' => $series,
            'detail' => $series->getDetailByLang('trad')
        );

        $saleArray['db']['sale'] = array(
            'main' => $sale,
            'detail' => $sale->getDetailByLang('trad')
        );

        $path = 'spider/christie/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        Storage::disk('local')->put($path, json_encode($saleArray));

        // backend.auction.itemList
        return redirect('tvadmin/auction/crawler/christie/capture/'.$intSaleID.'/itemlist');

    }

    public function pushS3($baseDirectory, $filePath)
    {
        $s3 = \Storage::disk('s3');
        $localPath = $baseDirectory.'/'.$filePath;

        echo $localPath;

        $image = fopen($localPath, 'r+');
        $s3->put('/'.$filePath, $image, 'public');
    }

    public function getRealizedPrice($intSaleID)
    {
        set_time_limit(6000);

        $path = 'spider/christie/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        $saleID =  $saleArray['db']['sale']['main']['id'];

        $sale = App\AuctionSale::find($saleID);

        $items = $sale->items;

        foreach($items as $item) {

            $content = $this->getLotLocale($intSaleID, 'en', $item->number);

            if(!$content) {
                $item->sold_value = null;
                $item->status = 'withdraw';
                $item->save();
                continue;
            }

            if($content['sold_value'] == null) {
                $item->sold_value = null;
                $item->status = 'bought in';
                $item->save();
                continue;
            }

            $item->sold_value = $this->convertValue($content['sold_value']);
            $item->status = 'sold';
            $item->save();

        }

        return redirect('tvadmin/auction/crawler/christie/capture/'.$intSaleID.'/itemlist');

    }

    public function getRealizedPrice2($intSaleID)
    {
        set_time_limit(6000);

        $saleArray = $this->getJSONContent($intSaleID);

        $saleID = $saleArray['db']['sale']['main']['id'];

        $content = $this->getContent($intSaleID); // get content from christie

        echo 'Spider '.$intSaleID.' start';
        echo "\n<br>";

        $saleArray = $this->makeSaleInfo($intSaleID, $content, false);

        $sale = App\AuctionSale::find($saleID);

        foreach($saleArray['lots'] as $lot) {
            $item = $sale->items()->where('number', $lot['number'])->first();
            if(count($item) > 0){
                if($lot['price'] != null) {
                    $item->sold_value = $this->convertValue2($lot['price']);
                    $item->status = 'sold';
                } else {
                    $item->sold_value = null;
                    $item->status = 'bought in';
                }
                $item->save();
            }
        }

        $items = $sale->items()->where('status', 'pending')->get();

        foreach($items as $item) {
            $item->status = 'withdraw';
            $item->save();
        }


        echo 'Spider '.$intSaleID.' end';
        echo "\n<br>";

        return redirect('tvadmin/auction/crawler/christie/capture/'.$intSaleID.'/itemlist');

    }

    private function convertValue($value)
    {
        $value = str_replace(',', '', $value);

        $exValue = explode(' ', $value);

        if(count($exValue) == 2) {
            $value = $exValue[1];
        }

        $value = trim($value);

        return $value;
    }

    private function convertValue2($value)
    {
        trim($value);

        $value = str_replace(',', '', $value);

        // $exValue = explode('$', $value);

        $value = str_replace('$', '', $value);
        $value = str_replace('Â£', '', $value);

//        Â£

        /*if(count($exValue) == 2) {
            $value = $exValue[1];
        }*/

        $value = trim($value);

        return $value;
    }

    private function getJSONContent($intSaleID)
    {
        $path = 'spider/christie/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        return $saleArray;
    }


}
