<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('categories')->insert(['id' => 12, 'parent_id' => null, 'slug' => 'Now-Focus', 'sorting' => 4, 'status' => 'published']);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Now Focus', 'photo_id' => null, 'category_id' => 12]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '時下焦點', 'photo_id' => null, 'category_id' => 12]);
        DB::table('category_details')->insert(['lang' => 'sim', 'name' => '時下焦點', 'photo_id' => null, 'category_id' => 12]);

        DB::table('categories')->insert(['id' => 13, 'parent_id' => null, 'slug' => 'Special-Interview', 'sorting' => 5, 'status' => 'published']);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Special Interview', 'photo_id' => null, 'category_id' => 13]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '專題專訪', 'photo_id' => null, 'category_id' => 13]);
        DB::table('category_details')->insert(['lang' => 'sim', 'name' => '專題專訪', 'photo_id' => null, 'category_id' => 13]);

        DB::table('categories')->insert(['id' => 14, 'parent_id' => null, 'slug' => 'Global-Gallery', 'sorting' => 6, 'status' => 'published']);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Global Gallery', 'photo_id' => null, 'category_id' => 14]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '全球藝廊', 'photo_id' => null, 'category_id' => 14]);
        DB::table('category_details')->insert(['lang' => 'sim', 'name' => '全球藝廊', 'photo_id' => null, 'category_id' => 14]);

        DB::table('categories')->insert(['id' => 15, 'parent_id' => null, 'slug' => 'Data-Centre', 'sorting' => 7, 'status' => 'published']);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Data Centre', 'photo_id' => null, 'category_id' => 15]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '數據中心', 'photo_id' => null, 'category_id' => 15]);
        DB::table('category_details')->insert(['lang' => 'sim', 'name' => '數據中心', 'photo_id' => null, 'category_id' => 15]);

        DB::table('categories')->insert(['id' => 16, 'parent_id' => null, 'slug' => 'LIVE-SMART', 'sorting' => 8, 'status' => 'published']);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'LIVE SMART', 'photo_id' => null, 'category_id' => 16]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => 'LIVE SMART', 'photo_id' => null, 'category_id' => 16]);
        DB::table('category_details')->insert(['lang' => 'sim', 'name' => 'LIVE SMART', 'photo_id' => null, 'category_id' => 16]);
    }
}
