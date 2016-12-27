<?php

use Illuminate\Database\Seeder;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        id	alt	image_path	watermark	size - (o)riginal, (m)edium, (s)mall
        DB::table('photos')->insert(['id' => 1, 'alt' => 'Advertisment 1', 'image_path' => 'images/advert/advert-01.png', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 2, 'alt' => 'Article Photo 1', 'image_path' => 'images/articles/temp/article-01.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 3, 'alt' => 'Article Photo 2', 'image_path' => 'images/articles/temp/article-02.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 4, 'alt' => 'Article Photo 3', 'image_path' => 'images/articles/temp/article-03.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 5, 'alt' => 'Article Photo 4', 'image_path' => 'images/articles/temp/article-04.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 6, 'alt' => 'Article Photo 5', 'image_path' => 'images/articles/temp/article-05.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 7, 'alt' => 'Banner Photo 1', 'image_path' => 'banners/banner-01.jpeg', 'size' => 'original']);
        DB::table('photos')->insert(['id' => 8, 'alt' => 'Banner photo 2', 'image_path' => 'banners/banner-02.jpeg', 'size' => 'original']);

        DB::table('banners')->insert(['id' => 1, 'photo_id' => 7, 'sorting' => 1]);
        DB::table('banners')->insert(['id' => 2, 'photo_id' => 8, 'sorting' => 1]);

    }
}
