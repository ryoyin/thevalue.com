<?php

namespace App\Http\Controllers\Backend\Crawler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\Facades\Image;

use App\Http\Controllers\Controller;
use App;

// Run in tinker
// php artisan tinker
// $controller = app()->make('App\Http\Controllers\Backend\Crawler\SothebysController');
// app()->call([$controller, 'crawler']);
// app()->call([$controller, 'confirmRealizedPrice']);

// app()->call([$controller, 'confirmRealizedPrice'], ['intSaleID' => 'l17054']);

class SothebysController extends Controller
{

    public function index()
    {
        $locale = App::getLocale();

        $sales = App\SothebysSale::orderBy('id', 'desc')->get();
        $importList = App\SothebysImportList::where('status', 0)->get();

        $data = array(
            'locale' => $locale,
            'menu' => array('auction', 'crawler', 'sothebys.index'),
            'sales' => $sales,
            'importList' => $importList
        );

        return view('backend.auctions.crawler.sothebys.index', $data);
    }

    public function importURL(Request $request)
    {
        $url = trim($request->url);

        $import = New App\SothebysImportList;
        $import->url = $url;
        $import->save();

        return redirect()->route('backend.auction.sothebys.index');
    }

    public function deleteImportURL($id)
    {
        $id = trim($id);
        $import = App\SothebysImportList::find($id);
        $import->delete();

        return redirect()->route('backend.auction.sothebys.index');
    }

    public function crawler()
    {
//        $url = trim($request->url);

//        echo $url;

//        $url = 'http://www.sothebys.com/en/auctions/2017/qing-dynasty-jade-carvings-from-hong-kong-collection-hk0771.html';

        $urls = App\SothebysImportList::where('status', 0)->get();

        foreach($urls as $url) {

            echo "Get Sale By URL<br>\n";
            $intSaleID = $this->getSaleByURL($url->url);

            echo "Download Data<br>\n";
            $downloadDataResult = $this->downloadData($intSaleID);

            echo "Parse Items<br>\n";
            $parseItemsResult = $this->parseItems($intSaleID);

            echo "Download Images<br>\n";
            $downloadImagesResult = $this->downloadImages($intSaleID);

            echo "Resize<br>\n";
            $resizeResult = $this->resize($intSaleID);

            echo "UploadS3<br>\n";
            $uploadS3Result = $this->uploadS3($intSaleID);

            $url->status = 1;

            $url->save();

        }

        return true;

//        return redirect()->route('backend.auction.sothebys.index');

    }

    public function getSaleByURL($url)
    {

        set_time_limit(60000);

        echo "Getting content from: ".$url;
        echo "<br>\r\n";

        $filename = str_replace('.html', '', basename($url));

        $exFilename = explode('-', $filename);
        $intSaleID = $exFilename[count($exFilename) -1];

        echo "Int Sale ID: ".$intSaleID;

        echo "<br>\r\n";

//        exit;

        echo "try: ".$url;
        echo "<br>\r\n";

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
        echo "<br>\r\n";

        return $intSaleID;

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

            if(str_replace("'", '', $lot['salePrice'] > 0)) {
                $salesArray['db']['result'] = true;
            }  else {
                $salesArray['db']['result'] = false;
            }
        }

        foreach($salesArray['lots'] as $lot) {

//            http://www.sothebys.com/en/auctions/ecatalogue/2017/treasures-l17303/lot.1.html
//            http://www.sothebys.com/zh/auctions/ecatalogue/lot.1.html/2017/treasures-l17303

            $url = $lot['url'];

            echo "Downloading: ".$url;
            echo "<br>\r\n";

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
        $sale->start_date = date('Y-m-d H:i:s', $salesArray['sale']['en']['auction']['datetime']['start_datetime']);
        $sale->end_date = date('Y-m-d H:i:s', $salesArray['sale']['en']['auction']['datetime']['end_datetime']);
        $sale->save();

//        return redirect()->route('backend.auction.sothebys.index');

        return true;

    }

    public function parseItems($intSaleID)
    {
        ini_set('memory_limit','1024M');

        $internalErrors = libxml_use_internal_errors(true);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

//        dd($saleArray);

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

//        return redirect()->route('backend.auction.sothebys.index');

        return true;

    }

    public function downloadImages($intSaleID)
    {
        set_time_limit(60000);

        $storePath = 'spider/sothebys/sale/' . $intSaleID . '/' . $intSaleID . '.json';
        $json = Storage::disk('local')->get($storePath);
        $saleArray = json_decode($json, true);

//        dd($saleArray);
        $salePath = 'images/auctions/sothebys/'.$intSaleID.'/';

        // Get Sale Image
//        $saleSourceImagePath = 'http://www.sothebys.com'.$request->sale_image_path;
        $saleSourceImagePath = 'http://www.sothebys.com'.$saleArray['sale']['en']['image_path'];

//        $saleImageName  = basename($request->sale_image_path);
//        $exSaleImageName = explode('.', $saleImageName);
//        $extension = $exSaleImageName[count($exSaleImageName) -1];
        $extension = 'jpg';

        $saleFilename = $intSaleID.'_sale_image.'.$extension;

        $saleImagePath = $this->getImageFromUrl($salePath, $saleSourceImagePath, $saleFilename);

        $saleArray['sale']['source_image_path'] = $saleSourceImagePath;
        $saleArray['sale']['image_path'] = $saleImagePath;

        File::copy(base_path().'/storage/app/'.$saleImagePath, base_path().'/storage/app/spider/sothebys/sale/'.$intSaleID.'/'.$intSaleID.'_sale_image.'.$extension);

//        exit;

        // - Sale Image

        // Get Lot Image
        foreach($saleArray['lots'] as $key => $lot) {

//            print_r($lot);

            $lotImagePath = $lot['source_image_path'];

            $lotImagePath = str_replace(" ", "%20", $lotImagePath);

            $lotFilename = $lot['number'].'.jpg';

            echo "source: ".$lotImagePath;
            echo "<br>\r\n";
            echo "search: ".base_path().'/storage/app/'.$salePath.$lotFilename;
            echo "<br>\r\n";

            if(!file_exists(base_path().'/storage/app/'.$salePath.$lotFilename)) {
                $lotImagePath = $this->getImageFromUrl($salePath, $lotImagePath, $lotFilename);
                echo 'file not exist: '.$lotImagePath;
                echo "<br>\r\n";
            } else {
                $lotImagePath = $salePath.$lotFilename;
                echo 'file exist: '.$salePath.$lotFilename;
                echo "<br>\r\n";
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

//        return redirect()->route('backend.auction.sothebys.index');

        return true;

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

        $saleFilename = $intSaleID.'_sale_image.jpg';

        echo $saleFilename."<br>\r\n";

        $newPath = $fullSalePath.'/'.$saleFilename;
        $img->save($newPath);

        $saleArray['sale']['stored_image_path'] = $salePath.$saleFilename;

        foreach($saleArray['lots'] as $key => $lot) {
            echo $lot['number']."<br>\r\n";
            $lotImage = $this->imgResize($lot['image_path'], $fullSalePath, $lot['number']);
            $saleArray['lots'][$key]['stored_image_path'] = $lotImage;
        }

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->resize = true;
        $sale->save();

        /*$saleArray['db'] = array(
            'url' => $sale->url,
            'start_date' => $sale->start_date,
            'end_date' => $sale->end_date,
            'title' => $sale->title,
            'int_sale_id' => $sale->int_sale_id
        );*/

        $saleArray['db']['url'] = $sale->url;
        $saleArray['db']['start_date'] = $sale->start_date;
        $saleArray['db']['end_date'] = $sale->end_date;
        $saleArray['db']['title'] = $sale->title;
        $saleArray['db']['int_sale_id'] = $sale->int_sale_id;

        $saleArray = json_encode($saleArray);
        Storage::disk('local')->put($storePath, $saleArray);

        $this->createGZipFile($intSaleID);

        return true;

//        return redirect()->route('backend.auction.sothebys.index');

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
//        echo "<br>\r\n";
//

        echo 'resize: '.$resizePath;
        echo "<br>\r\n";

        $img = Image::make(base_path().'/'.'storage/app/'.$file);

        $newPath = $resizePath.$lotNumber.'-'.$width.'.jpg';

        echo $newPath;
        echo "<br>\r\n";

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
//        echo $storePath;
//        echo "<br>\r\n";
//        echo $link;
//        echo "<br>\r\n";
//        echo $filename;
//        echo "<br>\r\n";
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
//        exit;

        curl_close($ch);

        Storage::disk('local')->put($image_path, $image);

        return $image_path;
    }

    private function getLotDetailsByLang($intSaleID, $number, $lang, $lot)
    {
        echo $number;
        echo "<br>\r\n";

        // get raw html content
        $storePath = 'spider/sothebys/sale/'.$intSaleID.'/'.$lang.'/';
        $path = $storePath.$number.'.html';
        $html = Storage::disk('local')->get($path);

        if(trim($html) == '') return false;

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
                $provenance = str_replace(";", ";<br>\r\n", $provenance);
                $lot[$lang]['provenance'] = $provenance;
            }

            if($key == 1) {
                $exhibited = $item->textContent;
                $exhibited = str_replace(";", ";<br>\r\n", $exhibited);
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

        $dom->loadHTML($html);

        $contentArray['image_path'] = $this->getSaleImage($dom);

        // Get Lot Info
        $dom->loadHTML($html); //resset load html
        $contentArray['lots'] = $this->getLotInfo($dom);

//        dd($contentArray);

        return $contentArray;

    }

    private function getSaleImage($dom)
    {
//        echo $dom->saveHTML();
//
//        exit;
        // get sale photo - eventdetail-left
        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'eventdetail-left')]");
//        $eventBlock = $node->item(0)->textContent;
        $eventBlock = $dom->saveHTML($node->item(0));
        $dom->loadHTML($eventBlock);
        $imgBlock = $dom->getElementsByTagName('img');

//        echo $imgBlock[0];

        $imgSrc = $imgBlock[0]->getAttribute('src');

        $imgSrc = str_replace('/original', '', $imgSrc);

        return $imgSrc;
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

        foreach($timeItems as $key => $item) {

            if($key == 0) {
                if(strpos($item->textContent, '-')) {
                    $exTime = explode('-', $item->textContent);
                    $start_date = trim($exTime[0]);
                    $end_date = trim($exTime[1]);
                } else {
                    $start_date = trim($item->textContent);
                    $end_date = $start_date;
                }
            } elseif($key==1) {
                $start_time = trim($item->textContent);
                $end_time = $start_time;
            }
        }

        $start_datetime = $start_date.' '.$start_time;
        $end_datetime = $end_date.' '.$end_time;

        $start_datetime = strtotime($start_datetime);
        $end_datetime = strtotime($end_datetime);

        $timeArray = array(
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime
        );

        // dd($timeArray);

        return $timeArray;
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
//        echo "test";

        $finder = new \DomXPath($dom);
        $node = $finder->query("//*[contains(@class, 'eventdetail-times')]");

        if($node->length == 0) return false;

        $content = $dom->saveHTML($node->item(0));

        $locationBlock = $node->item(0)->textContent;

//        echo $locationBlock;
//        exit;

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
//        echo "<br>\r\n";

        $dom->loadHTML($content);

        $viewingItems = $dom->getElementsByTagName('li');

        $viewingArray = array();
        foreach($viewingItems as $key => $item) {
            if($key > 0) {
                if(preg_match('/\n|\r/',$item->textContent)){
                    break;
                }
                $viewingArray[] = $item->textContent;
            }
        }

//        dd($viewingArray);

        $viewingStartDatetime =  $viewingArray[0];
        $viewingEndDatetime =  $viewingArray[count($viewingArray) -1];

//        echo $viewingStartDatetime;
//        echo "<br>\r\n";

        // get timezone
        $exSpaceViewingTime = explode(' ', $viewingStartDatetime);
        $timezone = $exSpaceViewingTime[count($exSpaceViewingTime)-1];

        $exViewingStart1 = explode(',', $viewingStartDatetime);
        $exViewingStart2 = explode('|', $exViewingStart1[1]);

//        echo $exViewingStart2[0];
//        echo "<br>\r\n";
//        echo $exViewingStart2[1];
        $exViewingStart3 = explode('-', $exViewingStart2[1]);

        switch($timezone) {
            case 'HKT':
                $default_timezone = 'Asia/Hong_Kong';
                break;
            case 'BST':
                $default_timezone = 'Europe/London';
                break;
            case 'EDT':
                $default_timezone = 'America/New_York';
                break;
        }

        date_default_timezone_set($default_timezone);
//        echo $exViewingStart3[0];
//        echo "<br>\r\n";
//        $parsedViewingStartDatetime = $exViewingStart2[0].' '.$exViewingStart3[0].' BST';
        $parsedViewingStartDatetime = $exViewingStart2[0].' '.$exViewingStart3[0];
//        echo $parsedViewingStartDatetime."<br>\n";

        $viewingStartDatetime = strtotime($parsedViewingStartDatetime);
//        echo "Viewing StartDateTime: ".$viewingStartDatetime."<br>\n";
//        echo 'Viewing EndDateTime   : '.$viewingEndDatetime."<br>\n";
//        echo "<br>\r\n";
//        exit;

        $exViewingEnd1 = explode(',', $viewingEndDatetime);
        $exViewingEnd2 = explode('|', $exViewingEnd1[1]);
        $exViewingEnd3 = explode('-', $exViewingEnd2[1]);

        $parsedViewingEndDatetime = $exViewingEnd2[0].' '.$exViewingEnd3[1];
        $viewingEndDatetime = strtotime($parsedViewingEndDatetime);

//        echo "<br>\r\n";
//        echo 'Viewing EndTime'.$viewingEndDatetime;
//        echo "<br>\r\n";

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
//            echo "<br>\r\n";
        }
//        echo $foundScript;

        $exScript = explode('ECAT.', $foundScript);

//        dd($exScript);

        $rawLots = array();
        foreach($exScript as $key => $item) {

            if(strpos($item, 'lotItemId')) {
//                echo $item;
//                echo "<br>\r\n";
//                echo "<br>\r\n";
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
//                echo "<br>\r\n";
//                echo "<br>\r\n";

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

//        dd($lots);

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

        $json = Storage::disk('local')->get($path);

        $saleArray = json_decode($json, true);

        $baseDirectory = base_path().'/public';

        foreach($saleArray['lots'] as $lot) {

            $this->pushS3($baseDirectory, $lot['stored_image_path']['fit']);
            $this->pushS3($baseDirectory, $lot['stored_image_path']['large']);
            $this->pushS3($baseDirectory, $lot['stored_image_path']['medium']);
            $this->pushS3($baseDirectory, $lot['stored_image_path']['small']);

        }

        $saleImagePath = $saleArray['sale']['image_path'];
        $this->pushS3($baseDirectory, $saleImagePath);

        Storage::disk('local')->put($path, $json);

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->pushS3 = true;
        $sale->save();

        return true;

//        return redirect()->route('backend.auction.sothebys.index');

    }

    public function pushS3($baseDirectory, $filePath)
    {
        $s3 = \Storage::disk('s3');
        $localPath = $baseDirectory.'/'.$filePath;

        echo $localPath."<br>\r\n";

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
        set_time_limit(60000);

        $intSaleID = trim($intSaleID);
        $auctionSeriesID = trim($request->auction_series_id);
        $slug = trim($request->slug);

//        echo $seriesID."<br>\r\n";
//        echo $slug."<br>\r\n";

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

        // dd($saleArray['sale']['en']['auction']['datetime']);

        $sale->start_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['start_datetime']);
        $sale->end_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['end_datetime']);
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
        $saleTime->start_date =  date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['start_datetime']);
        $saleTime->end_date =  date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['start_datetime']);
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
            echo "<br>\r\n";
            echo $itemID . "<br>\r\n";

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
        echo "<br>\r\n";

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

//            echo $lot['isSold'];
//
//             dd($lot);

            if($lot['isSold'] == 'false') {
                $saleArray['lots']['realizedPrice'][$lotNumber] = 0;
            } else {
                $saleArray['lots']['realizedPrice'][$lotNumber] = str_replace("'", '', $lot['salePrice']);
            }

            /*if($lot['salePrice'] == 0) {
                echo $lotNumber." withdraw";
                echo "<br>\r\n";
            } else {
                echo 'Price: '.$lot['salePrice'];
                echo "<br>\r\n";
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
                if($saleArray['lots']['realizedPrice'][$itemNumber] == 0) {
                    $item->sold_value = null;
//                    echo 'no show';
                    $item->status = 'noshow';
                } else {
//                    echo 'sold';
                    $item->sold_value = $saleArray['lots']['realizedPrice'][$itemNumber];
                    $item->status = 'sold';
                }
            } else {

//                echo 'no show';
                $item->sold_value = null;
                $item->status = 'noshow';
            }

//            dd($saleArray);

//            exit;

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
//        $sales = App\SothebysSale::all();

        // Get uploaded Sothebys Sale
        $store_path = base_path().'/storage/app/spider/sothebys/import/uploads/';
        $list = scandir($store_path);

        // Get Directory
        $importSaleArray = $this->getDirectoryList($store_path, $list);

        $storagePath = 'spider/sothebys/import/uploads/';
        $salesArray = array();
        foreach($importSaleArray as $key => $node) {
            $json = Storage::get($storagePath.$node.'/'.$node.'/'.$node.'.json');
            $salesArray[$node] = json_decode($json, true);
        }

        $data = array(
            'menu' => array('auction', 'crawler', 'sothebys.importSaleIndex'),
            'salesArray' => $salesArray,
        );

        return view('backend.auctions.crawler.sothebys.sale.importSaleIndex', $data);
    }

    public function uploadSaleFile(Request $request)
    {
        set_time_limit(60000);

        $file = $request->file('upload_file');

        // get uploaded filename
        $filename = $file->getClientOriginalName();

        // get int sale id
        $intSaleID = str_replace('.tar.gz', '', $filename);

        // define store path
        $store_path = base_path().'/storage/app/spider/sothebys/import/uploads/'.$intSaleID.'/';

        // move uploaded file to store path
        $file->move($store_path, $filename);

        // define moved path
        $filePath = $store_path.$filename;

        // extract tar.gz file

        if($_SERVER['SERVER_NAME'] == 'localhost') { // set 7zip path for window
            $zipPath = '"C:\Program Files\7-Zip\7z.exe"';
        } else { // 7zip for linux
            $zipPath = '/usr/bin/7z';
        }

        // ungzip
        $command = $zipPath.' x '.$filePath.' -o'.$store_path;
        exec($command);

        //untar
        $filePath = str_replace('.gz', '', $filePath); // remove .gz from {intSaleID}tar.gz
        $command = $zipPath.' x '.$filePath.' -o'.$store_path;
        exec($command);

        return redirect()->route('backend.auction.sothebys.sale.importSaleIndex');

    }

    public function createGZipFile($intSaleID)
    {

        // define path
        $filePath = base_path().'/storage/app/spider/sothebys/sale/'.$intSaleID;
        $tarExportPath = base_path().'/storage/app/spider/sothebys/export/'.$intSaleID.'.tar';
        $gzExportPath = base_path().'/storage/app/spider/sothebys/export/'.$intSaleID.'.tar.gz';

        // create tar file
        $command = '"C:\Program Files\7-Zip\7z.exe" a -ttar '.$tarExportPath.' '.$filePath;
        exec($command);

        // create tar.gz file
        $command = '"C:\Program Files\7-Zip\7z.exe" a -tgzip '.$gzExportPath.' '.$tarExportPath;
        exec($command);

        return true;
    }

    private function getDirectoryList($store_path, $list)
    {
        $importSaleArray = array();
        foreach($list as $key => $node) {

            if($node == '.' || $node == '..') continue;

            $testPath = $store_path.$node;
            if(is_dir($testPath)) {
                $importSaleArray[] = $node;
            }
        }

        return $importSaleArray;
    }

    public function importSaleFile(Request $request, $intSaleID)
    {
        set_time_limit(60000);
        
        $intSaleID = trim($intSaleID);
        $auctionSeriesID = trim($request->auction_series_id);
        $slug = trim($request->slug);

        $storePath = base_path().'/storage/app/spider/sothebys/import/uploads/'.$intSaleID;
        $storagePath = 'spider/sothebys/import/uploads/'.$intSaleID;

        $jsonFilePath = $storagePath.'/'.$intSaleID.'/'.$intSaleID.'.json';
        $json = Storage::disk('local')->get($jsonFilePath);

        $saleArray = json_decode($json, true);

        $importResult = $this->importSale($intSaleID, $auctionSeriesID, $slug, $saleArray);

//        $saleImagePath = $storePath.'/'.$intSaleID.'/sale_image.jpg';
//        $targetPath = base_path().'/public/images/auctions/sothebys/'.$intSaleID.'/'.$intSaleID.'sale_image.jpg';
//        File::copy($saleImagePath, $targetPath);

        Storage::deleteDirectory($storagePath);

        return redirect()->route('backend.auction.sothebys.sale.importSaleIndex');

    }

    private function importSale($intSaleID, $auctionSeriesID, $slug, $saleArray)
    {
        // dd($saleArray);

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
        $sale->image_pushS3 = 1;
        $sale->total_lots = count($saleArray['lots']);
        $sale->start_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['start_datetime']);
        $sale->end_date = date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['end_datetime']);
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
        $saleTime->start_date =  date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['start_datetime']);
        $saleTime->end_date =  date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']['end_datetime']);
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

            if($saleArray['db']['result']) {
                $item->sold_value = $lot['realized_price'];
                if($lot['realized_price'] == 0) {
                    $item->status = 'withdraw';
                } else {
                    $item->status = 'sold';
                }
            } else {
                $item->status = 'pending';
            }

            $item->sorting = $counter;

            $item->auction_sale_id = $saleID;

            $item->save();

            $itemID = $item->id;
            echo "<br>\r\n";
            echo $itemID . "<br>\r\n";

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

/*        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->import = true;
        $sale->save();*/

        $sale = New App\SothebysSale;
        $sale->url = $saleArray['db']['url'];
        $sale->start_date = $saleArray['db']['start_date'];
        $sale->end_date = $saleArray['db']['end_date'];
        $sale->title = $saleArray['db']['title'];
        $sale->int_sale_id = $saleArray['db']['int_sale_id'];
        $sale->html = true;
        $sale->json = true;
        $sale->image = true;
        $sale->resize = true;
        $sale->pushS3 = true;
        $sale->import = true;
        $sale->status = true;

        $sale->save();

        // backend.auction.itemList
//        return redirect()->route('backend.auction.sothebys.index');

    }

    public function crawlerRemove($intSaleID)
    {
        $intSaleID = trim($intSaleID);
//        echo $intSaleID;

        $sale = App\SothebysSale::where('int_sale_id', $intSaleID)->first();
        $sale->delete();

        return redirect()->route('backend.auction.sothebys.index');
    }

}
