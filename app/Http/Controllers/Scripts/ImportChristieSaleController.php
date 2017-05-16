<?php

namespace App\Http\Controllers\Scripts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App;

class ImportChristieSaleController extends Controller
{

    // Run in tinker
    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\Scripts\ImportChristieSaleController');
    // app()->call([$controller, 'index'], []);

    public function index()
    {
        set_time_limit(600);

        $christieSales = App\ChristieSale::where('status', 1)->orderBy('int_sale_id')->get();

        foreach($christieSales as $christieSale) { // get sale from DB

            $christieIntSaleID = $christieSale->int_sale_id;
            $content = $this->getContent($christieIntSaleID); // get content from christie

            $saleArray = $this->makeSaleInfo($christieIntSaleID, $content, true);

            if($sale === false) {
                continue;
            }

            dd($saleArray);

            $auction_series_id = 1;

            $importResult = $this->importAuctionSale($saleArray['sale'], $auction_series_id); // Import Auction Sale Info

            $auction_sale_id = $importResult->id;

            // Import Auction Sale Detail.


            break;

        }

        // trad item link
        // http://www.christies.com/zh/lotfinder/lot_details.aspx?hdnsaleid=26537&ln=2&intsaleid=26537&sid=f509af18-8331-4002-be74-6baf0332dfb5
        // http://www.christies.com/zh-CN/lotfinder/lot_details.aspx?hdnsaleid=26537&ln=2&intsaleid=26537&sid=f509af18-8331-4002-be74-6baf0332dfb5

    }

    private function importAuctionSale($sale, $auction_series_id)
    {

        // slug	number	total_lots	start_date	end_date	auction_series_id
        $slug = str_replace(' ', '-', trim(strtolower($sale['title'])));

        $sale = New App\AuctionSale;
        $sale->slug = $slug;
        $sale->number = $sale['id'];
        $sale->total_lots = $sale['total_lots'];
        $sale->start_date = $sale['date']['datetime'];
        $sale->auction_series_id = $auction_series_id;
        $sale->save();

        return $sale;

    }

    private function importAuctionSaleDetail($sale, $auction_sale_id)
    {

        // Import Sale Detail
        // id	type	title	locations	lang	auction_sale_id
        $saleDetail = New App\AuctionSaleDetail;
        $saleDetail->type = 'sale';
        $saleDetail->title = $sale['sale']['title'];
        $saleDetail->location = $sale['viewing']['location'];
        $saleDetail->lang = 'en';
        $saleDetail->auction_sale_id = $auction_sale_id;

    }

    private function getContent($sale_id) {

        $url = 'http://www.christies.com/lotfinder/print_sale.aspx?saleid='.$sale_id.'&lid=1';

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $result=curl_exec($cSession);

        return $result;

    }

    private function makeSaleInfo($saleNumber, $content, $getRaw)
    {
        $sale = array();

        // create new DOMDocument
        $document = new \DOMDocument('1.0', 'UTF-8');

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

        $sale['sale']['location'] = trim($explode_sale_id_location[1]);

        // get Sale Title
        $sale['sale']['title'] = trim($saleInfo_span->item(1)->textContent);

        // get date
        $sale_date_timestamp = strtotime(trim($saleInfo_span->item(2)->textContent));

        $year = date('Y', $sale_date_timestamp);

        $sale['sale']['date']['datetime'] = date('Y-m-d H:i:s', $sale_date_timestamp);
        $sale['sale']['date']['timestamp'] = $sale_date_timestamp;

        // spider for time


        $url = 'http://www.christies.com/salelanding/index.aspx?intsaleid=' . $saleNumber;
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $spider_result = curl_exec($cSession);
        curl_close($cSession);

        //    echo $result;

        // get exhibition time
        $spider = new \DOMDocument();
        $spider->loadHTML($spider_result);

        $saleImagePathBlock = $spider->getElementById('MainSaleImage');
        $saleImagePath = $saleImagePathBlock->getElementsByTagName('img');
        $saleImagePath = $saleImagePath[0];
        $saleImagePath = $saleImagePath->getAttribute('src');
        $sale['sale']['image_path'] = $saleImagePath;

        $spider_sale_info = $spider->getElementById('SaleInformation');

        if ($spider_sale_info != null) {
            //    echo $spider_sale_info->ownerDocument->saveHTML($spider_sale_info);

            $spider_ul = $spider_sale_info->getElementsByTagName('ul');
            $spider_auction_info = $spider_ul[1]->getElementsByTagName('p');

            foreach ($spider_auction_info as $skey => $auction_info) {
                if ($skey == 0) continue;

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

            $localeENResult = $this->getLotLocale($saleNumber, 'en', $lots_array['number']);
            $lots_array['en'] = $localeENResult;
            $localeTradResult = $this->getLotLocale($saleNumber, 'trad', $lots_array['number']);
            $lots_array['trad'] = $localeTradResult;
            $localeSimResult = $this->getLotLocale($saleNumber, 'sim', $lots_array['number']);
            $lots_array['sim'] = $localeSimResult;

            //     echo $lot_tds[2]->ownerDocument->saveHTML($lot_tds[2]);

            $lot_price = $lot_tds[2]->getElementsByTagName('span');

            $lots_array['estimate'] = $lot_price[1]->textContent;
            if($lot_price[4] != null) $lots_array['price'] = $lot_price[4]->textContent;

            $sale['lots'][] = $lots_array;

            //     break;

        }

        $storePath = 'spider/christie/sale/' . $saleNumber;
//
//        if (!file_exists($storePath)) {
//            mkdir($storePath);
//        }

        if($getRaw == false) {
            foreach ($sale['lots'] as $lkey => $lot) {

//                print_r($lot);
//
//                exit;

                echo "\n";
                echo 'Downloading Lot Image: ';

                $image = $lot['image_path'];
                echo $image;

                $ext = pathinfo($image, PATHINFO_EXTENSION);

                echo "\n";

                $rm_ext_image = str_replace('.' . $ext, '', $image);

                $file_name = substr($rm_ext_image, 0, -1);

                $image_path = $file_name . 'a.' . $ext;

                //        echo "store_path: ".$storePath;
                //        echo "\n";
                //
                //        echo "image_path: ".$image_path;
                //        echo "\n";

                $image_name = $saleNumber . '-' . $lot['number'];

                $get_image_result = $this->GetImageFromUrl($storePath, $image_path, $image_name);

                //        GetImageFromUrl('result/christie/sale/26805', 'http://www.christies.com/lotfinderimages/d60672/d6067288a.jpg');

                $sale['lots'][$lkey]['image_local_path'] = $get_image_result;

                //        echo $sale['lots'][$lkey]['image_local_path'];
                //        echo "\n";

                //        break;

            }
        }

        return $sale;
    }

    private function GetImageFromUrl($storePath, $link, $image_name)
    {
        $image_path = $storePath.'/'.$image_name.'.jpg';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result=curl_exec($ch);

        curl_close($ch);

        $savefile = fopen($image_path, 'w');
        fwrite($savefile, $result);
        fclose($savefile);

        if(filesize($image_path) == 150) {
            unlink($image_path);
            return false;
        }

        return $image_path;

    }

    private function getLotLocale($saleNumber, $locale, $lotNumber)
    {
        $localeArr = array('trad'=>'zh/', 'sim'=>'zh-CN/', 'en' => '');
        $url = 'http://www.christies.com/'.$localeArr[$locale].'lotfinder/lot_details.aspx?hdnsaleid='.$saleNumber.'&ln='.$lotNumber.'&intsaleid='.$saleNumber;
        $url = 'http://www.christies.com/'.$localeArr[$locale].'lotfinder/lot_details.aspx?hdnsaleid='.$saleNumber.'&ln='.$lotNumber.'&intsaleid='.$saleNumber;

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $spider_result = curl_exec($cSession);
        curl_close($cSession);

        //    echo $result;

        // get exhibition time
        $spider = new \DOMDocument();
        $spider->loadHTML($spider_result);

        $contentArray = array();

        // main_center_0_lblLotPrimaryTitle
        $title = $spider->getElementByID('main_center_0_lblLotPrimaryTitle');
        $contentArray['title'] = $title->textContent;

        // main_center_0_lblLotSecondaryTitle
        $stitle = $spider->getElementByID('main_center_0_lblLotSecondaryTitle');
        $contentArray['secondary_title'] = $stitle->textContent;

        // main_center_0_lblPriceEstimatedPrimary
        $estimate = $spider->getElementByID('main_center_0_lblPriceEstimatedPrimary');
        $contentArray['estimate'] = $estimate->textContent;

        // main_center_0_lblLotDescription
        $description = $spider->getElementByID('main_center_0_lblLotSecondaryTitle');
        $contentArray['description'] = $description->textContent;

        return $contentArray;

    }

}
