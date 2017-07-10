<?php

namespace App\Http\Controllers\Backend\Crawler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

use App\Http\Controllers\Controller;
use App;

class SothebysController extends Controller
{

    public function index()
    {
        $locale = App::getLocale();

        $sales = App\SothebysSale::all();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'crawler', 'sothebys.index'),
            'sales' => $sales
        );

        return view('backend.auctions.crawler.sothebys.index', $data);
    }

    public function crawler(Request $request)
    {
        $url = trim($request->url);

//        echo $url;

        $this->getSaleByURL($url);

        return redirect()->route('backend.auction.sothebys.index');
    }

    public function getSaleByURL($url)
    {

        set_time_limit(60000);

        echo "Getting content from: ".$url;
        echo "<br>";

        $filename = str_replace('.html', '', basename($url));

        $exFilename = explode('-', $filename);
        $intSaleID = $exFilename[count($exFilename) -1];

        echo "Int Sale ID: ".$intSaleID;

        echo "<br>";

//        exit;

        echo "try: ".$url;
        echo "<br>";

        // get EN Content;
        $this->getMainContentByLang($url, $intSaleID, 'en');
        //get ZH Content;
        $url = str_replace('/en/', '/zh/', $url);
        $this->getMainContentByLang($url, $intSaleID, 'zh');

        // id	int_sale_id	html	json	image	import	status
        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();

        if(count($sale) == 0) {
            $sale = New App\SothebysSale;
            $sale->int_sale_id = $intSaleID;
        }

        $sale->url = $url;
        $sale->title = '';
        $sale->html = false;
        $sale->json = false;
        $sale->image = false;
        $sale->resize = false;
        $sale->pushS3 = false;
        $sale->import = false;
        $sale->import = false;
        $sale->import = false;
        $sale->status = 0;
        $sale->save();

        echo 'Spider '.$url.' end';
        echo "<br>";

        return true;

    }

    public function getMainContentByLang($url, $intSaleID, $lang)
    {

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession, CURLOPT_HEADER, 0);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,1);

        $headers = [
            'Accept: application/json, text/javascript, */*; q=0.01',
//            'Accept-Encoding: gzip, deflate',
//            'Accept-Language: zh-CN,zh;q=0.8',
            'Connection: keep-alive',
            'Content-Length: 0',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Host: www.sothebys.com',
            'Origin: http://www.sothebys.com',
            'Referer: '.$url, //Your referrer address
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'X-MicrosoftAjax: Delta=true',
            'X-Requested-With: XMLHttpRequest',
        ];

        curl_setopt($cSession,CURLOPT_HTTPHEADER, $headers);

        $content=curl_exec($cSession);

//        echo $content;

        // exit;

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/'.$lang.'/';

        Storage::disk('local')->put($storePath . $intSaleID . '.html', $content);

    }

    public function downloadData($intSaleID)
    {

        $mainContentEN = $this->parseMainContentByLang($intSaleID, 'en');
        $mainContentZH = $this->parseMainContentByLang($intSaleID, 'zh');

        $salesArray = array(
            'sale' => array(
                'en' => $mainContentEN,
                'zh' => $mainContentZH
            )
        );

        foreach($salesArray['sale']['en']['lots'] as $lot) {
            $url = str_replace("'", '', $lot['condRep']);
            $exURL = explode('#', $url);
            $url = $exURL[0];

            $salesArray['lots'][] = array(
                // Useful Item: id, image, lowEst, highEst, salePrice, condRep (url)
                'number' => str_replace("'", '', $lot['id']),
                'source_image_path' => 'http://www.sothebys.com'.str_replace("'", '', $lot['image']),
                'currency' => str_replace("'", '', $lot['currency']),
                'estimate_initial' => str_replace("'", '', $lot['lowEst']),
                'estimate_end' => str_replace("'", '', $lot['highEst']),
                'realized_price' => str_replace("'", '', $lot['salePrice']),
                'url' => 'http://www.sothebys.com'.$url
            );
        }

        foreach($salesArray['lots'] as $lot) {

//            http://www.sothebys.com/en/auctions/ecatalogue/2017/treasures-l17303/lot.1.html
//            http://www.sothebys.com/zh/auctions/ecatalogue/lot.1.html/2017/treasures-l17303

            $url = $lot['url'];
            // get lot en content
            $this->getLotContentByLang($url, $intSaleID, 'en', $lot['number']);

            // get lot zh content

            $uri = str_replace('http://www.sothebys.com/en/auctions/ecatalogue/', '', $url);

            $pattern = explode('/', $uri);

            $urlPattern = $pattern[0].'/'.$pattern[1];

            $zhURL = 'http://www.sothebys.com/en/auctions/ecatalogue/'.$pattern[2].'/'.$urlPattern;

            $url = str_replace('/en/', '/zh/', $zhURL);

            $this->getLotContentByLang($url, $intSaleID, 'zh', $lot['number']);
        }

        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/';

        Storage::disk('local')->put($storePath . $intSaleID . '.json', json_encode($salesArray));

//        $sale->start_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']);
//        $sale->end_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']);

//        dd($salesArray);

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->html = true;
        $sale->title = $salesArray['sale']['en']['title'];
        $sale->start_date = date('Y-m-d H:i:s', $salesArray['sale']['en']['auction']['datetime']);
        $sale->end_date = date('Y-m-d H:i:s', $salesArray['sale']['en']['auction']['datetime']);
        $sale->save();

        return redirect()->route('backend.auction.sothebys.index');

    }

    public function parseItems($intSaleID)
    {
        $internalErrors = libxml_use_internal_errors(true);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

        // auction_sales - slug, *source_image_path, *image_path, number, start_date, end_date

        // auction_sale_details - type (sale/viewing), title, country, location, lang

        // auction_sale_times - type (sale/viewing), lots, start_date, end_date

        // auction_items - slug, *dimension, number,
        // source_image_path, image_path, image_fit_path, image_large_path, image_medium_path, image_small_path,
        // currency_code, estimate_value_initial, estimate_value_end, sold_value, sorting, status

        // auction_item_details - title, description, *maker, misc, provenance, *post_lot_text, exhibited, lang

//        dd($saleArray);

        foreach($saleArray['lots'] as $key => $lot) {
            $saleArray['lots'][$key] = $this->getLotDetailsByLang($intSaleID, $lot['number'], 'en', $lot);
            $saleArray['lots'][$key] = $this->getLotDetailsByLang($intSaleID, $lot['number'], 'zh', $saleArray['lots'][$key]);
        }

//        dd($saleArray);

        $saleArray = json_encode($saleArray);
        Storage::disk('local')->put($storePath, $saleArray);

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->json = true;
        $sale->save();

        return redirect()->route('backend.auction.sothebys.index');

    }

    public function downloadImages(Request $request, $intSaleID)
    {
        set_time_limit(60000);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

//        dd($saleArray);

        $salePath = 'images/auctions/sothebys/'.$intSaleID.'/';

        // Get Sale Image
        $saleSourceImagePath = 'http://www.sothebys.com'.$request->sale_image_path;
//        echo $saleSourceImagePath;

        $saleFilename = $intSaleID.'_sale_image.jpg';

        $saleImagePath = $this->getImageFromUrl($salePath, $saleSourceImagePath, $saleFilename);

        $saleArray['sale']['source_image_path'] = $saleSourceImagePath;
        $saleArray['sale']['image_path'] = $saleImagePath;

        File::copy(base_path().'/storage/app/'.$saleImagePath, base_path().'/storage/app/spider/sothebys/sale/'.$intSaleID.'/sale_image.jpg');

//        exit;

        // - Sale Image

        // Get Lot Image
        foreach($saleArray['lots'] as $key => $lot) {
            $lotImagePath = $lot['source_image_path'];

            $lotImagePath = str_replace(" ", "%20", $lotImagePath);

            $lotFilename = $lot['number'].'.jpg';

            echo "source: ".$lotImagePath;
            echo '<br>';
            echo "search: ".base_path().'/storage/app/'.$salePath.$lotFilename;
            echo '<br>';

            if(!file_exists(base_path().'/storage/app/'.$salePath.$lotFilename)) {
                $lotImagePath = $this->getImageFromUrl($salePath, $lotImagePath, $lotFilename);
                echo 'file not exist: '.$lotImagePath;
                echo '<br>';
            } else {
                $lotImagePath = $salePath.$lotFilename;
                echo 'file exist: '.$salePath.$lotFilename;
                echo '<br>';
            }
            $saleArray['lots'][$key]['image_path'] = $lotImagePath;
        }
        // - Lot Image

//        dd($saleArray);

        $saleArray = json_encode($saleArray);
        Storage::disk('local')->put($storePath, $saleArray);

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->image = true;
        $sale->save();

        return redirect()->route('backend.auction.sothebys.index');

    }

    public function resize($intSaleID)
    {
        set_time_limit(60000);



        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

//        dd($saleArray);

        // save sale image
        $saleImagePath = $saleArray['sale']['image_path'];
        $img = Image::make(base_path().'/'.'storage/app/'.$saleImagePath);

        $salePath = 'images/auctions/sothebys/'.$intSaleID.'/';
        $fullSalePath = base_path().'/public/'.$salePath;

        // create directory
        if(!file_exists($fullSalePath)) mkdir($fullSalePath);

        $saleFilename = $intSaleID.'sale_image.jpg';

        $newPath = $fullSalePath.'/'.$saleFilename;
        $img->save($newPath);

        $saleArray['sale']['stored_image_path'] = $salePath.'/'.$saleFilename;

        foreach($saleArray['lots'] as $key => $lot) {
            echo $lot['number'].'<br>';
            $lotImage = $this->imgResize($lot['image_path'], $fullSalePath, $lot['number']);
            $saleArray['lots'][$key]['stored_image_path'] = $lotImage;
        }

        $saleArray = json_encode($saleArray);
        Storage::disk('local')->put($storePath, $saleArray);

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->resize = true;
        $sale->save();

        $this->createGZipFile($intSaleID);

        return redirect()->route('backend.auction.sothebys.index');

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

        $newPath = $resizePath.$lotNumber.'-'.$width.'.jpg';

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

    private function getImageFromUrl($storePath, $link, $filename)
    {
        $image_path = $storePath.$filename;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        $headers = [
            'Accept: application/json, text/javascript, */*; q=0.01',
//            'Accept-Encoding: gzip, deflate',
//            'Accept-Language: zh-CN,zh;q=0.8',
            'Connection: keep-alive',
            'Content-Length: 0',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Host: www.sothebys.com',
            'Origin: http://www.sothebys.com',
            'Referer: '.$link, //Your referrer address
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'X-MicrosoftAjax: Delta=true',
            'X-Requested-With: XMLHttpRequest',
        ];

        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);

        $image=curl_exec($ch);

//        echo $image;

        curl_close($ch);

        Storage::disk('local')->put($image_path, $image);

        return $image_path;
    }

    private function getLotDetailsByLang($intSaleID, $number, $lang, $lot)
    {
        echo $number;
        echo '<br>';

        // get raw html content
        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/'.$lang.'/';
        $path = $storePath.$number.'.html';
        $html = Storage::disk('local')->get($path);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

//        echo $html;

        // get title - lotdetail-guarantee
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'lotdetail-guarantee')]");
        $title = $node->item(0)->textContent;
        $lot[$lang]['title'] = $title;
        // - title

        // get misc - lotdetail-subtitle
        $node = $finder->query("//*[contains(@class, 'lotdetail-subtitle')]");
        if($node->length > 0) {
            $misc = $node->item(0)->textContent;
            $lot[$lang]['misc'] = $misc;
        }
        // - misc

        // get description - lotdetail-description
        $node = $finder->query("//*[contains(@class, 'lotdetail-description-text')]");
        if($node->length > 0) {
            $description = $node->item(0)->textContent;
            $lot[$lang]['description'] = trim(str_replace("\r\n", '', $description));
        }
        // - description

        // get provenance - readmore-content
        $node = $finder->query("//*[contains(@class, 'readmore-content')]");
//        $provenanceBlock = $node->item(0)->ownerDocument->saveHTML($node->item(0));

        foreach($node as $key => $item) {

            if($key == 0) {
                $provenance = $item->textContent;
                $provenance = str_replace(";", ";<br>", $provenance);
                $lot[$lang]['provenance'] = $provenance;
            }

            if($key == 1) {
                $exhibited = $item->textContent;
                $exhibited = str_replace(";", ";<br>", $exhibited);
                $lot[$lang]['exhibited'] = $exhibited;
            }

        }

//        dd($lot);

        return $lot;

    }

    private function parseMainContentByLang($intSaleID, $lang)
    {
        $contentArray = array(); // declare content array

        // get raw html content
        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/'.$lang.'/';
        $path = $storePath.$intSaleID.'.html';
        $html = Storage::disk('local')->get($path);

//        echo $html;

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($html);

        $contentArray['title'] =  $this->getTitle($dom); // Get Title

        $contentArray['auction']['datetime'] = $this->getAuctionDateTime($dom); // Get Auction Datetime

        $contentArray['auction']['location'] = $this->getAuctionLocation($dom); // Get Auction Location - eventdetail-eventtime

        $contentArray['viewing'] = $this->getViewingInfo($dom); // Get Viewing Datetime -  class: eventdetail-times > ul

        // Get Lot Info
        $dom->loadHTML($html); //resset load html
        $contentArray['lots'] = $this->getLotInfo($dom);

        return $contentArray;

    }

    private function getTitle($dom)
    {
        $headerItems = $dom->getElementsByTagName('h1');

        $title = '';

        foreach($headerItems as $key => $item) {
            $title .= $item->textContent;
        }

        $title = str_replace("\r\n", '', $title);
        $title = str_replace('Now', '', $title);
        $title = str_replace('現在進行中', '', $title);
        $title = trim($title);

        return $title;
    }

    private function getAuctionDateTime($dom)
    {
        $timeItems = $dom->getElementsByTagName('time');

        $time = '';

        foreach($timeItems as $key => $item) {
            if($time != '') $time .= ' ';
            $time .= $item->textContent;
        }

        $time = strtotime($time);

        return $time;
    }

    private function getAuctionLocation($dom)
    {
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'eventdetail-eventtime')]");
        $auctionLocBlock = $node->item(0)->textContent;

        $exAuctionLoc = explode('|', $auctionLocBlock);
        $auctionLocation = $exAuctionLoc[count($exAuctionLoc) - 1];

        $auctionLocation = str_replace("\r\n", '', $auctionLocation);
        $auctionLocation = trim($auctionLocation);

        return $auctionLocation;
    }

    private function getViewingInfo($dom)
    {
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'eventdetail-times')]");

        if($node->length == 0) return false;

        $content = $dom->saveHTML($node->item(0));

        $locationBlock = $node->item(0)->textContent;

//        echo $locationBlock;
//        $locationBlock =
        $exLocation = explode("\r\n", $locationBlock);

        $foundItem = array();
        foreach($exLocation as $key => $item) {
            $parsed = trim(str_replace("\n", '', $item));
            if($parsed != '') {
                $foundItem[] = $parsed;
            }
        }

        $location = $foundItem[1];

//        echo $location;
//        echo '<br>';

        $dom->loadHTML($content);

        $viewingItems = $dom->getElementsByTagName('li');

        $viewingArray = array();
        foreach($viewingItems as $key => $item) {
            if($key > 0) {
                $viewingArray[] = $item->textContent;
            }
        }

        $viewingStartDatetime =  $viewingArray[0];
        $viewingEndDatetime =  $viewingArray[count($viewingArray) -1];

//        echo $viewingStartDatetime;
//        echo '<br>';
        $exViewingStart1 = explode(',', $viewingStartDatetime);
        $exViewingStart2 = explode('|', $exViewingStart1[1]);

//        echo $exViewingStart2[0];
//        echo '<br>';
//        echo $exViewingStart2[1];
        $exViewingStart3 = explode('-', $exViewingStart2[1]);
//        echo $exViewingStart3[0];
//        echo '<br>';
        $parsedViewingStartDatetime = $exViewingStart2[0].' '.$exViewingStart3[0].' BST';

        $viewingStartDatetime = strtotime($parsedViewingStartDatetime);
//        echo 'Viewing StartTime: '.$viewingStartDatetime;
//        echo '<br>';

        $exViewingEnd1 = explode(',', $viewingEndDatetime);
        $exViewingEnd2 = explode('|', $exViewingEnd1[1]);
        $exViewingEnd3 = explode('-', $exViewingEnd2[1]);

        $parsedViewingEndDatetime = $exViewingEnd2[0].' '.$exViewingEnd3[1];
        $viewingEndDatetime = strtotime($parsedViewingEndDatetime);

//        echo '<br>';
//        echo 'Viewing EndTime'.$viewingEndDatetime;
//        echo '<br>';

//        dd($viewingArray);

        return array(
            'location' => $location,
            'datetime' => array(
                'start' => $viewingStartDatetime,
                'end' => $viewingEndDatetime
            ),
        );

    }

    private function getLotInfo($dom)
    {
//        echo $dom->textContent;
        $scriptBlock = $dom->getElementsByTagName('script');

        foreach($scriptBlock as $key => $script){
            $readScript = $script->textContent;
            if(strpos($readScript, 'ECAT.lot')) {
                $foundScript = $readScript;
            }
//            echo '<br>';
        }
//        echo $foundScript;

        $exScript = explode('ECAT.', $foundScript);

//        dd($exScript);

        $rawLots = array();
        foreach($exScript as $key => $item) {

            if(strpos($item, 'lotItemId')) {
//                echo $item;
//                echo '<br>';
//                echo '<br>';
                $exItem = explode('=', $item);

                $parsedItem = '';

                foreach($exItem as $k => $i) {
                    if($k > 0) {
                        if($parsedItem != '') $parsedItem .= '=';
                        $parsedItem .= $i;
                    }
                }

                $item = substr($parsedItem, 1, -2);

//                echo $item;
//                echo '<br>';
//                echo '<br>';

                $item = str_getcsv($item, ',', "'");

//                dd($item);

                $rawLots[] = $item;
            }
        }

//        echo '<pre>';
//        print_r($rawLots);

        $lots = array();
        foreach($rawLots as $key => $item) {
            $lot = array();
//            dd($item);
            foreach($item as $nKey => $node) {
                $exNode = explode(':', $node);

                if(count($exNode) > 1) {
                    $lot[$exNode[0]] = $exNode[1];
                } else {
                    $lot[$exNode[0]] = '';
                }
            }
            $lots[] = $lot;
        }

        // Useful Item: id, image, lowEst, highEst, salePrice, condRep (url)

        return $lots;

//        exit;

    }

    public function getLotContentByLang($url, $intSaleID, $lang, $number)
    {
        set_time_limit(60000);

//        echo $url;

        $cSession = curl_init();

        curl_setopt($cSession,CURLOPT_URL,$url);
        curl_setopt($cSession, CURLOPT_HEADER, 0);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,1);

        $headers = [
            'Accept: application/json, text/javascript, */*; q=0.01',
//            'Accept-Encoding: gzip, deflate',
//            'Accept-Language: zh-CN,zh;q=0.8',
            'Connection: keep-alive',
            'Content-Length: 0',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Host: www.sothebys.com',
            'Origin: http://www.sothebys.com',
            'Referer: '.$url, //Your referrer address
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'X-MicrosoftAjax: Delta=true',
            'X-Requested-With: XMLHttpRequest',
        ];

        curl_setopt($cSession,CURLOPT_HTTPHEADER, $headers);

        $content=curl_exec($cSession);

//        echo $content;
//
//         exit;

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/'.$lang.'/';

        Storage::disk('local')->put($storePath . $number . '.html', $content);

    }

    public function uploadS3($intSaleID)
    {
        set_time_limit(60000);

        $intSaleID = trim($intSaleID);

        $path = 'spider/sothebys/sale/'.$intSaleID.'/'.$intSaleID.'.json';

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

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->pushS3 = true;
        $sale->save();

        return redirect()->route('backend.auction.sothebys.index');

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

        $path = 'spider/sothebys/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

//        dd($saleArray);

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'sothebys.index'),
            'saleArray' => $saleArray,
            'intSaleID' => $intSaleID,
        );

//        dd($saleArray);

        return view('backend.auctions.crawler.sothebys.captureItemList', $data);

    }


    public function import(Request $request, $intSaleID)
    {
        $intSaleID = trim($intSaleID);
        $auctionSeriesID = trim($request->auction_series_id);
        $slug = trim($request->slug);

//        echo $seriesID.'<br>';
//        echo $slug.'<br>';

        $saleArray = $this->getSaleArray($intSaleID, 'sothebys');

//        dd($saleArray);

        // Get Series Info
        $series = App\AuctionSeries::find($auctionSeriesID);
//        $seriesDetails = $series->details();

        $house = $series->house;

        if(count($series) == 0) exit;

        $saleSlug = $series->slug.'-'.$slug;

        // insert auction_sales
        // slug, source_image_path, image_path, number, total_lots, start_date, end_date, auction_series_id
        $sale = New App\AuctionSale;

        $sale->slug = $saleSlug;
        $sale->source_image_path = $saleArray['sale']['source_image_path'];
        $sale->image_path = $saleArray['sale']['stored_image_path'];
        $sale->number = $intSaleID;
        $sale->total_lots = count($saleArray['lots']);
        $sale->start_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']);
        $sale->end_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']);
        $sale->auction_series_id = $auctionSeriesID;

        $sale->save();

        $saleID = $sale->id;

        // insert auction_sale_details
        // type, title, country, location, lang, auction_sale_id
        $supported_languages = config('app.supported_languages');
        // sale detail type sale
        foreach($supported_languages as $lang) {

            $useLang = $lang == 'en' ? 'en' : 'zh';

            $saleDetail = New App\AuctionSaleDetail;
            $saleDetail->type = 'sale';
            $saleDetail->title = $saleArray['sale'][$useLang]['title'];
            $houseDetail = $house->getDetailByLang($lang);
            $saleDetail->country = $houseDetail->country;
            $saleDetail->location = $saleArray['sale'][$useLang]['auction']['location'];
            $saleDetail->lang = $lang;
            $saleDetail->auction_sale_id = $saleID;
            $saleDetail->save();
        }

        if($saleArray['sale']['en']['viewing']['datetime'] != '') {
            // sale detail type viewing
            foreach ($supported_languages as $lang) {

                $useLang = $lang == 'en' ? 'en' : 'zh';

                $saleDetail = New App\AuctionSaleDetail;
                $saleDetail->type = 'viewing';
                $saleDetail->title = $saleArray['sale'][$useLang]['title'];
                $houseDetail = $house->getDetailByLang($lang);
                $saleDetail->country = $houseDetail->country;
                $saleDetail->location = $saleArray['sale'][$useLang]['viewing']['location'];
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
        $saleTime->start_date =  date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']);
        $saleTime->end_date =  date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']);
        $saleTime->auction_sale_id = $saleID;
        $saleTime->save();

        if($saleArray['sale']['en']['viewing']['datetime'] != '') {
            $viewingStartTime = date('Y-m-d H:i:s', $saleArray['sale']['en']['viewing']['datetime']['start']);
            $viewingEndTime = date('Y-m-d H:i:s', $saleArray['sale']['en']['viewing']['datetime']['end']);

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

//            $dimension = $lot['dimension'];

            $item = New App\AuctionItem;
            $item->slug = $slug . '-' . $lot['number'];
            $item->dimension = null;
            $item->number = $lot['number'];
            $item->source_image_path = $lot['source_image_path'];
            $item->image_path = $lot['image_path'];
            $item->image_fit_path = $lot['stored_image_path']['fit'];
            $item->image_large_path = $lot['stored_image_path']['large'];
            $item->image_medium_path = $lot['stored_image_path']['medium'];
            $item->image_small_path = $lot['stored_image_path']['small'];
            $item->currency_code = $house->currency_code;

            $estimate_value_initial = str_replace(',', '', $lot['estimate_initial']);
            $estimate_value_end = str_replace(',', '', $lot['estimate_end']);

            $item->estimate_value_initial = $estimate_value_initial;
            $item->estimate_value_end = $estimate_value_end;
            $item->sorting = $counter;
            $item->status = 'pending';
            $item->auction_sale_id = $saleID;

            $item->save();

            $itemID = $item->id;
            echo '<br>';
            echo $itemID . '<br>';

            // insert auction_item_details
            // title, description, maker, misc, provenance, post_lot_text, lang, auction_item_id
            foreach($supported_languages as $lang) {

                $useLang = $lang == 'en' ? 'en' : 'zh';

                $itemDetail = New App\AuctionItemDetail;
                $itemDetail->title = $lot[$useLang]['title'];
                if(isset($lot[$useLang]['description'])) {
                    $itemDetail->description = $lot[$useLang]['description'];
                } else {
                    $itemDetail->description = '';
                }
                $itemDetail->maker = null;
                if(isset($lot[$useLang]['misc'])) {
                    $itemDetail->misc = $lot[$useLang]['misc'];
                }
                if(isset($lot[$useLang]['provenance'])) {
                    $itemDetail->provenance = $lot[$useLang]['provenance'];
                }
                if(isset($lot[$useLang]['exhibited'])) {
                    $itemDetail->exhibited = $lot[$useLang]['exhibited'];
                }
                $itemDetail->lang = $lang;
                $itemDetail->auction_item_id = $itemID;
                $itemDetail->save();
            }

            $counter += 10;

        }

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->import = true;
        $sale->save();

        // backend.auction.itemList
        return redirect()->route('backend.auction.sothebys.index');

    }

    private function getSaleArray($intSaleID)
    {
        $path = 'spider/sothebys/sale/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        return $saleArray;
    }

    public function getRealizedPrice(Request $request, $intSaleID)
    {

        $intSaleID = trim($intSaleID);
        $url = trim($request->url);

        echo $url;
        echo '<br>';

        $saleArray = $this->getSaleArray($intSaleID, 'sothebys');

//        dd($saleArray);
        $this->getMainContentByLang($url, $intSaleID, 'en');

        $mainContentEN = $this->parseMainContentByLang($intSaleID, 'en');

        $saleArray = array(
            'sale' => array(
                'en' => $mainContentEN,
            )
        );

        foreach($saleArray['sale']['en']['lots'] as $key => $lot) {
            $url = str_replace("'", '', $lot['condRep']);
            $exURL = explode('#', $url);

            $lotNumber = str_replace("'", '', $lot['id']);

            $saleArray['lots']['realizedPrice'][$lotNumber] = str_replace("'", '', $lot['salePrice']);

            /*if($lot['salePrice'] == 0) {
                echo $lotNumber." withdraw";
                echo '<br>';
            } else {
                echo 'Price: '.$lot['salePrice'];
                echo '<br>';
            }*/
        }

        // store auction result
        $auctionResult = json_encode($saleArray);

        $path = 'spider/sothebys/sale/'.$intSaleID.'/auction_result.json';
        Storage::disk('local')->put($path, $auctionResult);

//        dd($saleArray);



        return redirect()->route('backend.auction.sothebys.index');

    }

    public function confirmRealizedPrice($intSaleID)
    {
        $intSaleID = trim($intSaleID);

        $path = 'spider/sothebys/sale/'.$intSaleID.'/auction_result.json';
        $saleArray = Storage::disk('local')->get($path);

        $saleArray = json_decode($saleArray, true);

//        dd($saleArray);

        $sale = App\AuctionSale::where('number', $intSaleID)->first();
        $items = $sale->items;
        foreach($items as $key => $item) {
            $itemNumber = $item->number;

            if(isset($saleArray['lots']['realizedPrice'][$itemNumber])) {
                $item->sold_value = $saleArray['lots']['realizedPrice'][$itemNumber];
                $item->status = 'sold';
            } else {
                $item->status = 'withdraw';
            }

            $item->save();
        }

        $sothebysSale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sothebysSale->status = 1;
        $sothebysSale->save();

        return redirect()->route('backend.auction.sothebys.index');
    }

    public function sorting($intSaleID)
    {
        $sale = App\AuctionSale::where('number', $intSaleID)->first();
        $items = $sale->items;

        foreach($items as $item) {
            $item->sorting = (INT) $item->number;
            $item->save();
        }

        return redirect()->route('backend.auction.sothebys.index');
    }

    public function importSaleIndex()
    {
        $sales = App\SothebysSale::all();

        $data = array(
            'menu' => array('auction', 'crawler', 'sothebys.importSaleIndex'),
            'sales' => $sales
        );

        return view('backend.auctions.crawler.sothebys.sale.importSaleIndex', $data);
    }

    public function uploadSaleFile(Request $request)
    {
        $file = $request->file('upload_file');
        $auctionSeriesID = $request->auction_series_id;

        $store_path = base_path().'/storage/app/spider/sothebys/import/uploads/';
//        exit;

        $file->move($store_path, $file->getClientOriginalName());

    }

    public function createGZipFile($intSaleID)
    {
        $command = 'set PATH=%PATH%;C:\Program Files\7-Zip';

//        exec($command);

        $filePath = base_path().'/storage/app/spider/sothebys/sale/'.$intSaleID;
        $tarExportPath = base_path().'/storage/app/spider/sothebys/export/'.$intSaleID.'.tar';
        $gzExportPath = base_path().'/storage/app/spider/sothebys/export/'.$intSaleID.'.tar.gz';

        echo $filePath;
        echo '<br>';
        echo $tarExportPath;
        echo '<br>';

        // create tar file
        $command = '"C:\Program Files\7-Zip\7z.exe" a -ttar '.$tarExportPath.' '.$filePath;

        echo $command;
        echo '<br>';

//        $command = 'dir';

        echo exec($command);
        echo '<br>';

//        echo $return;

//        echo '<pre>';
//        print_r($output);

//        exit;

        $command = '"C:\Program Files\7-Zip\7z.exe" a -tgzip '.$gzExportPath.' '.$tarExportPath;
        echo exec($command);
        echo '<br>';

//        return true;
    }

}
