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
        DB::table('photos')->insert(['alt' => 'Testing Photo 1', 'image_path' => 'images/photos/test01.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['alt' => 'Testing Photo 2', 'image_path' => 'images/photos/test02.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['alt' => 'Testing Photo 3', 'image_path' => 'images/photos/test03.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['alt' => 'Testing Photo 4', 'image_path' => 'images/photos/test04.jpg', 'size' => 'original']);
        DB::table('photos')->insert(['alt' => 'Testing Photo 5', 'image_path' => 'images/photos/test05.jpg', 'size' => 'original']);
    }
}
