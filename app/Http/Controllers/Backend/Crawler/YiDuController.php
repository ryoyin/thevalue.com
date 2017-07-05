<?php

namespace App\Http\Controllers\Backend\Crawler;


use App\Http\Controllers\Controller;
use App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

class YiDuController extends Controller
{
    public function index()
    {
        $locale = App::getLocale();


        $sales = App\YiDuSale::all();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'crawler', 'yidu.index'),
            'sales' => $sales
        );

        return view('backend.auctions.crawler.yidu.index', $data);
    }

    public function crawler(Request $request)
    {
        $intSaleID = trim($request->int_sale_id);

        $this->getSaleByIntSaleID($intSaleID);

        return redirect()->route('backend.auction.yidu.index');
    }

    public function getSaleByIntSaleID($intSaleID)
    {

        set_time_limit(600);

        echo "<p>";
        echo 'Spider '.$intSaleID.' start';
        echo "<br>";


        $url = 'http://www.yidulive.com/auctionlist/show.php?sid='.$intSaleID.'&counts=1000&o=0&plo=0&page=1';

        echo "<br>";
        echo "Getting content from: ".$url;
        echo "<br>";

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $content=curl_exec($cSession);

        $storePath = 'spider/yidu/sale/' . $intSaleID . '/';

        Storage::disk('local')->put($storePath . $intSaleID . '.html', $content);

        // id	int_sale_id	html	json	image	import	status
        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();

        if(count($sale) == 0) {
            $sale = New App\YiDuSale;
            $sale->int_sale_id = $intSaleID;
        }

        $sale->html = false;
        $sale->json = false;
        $sale->image = false;
        $sale->resize = false;
        $sale->pushS3 = false;
        $sale->import = false;
        $sale->status = 0;
        $sale->save();

        echo 'Spider '.$intSaleID.' end';
        echo "<br>";

        return true;

    }

    public function makeSaleInfo($intSaleID)
    {
        $storePath = 'spider/yidu/sale/'.$intSaleID.'/';
        $path = $storePath.$intSaleID.'.html';

        $html = Storage::disk('local')->get($path);

//        echo $html;

        $sale = array();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

//        exit;

        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'content')]");
        $content = $dom->saveHTML($node->item(0));

        $dom->loadHTML($content);

        $items = $dom->getElementsByTagName('li');

        $itemArray = array();

        foreach($items as $key => $item) {
            $getItemLink = $item->getElementsByTagName('span');
            $getItemLink = $getItemLink[0]->getElementsByTagName('a');

            $itemPath = 'http://www.yidulive.com/auctionlist/';

            $itemLink = $getItemLink[0]->getAttribute('href');

            $itemURL = $itemPath.$itemLink;

            $itemArray[$key]['url'] = $itemURL;

            $itemFilePath = $this->getItemByURL($intSaleID, $key, $itemURL);

            $itemArray[$key]['filePath'] = $itemFilePath;

//            exit;
        }

        $itemJSON = json_encode($itemArray);

        Storage::disk('local')->put($storePath . $intSaleID . '.json', $itemJSON);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->html = true;
        $sale->save();

        return redirect()->route('backend.auction.yidu.index');

    }

    private function getItemByURL($intSaleID, $key, $url)
    {
        set_time_limit(600);

        echo "<br>";
        echo "Getting content from: ".$url;
        echo "<br>";

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $content=curl_exec($cSession);

        $storePath = 'spider/yidu/sale/'.$intSaleID.'/item/';

        $filePath = $storePath.$key.'.html';

        Storage::disk('local')->put($filePath, $content);

        return $filePath;
    }

    public function crawlerRemove($intSaleID)
    {

        $intSaleID = trim($intSaleID);

        $path = 'spider/yidu/sale/'.$intSaleID;

        Storage::disk('local')->delete($path.'/'.$intSaleID.'.json');
        Storage::disk('local')->deleteDirectory($path);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
//        dd($sale);
        $sale->delete();

        return redirect()->route('backend.auction.yidu.index');

    }

    public function parseItems($intSaleID)
    {
        $internalErrors = libxml_use_internal_errors(true);

        $storePath = 'spider/yidu/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($storePath);
        $itemArray = json_decode($json, true);

//        dd($saleArray);
        $lotInfo = array();

        foreach($itemArray as $item ) {

//            echo '<p>';

            $html = Storage::disk('local')->get($item['filePath']);

            $dom = new \DOMDocument('1.0', 'UTF-8');

            $dom->loadHTML($html);

            // get image url
            $imageBlock = $dom->getElementById('detailImg-box');
            $imageBlock = $imageBlock->getElementsByTagName('a');
            $imagePath = $imageBlock[0]->getAttribute('href');

//            echo 'Image Path: '.$imagePath.'<br>';

            // get title detailTitle
            $finder = new \DomXPath($dom);
            $node = $finder->query("//*[contains(@class, 'detailTitle')]");
//            $content = $dom->saveHTML($node->item(0));
            $titleBlock = $node->item(0)->textContent;

            // get lot number
            $exTitle = explode(' ', $titleBlock);
            $lotNumber = trim($exTitle[1]);

//            echo 'Lot Number: '.$lotNumber.'<br>';

            $exTitle = explode($lotNumber, $titleBlock);
            $title = $exTitle[1];

//            echo 'Title: '.$title.'<br>';

            // estimate hd_xx 估价：
            $finder = new \DomXPath($dom);
            $node = $finder->query("//*[contains(@class, 'hd_xx')]");

            $estimateBlock = $node->item(0)->textContent;
            $exEstimateBlock = explode(' ', $estimateBlock);

            $estimate = str_replace('估价：', '', $exEstimateBlock[0]);

//            echo "Estimate: ".$estimate."<br>";

            if(strpos($estimate, '无底价') === false) {

                $exEstimate = explode('-', $estimate);

                // initial estimate
                $estimateInitial = $exEstimate[0];
                $estimateEnd = $exEstimate[1];

                // Currency Code
                $currencyCode = $exEstimateBlock[1];

            } else {

                $estimateInitial = null;
                $estimateEnd = null;
                $currencyCode = null;

            }

//            echo "Initial: ".$estimateInitial."<br>";
//            echo "End: ".$estimateEnd."<br>";

//            echo "Currency Code: ".$currencyCode.'<br>';

            // hd_mx
            $finder = new \DomXPath($dom);
            $node = $finder->query("//*[contains(@class, 'hd_mx')]");

            $content = $node->item(0);

            $paragraph = $content->getElementsByTagName('p');

            $dimension = $paragraph[0]->textContent;
            $description = $paragraph[2]->textContent;

//            echo 'Dimension: '.$dimension."<br>";
//            echo 'Description: '.$description.'<br>';

            $lotInfo[] = array(
                'source_image_path' => $imagePath,
                'number' => $lotNumber,
                'title' => $title,
                'estimate_initial' => $estimateInitial,
                'estimate_end' => $estimateEnd,
                'currency_code' => $currencyCode,
                'dimension' => $dimension,
                'description' => $description
            );

//            exit;

        }

//        echo '<p>';
//        echo 'Auction Info: <br>';

        $auctionInfo = array();

        // get auction time main_t > dd
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'position')]");
        $content = $node->item(0);

        $navigationBlock = $content->getElementsByTagName('a');

        foreach($navigationBlock as $nKey => $n) {
            switch($nKey) {
                case 2:
//                    echo 'Series: '.$n;
                    $seriesLink = $n->getAttribute('href');
                    $seriesTitle = $n->textContent;
                    $exSeries = explode('·', $seriesTitle);
//                    echo 'Series URL: '.$seriesLink.'<br>';
//                    echo 'Series: '.$exSeries[1].'<br>';
                    break;
                case 3:
                    $saleTitle = $n->textContent;
                    break;
            }
//            if($nKey > 2) echo $n->textContent.'<br>';
        }

        $auctionInfo['title'] = $saleTitle;

        // get auction time main_t > dd
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'main_t')]");
        $content = $node->item(0);

        $auctionInfoBlock = $content->getElementsByTagName('dd');

        foreach($auctionInfoBlock as $aKey => $a) {

            $text = $a->textContent;

            // replace "拍卖地点：" didn't work
            $searchArr = array('预展时间：', '预展地点：', '拍卖时间：', '拍卖地点：'. '\n');

            $text = str_replace($searchArr, '', $text);

            $text = trim($text);

//            echo $text.'<br>';

            switch($aKey) {
                case 0:
                    $auctionInfo['viewing_time'] = $text;
                    break;
                case 1:
                    $text = trim(substr($text, 16, strlen($text))); // remove 拍卖地点：
                    $auctionInfo['viewing_location'] = $text;
                    break;
                case 2:
                    $exText = explode('至', $text);

                    $auctionStartTime = trim($exText[0]);

                    $auctionStartTime = str_replace('年', '-', $auctionStartTime);
                    $auctionStartTime = str_replace('月', '-', $auctionStartTime);
                    $auctionStartTime = str_replace('日', '', $auctionStartTime);

                    $auctionStartTime = strtotime($auctionStartTime);

                    if(count($exText) == 1) {
                        $auctionEndTime = $auctionStartTime;
                    } else {
                        $auctionEndTime = trim($exText[1]);
                    }

                    // echo $auctionEndTime;
                    if($auctionEndTime == '（时间顺延）' || $auctionEndTime == '(时间顺延）') {
                        // echo 'detected';
                        $auctionEndTime = $auctionStartTime;
                    }

                    $auctionTime = array(
                        'start_time' => $auctionStartTime,
                        'end_time' => $auctionEndTime
                    );

                    $auctionInfo['auction_time'] = $auctionTime;

                    break;
                case 3:
                    $text = trim(substr($text, 16, strlen($text))); // remove 拍卖地点：
                    $auctionInfo['auction_location'] = $text;
                    break;
            }

        }

//        exit;

        // get sale image http://www.yidulive.com/
        $seriesLink = str_replace('../', 'http://www.yidulive.com/', $seriesLink);
        $saleImagePath = $this->getSeriesImage($seriesLink, $saleTitle);

        $auctionInfo['source_image_path'] = $saleImagePath;

        $saleArray = array(
            'sale' => $auctionInfo,
            'lots' => $lotInfo,
        );

        $json = json_encode($saleArray);

        $storePath = 'spider/yidu/sale/' . $intSaleID . '/';
        Storage::disk('local')->put($storePath . 'saleInfo.json', $json);

        //update sale progress
        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->json = true;
        $sale->save();

        // dd($saleArray);
        return redirect()->route('backend.auction.yidu.index');
    }

    private function getSeriesImage($url, $saleTitle)
    {
        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession,CURLOPT_HEADER, false);

        $content=curl_exec($cSession);

//        echo $content;

        $dom = new \DOMDocument('1.0', 'UTF-8');

        $dom->loadHTML($content);

        // get auction time main_t > dd
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'context')]");
        $content = $node->item(0);

        $sales = $content->getElementsByTagName('li');

        $image_path = false;

        foreach($sales as $sKey => $sale) {
            if(strpos($sale->textContent, $saleTitle)) {
                $images = $sale->getElementsByTagName('img');
                $image_path = 'http://www.yidulive.com'.$images[0]->getAttribute('src');
            }
        }

        return $image_path;

    }


    public function downloadImages($intSaleID)
    {
        set_time_limit(6000);

        $intSaleID = trim($intSaleID);

        $locale = App::getLocale();

        $path = 'spider/yidu/sale/'.$intSaleID.'/saleInfo.json';
        $storePath = 'spider/yidu/sale/'.$intSaleID.'/images/';

//        echo $path;


        $json = Storage::disk('local')->get($path);

//        echo $json;

        $saleArray = json_decode($json, true);

//        dd($saleArray);

        $saleImageURL = $saleArray['sale']['source_image_path'];

        $saleImageName = 'sale_image.jpg';

        echo 'Downloading Sale Image: '.$saleImageURL.'<br>';
        $saleImagePath = $this->getImageFromUrl($storePath, $saleImageURL, $saleImageName);

        $saleArray['sale']['saved_image_path'] = $saleImagePath;

        foreach($saleArray['lots'] as $lotKey => $lot) {

            $lotFilename = $lot['number'].'.jpg';

            $lotImagePath = $storePath.$lotFilename;

//            echo 'checking: storage/app/'.$lotImagePath.'<br>';
//            echo 'base path: '.base_path().'<br>';

            if(file_exists(base_path().'/storage/app/'.$lotImagePath)) {
//                echo 'file exist<br>';
                echo $lotImagePath.'<br>';
                $saleArray['lots'][$lotKey]['saved_image_path'] = $lotImagePath;
            } else {
//                echo 'file not exist<br>';
                echo 'Downloading lot Image: '.$lot['source_image_path'].'<br>';
                $lotImagePath = $this->getImageFromUrl($storePath, $lot['source_image_path'], $lotFilename);
                $saleArray['lots'][$lotKey]['saved_image_path'] = $lotImagePath;
            }

        }

//        dd($saleArray);

        // save image path to saleArray
        $json = json_encode($saleArray);

        $storePath = 'spider/yidu/sale/' . $intSaleID . '/';
        Storage::disk('local')->put($storePath . 'saleInfo.json', $json);

        //update sale progress
        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->image = true;
        $sale->save();

        return redirect()->route('backend.auction.yidu.index');

    }

    private function getImageFromUrl($storePath, $link, $filename)
    {
        $image_path = $storePath.$filename;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $image=curl_exec($ch);

        curl_close($ch);

        Storage::disk('local')->put($image_path, $image);

        return $image_path;
    }

    public function resize($intSaleID)
    {
        set_time_limit(60000);

        $intSaleID = trim($intSaleID);
        $locale = App::getLocale();

        $path = 'spider/yidu/sale/'.$intSaleID.'/saleInfo.json';
        $storePath = 'spider/yidu/sale/'.$intSaleID.'/images/';

//        echo $path;

        $json = Storage::disk('local')->get($path);

//        echo $json;

        $saleArray = json_decode($json, true);

        // dd($saleArray);

        $saleImagePath = $saleArray['sale']['saved_image_path'];
        $img = Image::make(base_path().'/'.'storage/app/'.$saleImagePath);

        $salePath = 'images/auctions/sales/'.time();
        $fullSalePath = base_path().'/public/'.$salePath;

        // create directory
        if(!file_exists($fullSalePath)) mkdir($fullSalePath);

        $newPath = $fullSalePath.'/sale_image.jpg';
        $img->save($newPath);

        $saleArray['sale']['stored_image_path'] = $salePath.'/sale_image.jpg';

        foreach($saleArray['lots'] as $lKey => $lot) {
            echo $lot['number'].'<br>';
            $lotImage = $this->imgResize($lot['saved_image_path'], $fullSalePath, $lot['number']);
            $saleArray['lots'][$lKey]['stored_image_path'] = $lotImage;
        }

//        exit;

        $json = json_encode($saleArray);

        Storage::disk('local')->put($path, $json);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->resize = true;
        $sale->save();

        return redirect()->route('backend.auction.yidu.index');

    }

    public function imgResize($savedImagePath, $salePath, $lotNumber)
    {

        ini_set('memory_limit','1024M');

        $image_large_path = $this->resizeImage($savedImagePath, $salePath, $lotNumber, 1140);
        $image_medium_path = $this->resizeImage($savedImagePath, $salePath, $lotNumber, 500);
        $image_small_path = $this->resizeImage($savedImagePath, $salePath, $lotNumber, 150);
        $image_fit_path = $this->resizeImage($savedImagePath, $salePath, $lotNumber, 250);

        $image_path = array(
            'large' => str_replace(base_path().'/public/', '', $image_large_path),
            'medium' => str_replace(base_path().'/public/', '', $image_medium_path),
            'small' => str_replace(base_path().'/public/', '', $image_small_path),
            'fit' => str_replace(base_path().'/public/', '', $image_fit_path)
        );

        return $image_path;
    }

    private function resizeImage($file, $resizePath, $lotNumber, $width)
    {

//        echo $file;
//        echo '<br>';
//

        echo 'resize: '.$resizePath;
        echo '<br>';

        $img = Image::make(base_path().'/'.'storage/app/'.$file);

        $newPath = $resizePath.'/'.$lotNumber.'-'.$width.'.jpg';

        echo $newPath;
        echo "<br>";

        $img->widen($width, function ($constraint) {
            $constraint->upsize();
        });

        $img->heighten($width, function ($constraint) {
            $constraint->upsize();
        })->save($newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $newPath;
    }

    public function uploadS3($intSaleID)
    {
        set_time_limit(60000);

        $intSaleID = trim($intSaleID);
        $locale = App::getLocale();

        $path = 'spider/yidu/sale/'.$intSaleID.'/saleInfo.json';
        $storePath = 'spider/yidu/sale/'.$intSaleID.'/images/';

//        echo $path;

        $json = Storage::disk('local')->get($path);

//        echo $json;

        $saleArray = json_decode($json, true);

        $baseDirectory = base_path().'/public';

        foreach($saleArray['lots'] as $lot) {

            $this->pushS3($baseDirectory, $lot['stored_image_path']['fit']);
            $this->pushS3($baseDirectory, $lot['stored_image_path']['large']);
            $this->pushS3($baseDirectory, $lot['stored_image_path']['medium']);
            $this->pushS3($baseDirectory, $lot['stored_image_path']['small']);

        }

        Storage::disk('local')->put($path, $json);

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->pushS3 = true;
        $sale->save();

        return redirect()->route('backend.auction.yidu.index');

    }

    public function pushS3($baseDirectory, $filePath)
    {
        $s3 = \Storage::disk('s3');
        $localPath = $baseDirectory.'/'.$filePath;

        echo $localPath;

        $image = fopen($localPath, 'r+');
        $s3->put('/'.$filePath, $image, 'public');
    }

    public function examine($intSaleID)
    {
        $intSaleID = trim($intSaleID);

        $locale = App::getLocale();

        $path = 'spider/yidu/sale/'.$intSaleID.'/saleInfo.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

//        dd($saleArray);

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'yidu.index'),
            'saleArray' => $saleArray,
            'intSaleID' => $intSaleID,
        );

//        dd($saleArray);

        return view('backend.auctions.crawler.yidu.captureItemList', $data);

    }

    public function import(Request $request, $intSaleID)
    {
        $intSaleID = trim($intSaleID);

        $auctionSeriesID = trim($request->auction_series_id);
        $slug = trim($request->slug);

        $series = App\AuctionSeries::find($auctionSeriesID);
        $seriesDetails = $series->details();

        $house = $series->house;

        if(count($series) == 0) exit;

        $path = 'spider/yidu/sale/'.$intSaleID.'/saleInfo.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        // dd($saleArray);

        $saleSlug = $series->slug.'-'.$slug;

//        exit;

        // insert auction_sales
        // slug, source_image_path, image_path, number, total_lots, start_date, end_date, auction_series_id
        $sale = New App\AuctionSale;

        $sale->slug = $saleSlug;
        $sale->source_image_path = $saleArray['sale']['source_image_path'];
        $sale->image_path = $saleArray['sale']['stored_image_path'];
        $sale->number = $intSaleID;
        $sale->total_lots = count($saleArray['lots']);
        $sale->start_date = date('Y-m-d H:i:s', $saleArray['sale']['auction_time']['start_time']);
        $sale->end_date = date('Y-m-d H:i:s', $saleArray['sale']['auction_time']['end_time']);
        $sale->auction_series_id = $auctionSeriesID;

        $sale->save();

//        exit;

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
            $saleDetail->title = $saleArray['sale']['title'];
            $houseDetail = $house->getDetailByLang($lang);
            $saleDetail->country = $houseDetail->country;
            $saleDetail->location = $saleArray['sale']['auction_location'];
            $saleDetail->lang = $lang;
            $saleDetail->auction_sale_id = $saleID;
            $saleDetail->save();
        }

        if($saleArray['sale']['viewing_time'] != '') {
            // sale detail type viewing
            foreach ($supported_languages as $lang) {
                $saleDetail = New App\AuctionSaleDetail;
                $saleDetail->type = 'viewing';
                $saleDetail->title = $saleArray['sale']['title'];
                $houseDetail = $house->getDetailByLang($lang);
                $saleDetail->country = $houseDetail->country;
                $saleDetail->location = $saleArray['sale']['viewing_location'];
                $saleDetail->lang = $lang;
                $saleDetail->auction_sale_id = $saleID;
                $saleDetail->save();
            }
        }

        // insert auction_sale_times
        // type, lots, start_date, end_date, auction_sale_id
        // sale date
        $saleTime = New App\AuctionSaleTime;
        $saleTime->type = 'sale';
        $saleTime->start_date =  date('Y-m-d H:i:s', $saleArray['sale']['auction_time']['start_time']);
        $saleTime->end_date =  date('Y-m-d H:i:s', $saleArray['sale']['auction_time']['end_time']);
        $saleTime->auction_sale_id = $saleID;
        $saleTime->save();

        if($saleArray['sale']['viewing_time'] != '') {
            $viewingStartTime = date('Y-m-d H:i:s', $saleArray['sale']['viewing_time']['start_time']);
            $viewingEndTime = date('Y-m-d H:i:s', $saleArray['sale']['viewing_time']['end_time']);

            $viewingTime = New App\AuctionSaleTime;
            $viewingTime->type = 'viewing';
            $viewingTime->start_date = $viewingStartTime;
            $viewingTime->end_date = $viewingEndTime;
            $viewingTime->auction_sale_id = $saleID;

            $viewingTime->save();
        }

        // insert auction_items
        // slug, dimension, number,
        // source_image_path, image_path, image_fit_path, image_large_path, image_medium_path, image_small_path,
        // currency_code, estimate_value_initial, estimate_value_end, sold_value, sorting, status, auction_sale_id

        $counter = 10;

//        exit;

        foreach($saleArray['lots'] as $lot) {
            // filter dimension

            $dimension = $lot['dimension'];

            $item = New App\AuctionItem;
            $item->slug = $slug.'-'.$lot['number'];
            $item->dimension = $dimension;
            $item->number = $lot['number'];
            $item->source_image_path = $lot['source_image_path'];
            $item->image_path = $lot['saved_image_path'];
            $item->image_fit_path = $lot['stored_image_path']['fit'];
            $item->image_large_path = $lot['stored_image_path']['large'];
            $item->image_medium_path = $lot['stored_image_path']['medium'];
            $item->image_small_path = $lot['stored_image_path']['small'];
            $item->currency_code = $house->currency_code;

            if($lot['currency_code'] == '') {
                $estimate_value_initial = null;
                $estimate_value_end = null;
            } else {
                $estimate_value_initial = str_replace(',', '', $lot['estimate_initial']);
                $estimate_value_end = str_replace(',', '', $lot['estimate_end']);
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
                $itemDetail->title = $lot['title'];
                $itemDetail->description = $lot['description'];
                $itemDetail->maker = null;
                $itemDetail->misc = null;
                $itemDetail->lang = $lang;
                $itemDetail->auction_item_id = $itemID;
                $itemDetail->save();
            }

            $counter += 10;

        }

        $sale = App\YiDuSale::where('int_sale_id', $intSaleID)->first();
        $sale->import = true;
        $sale->save();


        // backend.auction.itemList
        return redirect()->route('backend.auction.yidu.index');

    }

}
