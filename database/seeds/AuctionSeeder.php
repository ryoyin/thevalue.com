<?php

use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auction_series')->insert(['slug' => 'christie-spring','total_lots' => 0, 'auction_house_id' => 1, 'start_date' => '2017-05-26 00:00:00','end_date' => '2017-05-31 00:00:00']);
        DB::table('auction_series_details')->insert(['name' => 'Christie Hong Kong Week Spring Auctions','country' => 'Hong Kong','location' => '','lang' => 'en','auction_series_id' => 1]);
        DB::table('auction_series_details')->insert(['name' => '佳士得香港春拍週','country' => '香港','location' => '','lang' => 'trad','auction_series_id' => 1]);
        DB::table('auction_series_details')->insert(['name' => '佳士得香港春拍周','country' => '香港','location' => '','lang' => 'sim','auction_series_id' => 1]);

        DB::table('auction_houses')->insert(['image_path' => 'images/company_logo/christie_logo.jpg','tel_no' => '+852 2760 1766','fax_no' => '','email' => 'info@christies.com','status' => 1]);
        DB::table('auction_house_details')->insert(['name' => 'CHRISTIE’S (HK)','address' => '','lang' => 'en','office_hour' => 'Monday - Friday, 9:30 am - 6:00 pm, local time','auction_house_id' => 1]);
        DB::table('auction_house_details')->insert(['name' => '佳士得 (香港)','address' => '','lang' => 'trad','office_hour' => '星期一至五 上午 9:30 至下午 6:00','auction_house_id' => 1]);
        DB::table('auction_house_details')->insert(['name' => '佳士得 (香港)','address' => '','lang' => 'sim','office_hour' => '星期一至五 上午 9:30 至下午 6:00','auction_house_id' => 1]);

        DB::table('christie_sales')->insert(['int_sale_id' => 27079,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27083,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 26537,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 26538,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 26539,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 26911,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27080,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 26912,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 26913,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27081,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27489,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27469,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27030,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27054,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27120,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);
        DB::table('christie_sales')->insert(['int_sale_id' => 27470,'sale_number' => 0,'get_json' => 0,'to_db' => 0,'get_image' => 0,'status' => 0,]);

    }
}
