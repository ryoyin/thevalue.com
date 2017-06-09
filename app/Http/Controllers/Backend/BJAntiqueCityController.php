<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use App\Http\Controllers\Controller;
use App;

// php artisan tinker
// $controller = app()->make('App\Http\Controllers\Backend\BJAntiqueCityController');
// app()->call([$controller, 'imgResize'], []);

class BJAntiqueCityController extends Controller
{
    public function saleInjection()
    {
        // Create Auction House
        $house = New App\AuctionHouse;
        $house->slug = 'beijing-antique-city';
        $house->image_path = 'images/company_logo/beijing-antique-city-logo.jpg';
        $house->tel_no = '';
        $house->fax_no = '';
        $house->email = '';
        $house->status = 1;
        $house->save();

        $houseID = $house->id;

        // Create Auction House Details
        $houseDetails = New App\AuctionHouseDetail;
        $houseDetails->name = 'Beijing Antique City';
        $houseDetails->address = '';
        $houseDetails->lang = 'en';
        $houseDetails->office_hour = '';
        $houseDetails->auction_house_id = $houseID;
        $houseDetails->save();

        $houseDetails = New App\AuctionHouseDetail;
        $houseDetails->name = '北京古玩城';
        $houseDetails->address = '';
        $houseDetails->lang = 'trad';
        $houseDetails->office_hour = '';
        $houseDetails->auction_house_id = $houseID;
        $houseDetails->save();

        $houseDetails = New App\AuctionHouseDetail;
        $houseDetails->name = '北京古玩城';
        $houseDetails->address = '';
        $houseDetails->lang = 'sim';
        $houseDetails->office_hour = '';
        $houseDetails->auction_house_id = $houseID;
        $houseDetails->save();

        // Create Auction Series
        $series = New App\AuctionSeries;
        $series->slug = 'beijing-antique-city-international-2017-spring-auction';
        $series->total_lots = 0;
        $series->start_date = '2017-06-19';
        $series->end_date = '2017-06-20';
        $series->auction_house_id = $houseID;
        $series->save();

        $seriesID = $series->id;

        $seriesDetail = New App\AuctionSeriesDetail;
        $seriesDetail->name = 'Beijing Antique City International 2017 Spring Auctions';
        $seriesDetail->country = 'China';
        $seriesDetail->location = '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳';
        $seriesDetail->lang = 'en';
        $seriesDetail->auction_series_id = $seriesID;
        $seriesDetail->save();

        $seriesDetail = New App\AuctionSeriesDetail;
        $seriesDetail->name = '北京古玩城國際拍賣2017春季拍賣會';
        $seriesDetail->country = '中國';
        $seriesDetail->location = '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳';
        $seriesDetail->lang = 'trad';
        $seriesDetail->auction_series_id = $seriesID;
        $seriesDetail->save();

        $seriesDetail = New App\AuctionSeriesDetail;
        $seriesDetail->name = '北京古玩城国际拍卖2017春季拍卖会';
        $seriesDetail->country = '中国';
        $seriesDetail->location = '北京市朝阳区东三环南路21号北京古玩城A座5层多功能厅';
        $seriesDetail->lang = 'sim';
        $seriesDetail->auction_series_id = $seriesID;
        $seriesDetail->save();

        $saleRawArray = array();
        //slug	source_image_path	number	total_lots	start_date	end_date
        $saleRawArray[] = array('slug' => 'beijing-antique-2017-spring-jade-wares',	'source_image_path' => 'images/company_logo/beijing-antique-2017-spring-jade.jpg', 'number' => '201706192001', 'start_date' => '2017-06-19', 'end_date' => '2017-06-20');
        $saleRawArray[] = array('slug' => 'beijing-antique-2017-spring-furniture',	'source_image_path' => 'images/company_logo/beijing-antique-2017-spring-furniture.jpg', 'number' => '201706192002', 'start_date' => '2017-06-19', 'end_date' => '2017-06-20');
        $saleRawArray[] = array('slug' => 'beijing-antique-2017-spring-ancient-chinese-paintings',	'source_image_path' => 'images/company_logo/beijing-antique-2017-spring-book.jpg', 'number' => '201706192003', 'start_date' => '2017-06-19', 'end_date' => '2017-06-20');
        $saleRawArray[] = array('slug' => 'beijing-antique-2017-spring-ancient-buddha-status',	'source_image_path' => 'images/company_logo/beijing-antique-2017-spring-status.jpg', 'number' => '201706192004', 'start_date' => '2017-06-19', 'end_date' => '2017-06-20');
        $saleRawArray[] = array('slug' => 'beijing-antique-2017-spring-porcelains-and-miscellaneous',	'source_image_path' => 'images/company_logo/beijing-antique-2017-spring-porcelains.jpg', 'number' => '201706192005', 'start_date' => '2017-06-19', 'end_date' => '2017-06-20');

        $saleDetailsRawArray = array(
            array(
                array('type' => 'sale', 'title' => 'Special Auction for Jade Wares in Ancient Times	China', 'country' => 'China', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'en'),
                array('type' => 'sale', 'title' => '古代玉器專場', 'country' => '中國', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'trad'),
                array('type' => 'sale', 'title' => '古代玉器专场', 'country' => '中国', 'location' => '北京市朝阳区东三环南路21号北京古玩城A座5层多功能厅', 'lang' => 'sim'),
            ),
            array(
                array('type' => 'sale', 'title' => 'Special Auction for Furniture in the Ming and Qing Dynasties', 'country' => 'China', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'en'),
                array('type' => 'sale', 'title' => '明清家具專場', 'country' => '中國', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'trad'),
                array('type' => 'sale', 'title' => '明清家具专场', 'country' => '中国', 'location' => '北京市朝阳区东三环南路21号北京古玩城A座5层多功能厅', 'lang' => 'sim'),
            ),
            array(
                array('type' => 'sale', 'title' => 'Ancient Chinese Paintings', 'country' => 'China', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'en'),
                array('type' => 'sale', 'title' => '中國書畫', 'country' => '中國', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'trad'),
                array('type' => 'sale', 'title' => '中国书画', 'country' => '中国', 'location' => '北京市朝阳区东三环南路21号北京古玩城A座5层多功能厅', 'lang' => 'sim'),
            ),
            array(
                array('type' => 'sale', 'title' => 'Auction of Ancient Buddha Status', 'country' => 'China', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'en'),
                array('type' => 'sale', 'title' => '古代佛教造像專場', 'country' => '中國', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'trad'),
                array('type' => 'sale', 'title' => '古代佛教造像专场', 'country' => '中国', 'location' => '北京市朝阳区东三环南路21号北京古玩城A座5层多功能厅', 'lang' => 'sim'),
            ),
            array(
                array('type' => 'sale', 'title' => 'Special Auction for Porcelains and Miscellaneous in the Ming and Qing Dynasties	China', 'country' => 'China', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'en'),
                array('type' => 'sale', 'title' => '明清瓷染專場', 'country' => '中國', 'location' => '北京市朝陽區東三環南路21號北京古玩城A座5層多功能廳', 'lang' => 'trad'),
                array('type' => 'sale', 'title' => '明清瓷染专场', 'country' => '中国', 'location' => '北京市朝阳区东三环南路21号北京古玩城A座5层多功能厅', 'lang' => 'sim'),
            )
        );

        foreach($saleRawArray as $srIndex => $saleRaw) {

            $sale = New App\AuctionSale;

            $sale->slug = $saleRaw['slug'];
            $sale->source_image_path = $saleRaw['source_image_path'];
            $sale->image_path = $saleRaw['source_image_path'];
            $sale->number = $saleRaw['number'];
            $sale->total_lots = 0;
            $sale->start_date = $saleRaw['start_date'];
            $sale->end_date = $saleRaw['end_date'];
            $sale->auction_series_id = $seriesID;
            $sale->save();

            $saleID = $sale->id;

            foreach($saleDetailsRawArray[$srIndex] as $saleDetailsRaw) {
                $saleDetail = New App\AuctionSaleDetail;
                $saleDetail->type = $saleDetailsRaw['type'];
                $saleDetail->title = $saleDetailsRaw['title'];
                $saleDetail->country = $saleDetailsRaw['country'];
                $saleDetail->location = $saleDetailsRaw['location'];
                $saleDetail->lang = $saleDetailsRaw['lang'];
                $saleDetail->auction_sale_id = $saleID;
                $saleDetail->save();
            }

        }

    }

    public function insertItem1()
    {

        $path = 'bjac/';

        // status

        $slug = 'beijing-antique-2017-spring-ancient-buddha-status';

        $sale = App\AuctionSale::where('slug', $slug)->first();

        $file = Storage::disk('local')->get($path.'statue.csv');

        $exCSV = explode("\n", $file);

        foreach($exCSV as $csv) {
            $lot = str_getcsv($csv);

            $newLot = array();
            foreach ($lot as $index => $item) {

                switch ($index) {
                    case 0:
                        $hash = 'number';
                        break;
                    case 1:
                        $hash = 'cn_title';
                        break;
                    case 2:
                        $hash = 'cn_misc';
                        break;
                    case 3:
                        $hash = 'cn_maker';
                        break;
                    case 4:
                        $hash = 'cn_dimension';
                        break;
                    case 5:
                        $hash = 'en_title';
                        break;
                    case 6:
                        $hash = 'en_misc';
                        break;
                    case 7:
                        $hash = 'en_maker';
                        break;
                    case 8:
                        $hash = 'en_dimension';
                        break;
                    case 9:
                        $hash = 'estimate';
                        break;
                    case 10:
                        $hash = 'description';
                        break;
                }

                $newLot[$hash] = $item;

            }

            // create sale item
            $saleItem = New App\AuctionItem;
            // id	slug	dimension	number	source_image_path	currency_code	estimate_value_initial	estimate_value_end	sold_value	status	auction_sale_id
            $saleItem->slug = $slug . '-' . $newLot['number'];
            $saleItem->dimension = $newLot['cn_dimension'];
            $saleItem->number = $newLot['number'];
            $saleItem->source_image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->currency_code = 'RMB';

            if($newLot['estimate'] == '估价待询') {
                $saleItem->estimate_value_initial = null;
                $saleItem->estimate_value_end = null;
            } else {
                $estimate = trim(str_replace('RMB: ', '', $newLot['estimate']));
                $estimate = trim(str_replace(',', '', $estimate));
                $exEstimate = explode('-', $estimate);
                $saleItem->estimate_value_initial = (int) trim($exEstimate[0]);
                $saleItem->estimate_value_end = (int) trim($exEstimate[1]);
            }


            $saleItem->status = 'pending';
            $saleItem->auction_sale_id = $sale->id;
            $saleItem->save();

            $saleID = $saleItem->id;

            // create sale detail
            $saleDetail = New App\AuctionItemDetail;
            // title	desciprtion	maker	misc	lang	auction_item_id
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['description'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'sim';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // title	desciprtion	maker	misc	lang	auction_item_id
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['description'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'trad';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // title	desciprtion	maker	misc	lang	auction_item_id
            $saleDetail->title = $newLot['en_title'];
            $saleDetail->description = $newLot['description'];
            $saleDetail->maker = $newLot['en_maker'];
            $saleDetail->misc = $newLot['en_misc'];
            $saleDetail->lang = 'en';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

//            break;

        }

    }

    public function insertItem2()
    {

        $path = 'bjac/';

        // status

        $slug = 'beijing-antique-2017-spring-furniture';

        $sale = App\AuctionSale::where('slug', $slug)->first();

        $file = Storage::disk('local')->get($path.'furniture.csv');

        $exCSV = explode("\n", $file);

        foreach($exCSV as $csv) {
            $lot = str_getcsv($csv);

            $newLot = array();
            foreach ($lot as $index => $item) {

                // number, cn_title, en_title, cn_maker, en_maker, cn_misc, en_misc, dimension, estimate, en_desc, cn_desc

                switch ($index) {
                    case 0:
                        $hash = 'number';
                        break;
                    case 1:
                        $hash = 'cn_title';
                        break;
                    case 2:
                        $hash = 'en_title';
                        break;
                    case 3:
                        $hash = 'cn_maker';
                        break;
                    case 4:
                        $hash = 'en_maker';
                        break;
                    case 5:
                        $hash = 'cn_misc';
                        break;
                    case 6:
                        $hash = 'en_misc';
                        break;
                    case 7:
                        $hash = 'dimension';
                        break;
                    case 8:
                        $hash = 'estimate';
                        break;
                    case 9:
                        $hash = 'en_desc';
                        break;
                    case 10:
                        $hash = 'cn_desc';
                        break;
                }

                $newLot[$hash] = $item;

            }

//            dd($newLot);

            // create sale item
            $saleItem = New App\AuctionItem;
            // id	slug	dimension	number	source_image_path	currency_code	estimate_value_initial	estimate_value_end	sold_value	status	auction_sale_id
            $saleItem->slug = $slug . '-' . $newLot['number'];
            $saleItem->dimension = $newLot['dimension'];
            $saleItem->number = $newLot['number'];
            $saleItem->source_image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->currency_code = 'RMB';

            if($newLot['estimate'] == '估价待询') {
                $saleItem->estimate_value_initial = null;
                $saleItem->estimate_value_end = null;
            } else {
                $estimate = trim(str_replace('RMB: ', '', $newLot['estimate']));
                $estimate = trim(str_replace(',', '', $estimate));
                $exEstimate = explode('-', $estimate);
                $saleItem->estimate_value_initial = (int) trim($exEstimate[0]);
                $saleItem->estimate_value_end = (int) trim($exEstimate[1]);
            }




            $saleItem->status = 'pending';
            $saleItem->auction_sale_id = $sale->id;
            $saleItem->save();

            $saleID = $saleItem->id;

            // create sale detail
            $saleDetail = New App\AuctionItemDetail;
            // number, cn_title, en_title, cn_maker, en_maker, cn_misc, en_misc, dimension, estimate, en_desc, cn_desc
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['cn_desc'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'sim';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // title	desciprtion	maker	misc	lang	auction_item_id
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['cn_desc'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'trad';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // title	desciprtion	maker	misc	lang	auction_item_id
            $saleDetail->title = $newLot['en_title'];
            $saleDetail->description = $newLot['cn_desc'];
            $saleDetail->maker = $newLot['en_maker'];
            $saleDetail->misc = $newLot['en_misc'];
            $saleDetail->lang = 'en';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

//            break;

        }

    }

    public function insertItem3()
    {

        $path = 'bjac/';

        // status

        $slug = 'beijing-antique-2017-spring-jade-wares';

        $sale = App\AuctionSale::where('slug', $slug)->first();

        $file = Storage::disk('local')->get($path.'jade_2.csv');

        $exCSV = explode("\n", $file);

        foreach($exCSV as $csv) {
            $lot = str_getcsv($csv);

            $newLot = array();
            foreach ($lot as $index => $item) {

                // number	cn_title	en_title	En-misc	dimension	estimate	desc

                switch ($index) {
                    case 0:
                        $hash = 'number';
                        break;
                    case 1:
                        $hash = 'cn_title';
                        break;
                    case 2:
                        $hash = 'en_title';
                        break;
                    case 3:
                        $hash = 'en_misc';
                        break;
                    case 4:
                        $hash = 'dimension';
                        break;
                    case 5:
                        $hash = 'estimate';
                        break;
                    case 6:
                        $hash = 'desc';
                        break;
                }

                $newLot[$hash] = $item;

            }

//            dd($newLot);

            // create sale item
            $saleItem = New App\AuctionItem;
            // number	cn_title	en_title	En-misc	dimension	estimate	desc
            $saleItem->slug = $slug . '-' . $newLot['number'];
            $saleItem->dimension = $newLot['dimension'];
            $saleItem->number = $newLot['number'];
            $saleItem->source_image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->currency_code = 'RMB';

            echo $newLot['estimate'];

            if($newLot['estimate'] == '估价待询') {
                $saleItem->estimate_value_initial = null;
                $saleItem->estimate_value_end = null;
            } else {
                $estimate = trim(str_replace('RMB: ', '', $newLot['estimate']));
                $estimate = trim(str_replace(',', '', $estimate));
                $exEstimate = explode('-', $estimate);
                $saleItem->estimate_value_initial = (int) trim($exEstimate[0]);
                $saleItem->estimate_value_end = (int) trim($exEstimate[1]);
            }

            $saleItem->status = 'pending';
            $saleItem->auction_sale_id = $sale->id;
            $saleItem->save();

            $saleID = $saleItem->id;

            // create sale detail
            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	en_title	En-misc	dimension	estimate	desc
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['desc'];
            $saleDetail->maker = '';
            $exCNTitle = explode(' ', $newLot['cn_title']);
            $saleDetail->misc = $exCNTitle[0];
            $saleDetail->lang = 'sim';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // title	desciprtion	maker	misc	lang	auction_item_id
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['desc'];
            $saleDetail->maker = '';
            $saleDetail->misc = $exCNTitle[0];
            $saleDetail->lang = 'trad';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	en_title	En-misc	dimension	estimate	desc
            $saleDetail->title = $newLot['en_title'];
            $saleDetail->description = $newLot['desc'];
            $saleDetail->maker = '';
            $saleDetail->misc = $newLot['en_misc'];
            $saleDetail->lang = 'en';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

//            break;

        }

    }

    public function insertItem4()
    {

        $path = 'bjac/';

        // status

        $slug = 'beijing-antique-2017-spring-ancient-chinese-paintings';

        $sale = App\AuctionSale::where('slug', $slug)->first();

        $file = Storage::disk('local')->get($path.'bookpicture.csv');

        $exCSV = explode("\n", $file);

        foreach($exCSV as $csv) {
            $lot = str_getcsv($csv);

            $newLot = array();
            foreach ($lot as $index => $item) {

                // number	cn_title	cn_misc	cn_maker	en_title	en_misc	desc	dimension	estimate

                switch ($index) {
                    case 0:
                        $hash = 'number';
                        break;
                    case 1:
                        $hash = 'cn_title';
                        break;
                    case 2:
                        $hash = 'cn_misc';
                        break;
                    case 3:
                        $hash = 'cn_maker';
                        break;
                    case 4:
                        $hash = 'en_title';
                        break;
                    case 5:
                        $hash = 'en_misc';
                        break;
                    case 6:
                        $hash = 'desc';
                        break;
                    case 7:
                        $hash = 'dimension';
                        break;
                    case 8:
                        $hash = 'estimate';
                        break;
                }

                $newLot[$hash] = $item;

            }

//            dd($newLot);

            // create sale item
            $saleItem = New App\AuctionItem;
            // number	cn_title	cn_misc	cn_maker	en_title	en_misc	desc	dimension	estimate
            $saleItem->slug = $slug . '-' . $newLot['number'];
            $saleItem->dimension = $newLot['dimension'];
            $saleItem->number = $newLot['number'];
            $saleItem->source_image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->currency_code = 'RMB';

//            echo $newLot['estimate'];

            if($newLot['estimate'] == 'RMB: 无底价') {
                $saleItem->estimate_value_initial = null;
                $saleItem->estimate_value_end = null;
            } else {
                $estimate = trim(str_replace('RMB: ', '', $newLot['estimate']));
                $estimate = trim(str_replace(',', '', $estimate));
                $exEstimate = explode('-', $estimate);
                $saleItem->estimate_value_initial = (int) trim($exEstimate[0]);
                $saleItem->estimate_value_end = (int) trim($exEstimate[1]);
            }

            $saleItem->status = 'pending';
            $saleItem->auction_sale_id = $sale->id;
            $saleItem->save();

            $saleID = $saleItem->id;

            // create sale detail
            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	cn_misc	cn_maker	en_title	en_misc	desc	dimension	estimate
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['desc'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'sim';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	cn_misc	cn_maker	en_title	en_misc	desc	dimension	estimate
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['desc'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'trad';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	cn_misc	cn_maker	en_title	en_misc	desc	dimension	estimate
            $saleDetail->title = $newLot['en_title'];
            $saleDetail->description = $newLot['desc'];
            $saleDetail->maker = '';
            $saleDetail->misc = $newLot['en_misc'];
            $saleDetail->lang = 'en';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

//            break;

        }

    }

    public function insertItem5()
    {

        $path = 'bjac/';

        // status

        $slug = 'beijing-antique-2017-spring-porcelains-and-miscellaneous';

        $sale = App\AuctionSale::where('slug', $slug)->first();

        $file = Storage::disk('local')->get($path.'porcelains.csv');

        $exCSV = explode("\n", $file);

        foreach($exCSV as $csv) {
            $lot = str_getcsv($csv);

            $newLot = array();
            foreach ($lot as $index => $item) {

                // number	cn_title	cn_misc	cn_maker	cn_desc	en_title	en_misc	en_maker	dimension	estimate

                switch ($index) {
                    case 0:
                        $hash = 'number';
                        break;
                    case 1:
                        $hash = 'cn_title';
                        break;
                    case 2:
                        $hash = 'cn_misc';
                        break;
                    case 3:
                        $hash = 'cn_maker';
                        break;
                    case 4:
                        $hash = 'cn_desc';
                        break;
                    case 5:
                        $hash = 'en_title';
                        break;
                    case 6:
                        $hash = 'en_misc';
                        break;
                    case 7:
                        $hash = 'en_maker';
                        break;
                    case 8:
                        $hash = 'dimension';
                        break;
                    case 9:
                        $hash = 'estimate';
                        break;
                }

                $newLot[$hash] = $item;

            }

//            dd($newLot);

            // create sale item
            $saleItem = New App\AuctionItem;
            // number	cn_title	cn_misc	cn_maker	cn_desc	en_title	en_misc	en_maker	dimension	estimate
            $saleItem->slug = $slug . '-' . $newLot['number'];
            $saleItem->dimension = $newLot['dimension'];
            $saleItem->number = $newLot['number'];
            $saleItem->source_image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->image_path = 'images/sales/beijing-antique-city/' . $slug . '/' . $newLot['number'] . '.jpg';
            $saleItem->currency_code = 'RMB';

//            echo $newLot['estimate'];

            if($newLot['estimate'] == '估价待询') {
                $saleItem->estimate_value_initial = null;
                $saleItem->estimate_value_end = null;
            } else {
                $estimate = trim(str_replace('RMB: ', '', $newLot['estimate']));
                $estimate = trim(str_replace(',', '', $estimate));
                $exEstimate = explode('-', $estimate);
                $saleItem->estimate_value_initial = (int) trim($exEstimate[0]);
                $saleItem->estimate_value_end = (int) trim($exEstimate[1]);
            }

            $saleItem->status = 'pending';
            $saleItem->auction_sale_id = $sale->id;
            $saleItem->save();

            $saleID = $saleItem->id;

            // create sale detail
            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	cn_misc	cn_maker	cn_desc	en_title	en_misc	en_maker	dimension	estimate
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['cn_desc'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->lang = 'sim';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	cn_misc	cn_maker	cn_desc	en_title	en_misc	en_maker	dimension	estimate
            $saleDetail->title = $newLot['cn_title'];
            $saleDetail->description = $newLot['cn_desc'];
            $saleDetail->maker = $newLot['cn_maker'];
            $saleDetail->misc = $newLot['cn_misc'];
            $saleDetail->lang = 'trad';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

            $saleDetail = New App\AuctionItemDetail;
            // number	cn_title	cn_misc	cn_maker	cn_desc	en_title	en_misc	en_maker	dimension	estimate
            $saleDetail->title = $newLot['en_title'];
            $saleDetail->description = $newLot['cn_desc'];
            $saleDetail->maker = $newLot['en_maker'];
            $saleDetail->misc = $newLot['en_misc'];
            $saleDetail->lang = 'en';
            $saleDetail->auction_item_id = $saleID;

            $saleDetail->save();

//            break;

        }

    }

    public function imgResize()
    {
        ini_set('memory_limit', '1024M');

        $items = App\AuctionItem::where('image_medium_path', null)->get();

        foreach($items as $item) {

            $file = $item->image_path;

            echo "item id:".$item->id;
            echo "<br>\n";
//            echo "before: ".$file;
//            echo "<br>\n";

            $exPath = explode("/", $file);
            $newPath = $exPath[0].'/'.$exPath[1].'/'.$exPath[2].'/'.$exPath[3].'/';
//            echo $newPath;
//            echo "<br>\n";

            $fileName = basename($file);
            echo "after: ".$fileName;
            echo "<br>\n";

            $file = $newPath.$fileName;

            echo "after: ".$file;
            echo "<br>\n";
//            exit;

            $item->image_large_path = $this->resizeImage($file, 1140);
            $item->image_medium_path = $this->resizeImage($file, 500);
            $item->image_small_path = $this->resizeImage($file, 150);
            $item->image_fit_path = $this->createFitImage($file, 250);
            $item->save();

//            break;

        }
    }



    private function resizeImage($file, $width)
    {

        echo "new path: ".$file;
        echo "<br>\n";

        $img = Image::make('storage/app/'.$file);

        $root = base_path();
        $filePath = str_replace(".jpg", '', $file).'-'.$width.'.jpg';
        $newPath = $root.'/public/'.$filePath;

        $img->widen($width, function ($constraint) {
            $constraint->upsize();
        })->save($newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $filePath;
    }

    private function createFitImage($file, $width)
    {
        $img = Image::make('storage/app/'.$file);

        $root = base_path();
        $filePath = str_replace(".jpg", '', $file).'-'.$width.'.jpg';
        $newPath = $root.'/public/'.$filePath;

        $img->fit($width)->save($newPath);

//        Storage::disk('local')->put($newPath, $img);

        $img = null;

        return $filePath;
    }

    public function uploadS3()
    {
        $slug = 'beijing-antique-2017-spring-ancient-buddha-status';

        $sale = App\AuctionSale::where('slug', $slug)->first();

        $items = $sale->items;
//        dd($items);
        $baseDirectory = base_path().'/public';

        foreach($items as $item) {

//            if($item->number == '1484') continue;

//            echo $item->image_fit_path."\n";
            $this->pushS3($baseDirectory, $item->image_fit_path);
            $this->pushS3($baseDirectory, $item->image_large_path);
            $this->pushS3($baseDirectory, $item->image_medium_path);
            $this->pushS3($baseDirectory, $item->image_small_path);

        }
    }

    public function uploadS3Indv()
    {
        $items = App\AuctionItem::where('id', '>=', 2840)->get();
        $baseDirectory = base_path().'/public';

        foreach($items as $item) {
            $this->pushS3($baseDirectory, $item->image_fit_path);
            $this->pushS3($baseDirectory, $item->image_large_path);
            $this->pushS3($baseDirectory, $item->image_medium_path);
            $this->pushS3($baseDirectory, $item->image_small_path);
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

}
