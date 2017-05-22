<?php

namespace App\Http\Controllers\Scripts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App;

class ImportChristieSaleController extends Controller
{

    // Run in tinker
    // php artisan tinker
    // $controller = app()->make('App\Http\Controllers\Scripts\ImportChristieSaleController');
    // app()->call([$controller, 'insertItemMissingDetail'], []);

    public function index()
    {
        set_time_limit(600);

        $christieSales = App\ChristieSale::where('get_json', 0)->where('status', 0)->orderBy('int_sale_id')->get();

        foreach($christieSales as $christieSale) {


            $christieIntSaleID = $christieSale->int_sale_id;
            $content = $this->getContent($christieIntSaleID); // get content from christie

            echo 'Spider '.$christieIntSaleID.' start';
            echo "\n";

            $saleArray = $this->makeSaleInfo($christieIntSaleID, $content, true);

            if ($saleArray === false) {
                exit;
            }

            $saleJSON = json_encode($saleArray);

            $storePath = 'spider/christie/sale/' . $christieIntSaleID . '/';

            Storage::disk('local')->put($storePath . $christieIntSaleID . '.json', $saleJSON);

            $christieSale->get_json = 1;

            $christieSale->save();

            // dd($saleArray);

            echo 'Spider '.$christieIntSaleID.' end';
            echo "\n";

        }

    }

    public function insertItemMissingDetail()
    {
        $sales = App\AuctionSale::all();
        echo '<pre>';

        foreach($sales as $sale) {
            $items = $sale->items;

            foreach($items as $item) {
                $itemDetails = $item->details()->where('provenance', null)->get();

                foreach($itemDetails as $itemDetail) {

                    $itemDetail->provenance = 'provenance';
                    $itemDetail->save();

                    $content = $this->getLotLocale($sale->christieSale->int_sale_id, $itemDetail->lang, $item->number);

                    $itemDetail->description = $content['description'];
                    $itemDetail->provenance = $content['provenance'];
//                    $itemDetail->post_lot_text = $content['postLotText'];
                    $itemDetail->save();

                }

            }
        }

    }

    public function insertSaleToDB()
    {
        $christieSales = App\ChristieSale::where('get_json', 1)->where('to_db', 0)->orderBy('int_sale_id')->get();

        foreach($christieSales as $christieSale) {

            $christieIntSaleID = $christieSale->int_sale_id;

            $storePath = 'spider/christie/sale/' . $christieIntSaleID . '/';

            $saleJson = Storage::disk('local')->get($storePath . $christieIntSaleID . '.json');

            $saleArray = json_decode($saleJson, true);

            $auction_series_id = 1;

            $insertAuctionSaleResult = $this->insertAuctionSale($saleArray['sale'], $auction_series_id); // Import Auction Sale Info

            $auction_sale_id = $insertAuctionSaleResult->id;

            // Import Auction Sale Detail
            $insertAuctionSaleDetailENResult = $this->insertAuctionSaleDetail($saleArray, $auction_sale_id, 'en');
            $insertAuctionSaleDetailTradResult = $this->insertAuctionSaleDetail($saleArray, $auction_sale_id, 'trad');
            $insertAuctionSaleDetailSimResult = $this->insertAuctionSaleDetail($saleArray, $auction_sale_id, 'sim');

            // Insert Auction Viewing
            $insertAuctionViewingDetailENResult = $this->insertAuctionViewingDetail($saleArray, $auction_sale_id, 'en');
            $insertAuctionViewingDetailTradResult = $this->insertAuctionViewingDetail($saleArray, $auction_sale_id, 'trad');
            $insertAuctionViewingDetailSimResult = $this->insertAuctionViewingDetail($saleArray, $auction_sale_id, 'sim');

            // Insert Auction Times
            $insertAuctionSaleTimeResult = $this->insertAuctionSaleTime($saleArray, $auction_sale_id);
            $insertAuctionViewingTimeResult = $this->insertAuctionViewingTime($saleArray, $auction_sale_id);

            // Insert Auction Items
            $insertAuctionItemResult = $this->insertAuctionItem($saleArray['lots'], $auction_sale_id);

            $christieSale->sale_number = $insertAuctionSaleResult->number;
            $christieSale->to_db = 1;
            $christieSale->save();

        }

    }

    private function insertAuctionSale($saleArray, $auction_series_id)
    {
        // slug	number	total_lots	start_date	end_date	auction_series_id
        $slug = str_replace(' ', '-', trim(strtolower($saleArray['en']['title'])));

        $sale = New App\AuctionSale;
        $sale->slug = $slug;
        $sale->source_image_path = $saleArray['image_path'];
        $sale->number = $saleArray['id'];
        $sale->total_lots = $saleArray['total_lots'];
        $sale->start_date = $saleArray['date']['datetime'];
        $sale->auction_series_id = $auction_series_id;
        $sale->save();

        return $sale;
    }

    private function insertAuctionSaleDetail($sale, $auction_sale_id, $locale)
    {
        // Import Sale Detail
        // id	type	title	locations	lang	auction_sale_id

        if($sale['sale']['country'] == 'Hong Kong' && $locale != 'en') {
            $country = '香港';
        } else {
            $country = $sale['sale']['country'];
        }

        $saleDetail = New App\AuctionSaleDetail;
        $saleDetail->type = 'sale';
        $saleDetail->title = $sale['sale'][$locale]['title'];
        $saleDetail->country = $country;
        $saleDetail->location = $sale['sale'][$locale]['location'];
        $saleDetail->lang = $locale;
        $saleDetail->auction_sale_id = $auction_sale_id;
        $saleDetail->save();

        return $saleDetail;
    }

    private function insertAuctionViewingDetail($sale, $auction_sale_id, $locale)
    {
        // Import Sale Detail
        // id	type	title	locations	lang	auction_sale_id
        $saleDetail = New App\AuctionSaleDetail;
        $saleDetail->type = 'viewing';
        $saleDetail->location = $sale['sale'][$locale]['location'];
        $saleDetail->lang = $locale;
        $saleDetail->auction_sale_id = $auction_sale_id;
        $saleDetail->save();

        return $saleDetail;
    }

    private function insertAuctionSaleTime($saleArray, $auction_sale_id)
    {
        foreach($saleArray['sale']['time'] as $timeArray) {

            $time = New App\AuctionSaleTime;
            $time->type = 'sale';
            $time->lots = $timeArray['lots'];
            $time->start_date = $timeArray['date_datetime'];
            $time->auction_sale_id = $auction_sale_id;
            $time->save();

        }
    }

    private function insertAuctionViewingTime($saleArray, $auction_sale_id)
    {
        foreach($saleArray['viewing']['time'] as $timeArray) {

            $time = New App\AuctionSaleTime;
            $time->type = 'Viewing';
//            $time->lots = $timeArray['lots'];
            $time->start_date = $timeArray['start_datetime'];
            $time->end_date = $timeArray['end_datetime'];
            $time->auction_sale_id = $auction_sale_id;
            $time->save();

        }
    }

    private function insertAuctionItem($lotsArray, $auction_sale_id)
    {
        foreach($lotsArray as $lotArray) {

            // auction_items	id	slug	number	image_path	image_large_path	image_medium_path	image_small_path	estimate	price	auction_sale_id
            $slug = str_replace(' ','-', $lotArray['en']['title']).'-'.str_replace(' ','-', $lotArray['en']['secondary_title']);

            $estimate = trim($lotArray['en']['estimate']);

            if($estimate == 'Estimate on request' || $estimate == '') {
                $currencyCode = null;
                $estimate_value_initial = null;
                $estimate_value_end = null;
            } else {
                $currencyCode = substr($estimate, 0, 3);
                $exEstimate = explode('-', $estimate);
                $estimate_value_initial = trim($exEstimate[0]);
                $estimate_value_end = trim($exEstimate[1]);
            }

            $item = New App\AuctionItem;
            $item->slug = str_replace(',', '', mb_substr($slug, 0, 100, 'utf-8'));
            $item->number = $lotArray['number'];
            $item->source_image_path = $lotArray['image_path'];
            $item->currency_code = $currencyCode;
            $item->estimate_value_initial = $estimate_value_initial;
            $item->estimate_value_end = $estimate_value_end;
            $item->sold_value = $lotArray['price'];
            $item->auction_sale_id = $auction_sale_id;
            $item->save();

            // id	description	maker	misc	lang	auction_item_id
            $itemID = $item->id;
            $this->insertAuctionItemDetail($lotArray, $itemID, 'en');
            $this->insertAuctionItemDetail($lotArray, $itemID, 'trad');
            $this->insertAuctionItemDetail($lotArray, $itemID, 'sim');

        }
    }

    private function insertAuctionItemDetail($lotArray, $itemID, $locale)
    {
        $itemDetail = New App\AuctionItemDetail;

        $itemDetail->title = str_replace(',', '', mb_substr($lotArray[$locale]['title'], 0, 200, 'utf-8'));
        $itemDetail->description = $lotArray[$locale]['description'];
        $itemDetail->maker = $lotArray['maker'];
        $itemDetail->misc = $lotArray[$locale]['secondary_title'];
        $itemDetail->lang = $locale;
        $itemDetail->auction_item_id = $itemID;

        $itemDetail->save();
    }

    private function getContent($sale_id)
    {
        $url = 'http://www.christies.com/lotfinder/print_sale.aspx?saleid='.$sale_id.'&lid=1';

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $result=curl_exec($cSession);

        return $result;
    }

    private function makeSaleInfo($saleNumber, $content)
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

        // Get sale trad-chinese title
        $saleLandingTrad = $this->GetSaleLanding($saleNumber, 'trad');
        $sale['sale']['trad'] = $this->GetSaleInfoByLang($saleLandingTrad, 'trad');

        // Get sale sim-chinese title
        $saleLandingSim = $this->GetSaleLanding($saleNumber, 'sim');
        $sale['sale']['sim'] = $this->GetSaleInfoByLang($saleLandingSim, 'sim');

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
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $result = curl_exec($cSession);
        curl_close($cSession);

        $dom = new \DOMDocument();
        $dom->loadHTML($result);

        return $dom;
    }

    private function GetSaleInfoByLang($dom)
    {
        $sale = array();
        $titleBlock = $dom->getElementById('main_center_0_ctl00_lblSaleTitle');
        $sale['title'] = $titleBlock->textContent;

        $saleInfo = $dom->getElementById('SaleInformation');
//        echo $saleInfo->textContent;
//        exit;
        $locationBlock = $saleInfo->getElementsByTagName('strong');
        $sale['location'] = $locationBlock[0]->textContent;

        return $sale;
    }

    public function getImage()
    {
//        set_time_limit(600);

        $christieSales = App\ChristieSale::where('get_image', 0)->where('to_db', 1)->orderBy('int_sale_id')->get();

        foreach($christieSales as $christieSale) {

            $christieIntSaleID = $christieSale->int_sale_id;
            $saleNumber = $christieSale->sale_number;

            $sale = App\AuctionSale::where('number', $saleNumber)->first();
            $saleID = $sale->id;

            $storePath = 'images/auctions/christie/sale/' . $christieIntSaleID . '/';

//            echo getcwd();

            if(!file_exists('public/'.$storePath)) mkdir('public/'.$storePath);

            $saleItems = App\AuctionItem::where('auction_sale_id', $saleID)->get();

//        dd($saleItems);

            foreach ($saleItems as $lkey => $item) {
                echo 'Downloading Lot Image: ';

                $image = $item['source_image_path'];
                echo $image;

                $ext = pathinfo($image, PATHINFO_EXTENSION);

                echo "\n";

                $rm_ext_image = str_replace('.' . $ext, '', $image);

                $file_name = substr($rm_ext_image, 0, -1);

                $image_path = $file_name . 'a.' . $ext;

                $image_name = $saleNumber . '-' . $item['number'];

                $get_image_path = $this->GetImageFromUrl($storePath, $image_path, $image_name);

                // resize

//                $largeImage = $this->resizeImage($get_image_path, $storePath, 1140);
//                $mediumImage = $this->resizeImage($get_image_path, $storePath, 500);
//                $smallImage = $this->resizeImage($get_image_path, $storePath, 150);

                $item->image_path = $get_image_path;
//                $item->image_large_path = $largeImage;
//                $item->image_medium_path = $mediumImage;
//                $item->image_small_path = $smallImage;

                $item->save();

//            break;

            }

            $christieSale->get_image = 1;
            $christieSale->save();

        }

    }

    public function imgResize()
    {
        $items = App\AuctionItem::where('image_medium_path', null)->get();
        foreach($items as $item) {

            $item->image_medium_path = 'pending';
            $item->save();

            $file = $item->image_path;

            echo $file;
            echo "\n";

            $exFile = explode('/', $file);
            $christieIntSaleID = $exFile[4];

            $storePath = 'images/auctions/christie/sale/' . $christieIntSaleID . '/';
            $item->image_large_path = $this->resizeImage($file, $storePath, 1140);
            $item->image_medium_path = $this->resizeImage($file, $storePath, 500);
            $item->image_small_path = $this->resizeImage($file, $storePath, 150);
            $item->save();

        }
    }

    public function imgFitResize()
    {
        $items = App\AuctionItem::where('image_fit_path', null)->get();
        foreach($items as $item) {

            $file = $item->image_path;

            echo $file;
            echo "\n";

            $exFile = explode('/', $file);
            $christieIntSaleID = $exFile[4];

            $storePath = 'images/auctions/christie/sale/' . $christieIntSaleID . '/';
            $item->image_fit_path = $this->createFitImage($file, $storePath, 250);
//            $item->image_large_path = $this->resizeImage($file, $storePath, 1140);
            $item->save();

//            break;

        }
    }

    private function resizeImage($file, $resizePath, $width)
    {
        $img = Image::make('storage/app/'.$file);

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $newPath = $resizePath.str_replace('.'.$fileExtension, '', basename($file)).'-'.$width.'.'.$fileExtension;

        echo $newPath;
        echo "\n";

        $img->widen($width, function ($constraint) {
            $constraint->upsize();
        })->save('public/'.$newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $newPath;
    }

    private function createFitImage($file, $resizePath, $width)
    {
        $img = Image::make('storage/app/'.$file);

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $newPath = $resizePath.str_replace('.'.$fileExtension, '', basename($file)).'-fit-'.$width.'.'.$fileExtension;

        echo $newPath;
        echo "\n";

        $img->fit($width)->save('public/'.$newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $newPath;
    }

    public function ReGetImage()
    {
        $items = App\AuctionItem::where('image_medium_path', null)->get();
        foreach($items as $item) {

            $source = str_replace('s.jpg', 'a.jpg', $item->source_image_path);

            echo $source;
            echo "\n";

            $christieIntSaleID = $item->sale->christieSale->int_sale_id;

            $storePath = 'images/auctions/christie/sale/' . $christieIntSaleID . '/';
            echo $storePath;
            echo "\n";

            $image_path = $this->GetImageFromUrl($storePath, $source, $item->sale->number.'-'.$item->number);

            $item->image_path = $image_path;
            $item->image_medium_path = null;
            $item->save();

        }
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

    private function getLotLocale($saleNumber, $locale, $lotNumber)
    {
        $localeArr = array('trad'=>'zh/', 'sim'=>'zh-CN/', 'en' => '');
        $url = 'http://www.christies.com/'.$localeArr[$locale].'lotfinder/lot_details.aspx?hdnsaleid='.$saleNumber.'&ln='.str_replace(' ', '', $lotNumber).'&intsaleid='.$saleNumber;
        echo "Getting Lot Locale From: ".$url."\n";

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

//        exit;

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
        $description = $spider->getElementByID('main_center_0_lblLotDescription');
        $contentArray['description'] = $description->ownerDocument->saveHTML($description);

        // main_center_0_lblLotDescription
        $provenance = $spider->getElementByID('main_center_0_lblLotProvenance');
        if($provenance == null) {
            $provenance = null;
        } else {
            $provenance = $provenance->ownerDocument->saveHTML($provenance);
        }
        $contentArray['provenance'] = $provenance;

        // main_center_0_lblLotDescription
        /*$postLotText = $spider->getElementByID('main_center_0_lblPostLotText');
        $contentArray['postLotText'] = $postLotText->ownerDocument->saveHTML($postLotText);

        main_center_0_lblPreLotText*/

        return $contentArray;

    }

    public function uploadS3()
    {
        $items = App\AuctionItem::all();
//        dd($items);

        $baseDirectory = 'public';

        foreach($items as $item) {

//            echo $item->image_large_path."\n";
//            echo $item->image_medium_path."\n";
//            echo $item->image_small_path."\n";
            echo $item->image_fit_path."\n";

//            $this->pushS3($baseDirectory, $item->image_large_path);
//            $this->pushS3($baseDirectory, $item->image_medium_path);
//            $this->pushS3($baseDirectory, $item->image_small_path);
            $this->pushS3($baseDirectory, $item->image_fit_path);
//            break;
        }


    }

    public function pushS3($baseDirectory, $filePath)
    {
        $s3 = \Storage::disk('s3');
        $localPath = $baseDirectory.'/'.$filePath;
        $image = fopen($localPath, 'r+');
        $s3->put('/'.$filePath, $image, 'public');

        echo $filePath."\n";
    }

    public function importDimension()
    {

        $sales = App\AuctionSale::all();
        foreach($sales as $sale) {
            $items = $sale->items;

            foreach($items as $item) {
                $itemDetail = $item->details()->where('lang', 'en')->first();
                $description = $itemDetail->description;
                $exDesc = explode("\r\n", $description);

                $dimension = null;

                foreach($exDesc as $dItem) {

                    if (stripos($dItem, "cm.") !== false) {
                        $dimension = $dItem;
                        $exItem = explode("cm.", $dItem);
                        $dimension = $exItem[0].' cm';
                        echo $dimension;
                        echo '<br>';
                    }

                }

                $item->dimension = $dimension;
                $item->save();

            }
        }

    }

}
