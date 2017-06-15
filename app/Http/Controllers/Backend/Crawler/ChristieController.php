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
        set_time_limit(600);

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

        dd($saleArray);

        $storePath = 'spider/christie/sale/'.$intSaleID.'/';

        foreach($saleArray['lots'] as $lot) {
            $link = str_replace('s.jpg', 'a.jpg', $lot['image_path']);

            echo $storePath.$lot['number'].'.jpg<br>';

            $publicStoragePath = base_path().'/public/images/auctions/christie/sale/'.$intSaleID.'/';

            $existImage = array();
            $existImage[] = $publicStoragePath.$lot['number'].'-150.jpg';
            $existImage[] = $publicStoragePath.$lot['number'].'-500.jpg';
            $existImage[] = $publicStoragePath.$lot['number'].'-1140.jpg';
            $existImage[] = $publicStoragePath.$lot['number'].'-fit-250.jpg';

            $imageExists = false;

            foreach($existImage as $image) {
                echo $image;
                echo '<br>';
                if(file_exists($image)) {
                    $imageExists = true;
                    echo 'file exist';
                    echo '<br>';
                    continue;
                }
            }

            if($imageExists) {
                continue;
            }

//            exit;

            $image_path = $this->GetImageFromUrl($storePath, $link, $lot['number']);

            $resize = $this->imgResize($intSaleID, $lot['number']);

//            exit;

        }

        return redirect('tvadmin/auction/crawler/christie/capture/'.$intSaleID.'/itemlist');

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

    public function imgResize($intSaleID, $lotNumber)
    {
        $file = 'spider/christie/sale/'.$intSaleID.'/'.$lotNumber.'.jpg';

        echo $file;
        echo "\n";
        echo '<br>';

        $exFile = explode('/', $file);
        $christieIntSaleID = $exFile[3];

        $storePath = 'images/auctions/christie/sale/' . $christieIntSaleID . '/';

        if(!file_exists(base_path().'/public/'.$storePath)) mkdir(base_path().'/public/'.$storePath);

        $image_large_path = $this->resizeImage($file, $storePath, 1140);
        $image_medium_path = $this->resizeImage($file, $storePath, 500);
        $image_small_path = $this->resizeImage($file, $storePath, 150);
        $image_fit_path = $this->createFitImage($file, $storePath, 250);
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

}
