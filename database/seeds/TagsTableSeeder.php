<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert(['id' => 1, 'slug' => 'Tag-1']);
        DB::table('tags')->insert(['id' => 2, 'slug' => 'Tag-2']);

        DB::table('tag_details')->insert(['id' => 1, 'lang' => 'en', 'name' => 'Tag 1', 'tag_id' => 1]);
        DB::table('tag_details')->insert(['id' => 2, 'lang' => 'trad', 'name' => '標示 1', 'tag_id' => 1]);
        DB::table('tag_details')->insert(['id' => 3, 'lang' => 'sim', 'name' => '標示 1', 'tag_id' => 1]);
        DB::table('tag_details')->insert(['id' => 4, 'lang' => 'en', 'name' => 'Tag 2', 'tag_id' => 2]);
        DB::table('tag_details')->insert(['id' => 5, 'lang' => 'trad', 'name' => '標示 2', 'tag_id' => 2]);
        DB::table('tag_details')->insert(['id' => 6, 'lang' => 'sim', 'name' => '標示 2', 'tag_id' => 2]);

        DB::table('article_tag')->insert(['article_id' => 1, 'tag_id'=> 1]);
        DB::table('article_tag')->insert(['article_id' => 1, 'tag_id'=> 2]);
        DB::table('article_tag')->insert(['article_id' => 2, 'tag_id'=> 1]);
        DB::table('article_tag')->insert(['article_id' => 3, 'tag_id'=> 2]);
        DB::table('article_tag')->insert(['article_id' => 4, 'tag_id'=> 1]);
        DB::table('article_tag')->insert(['article_id' => 4, 'tag_id'=> 2]);

    }
}
