<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # create article
        DB::table('articles')->insert(['id' => 1, 'category_id' => 12, 'slug' => 'Testing Article 1', 'photo_id' => 2, 'status' => 'published']);
        DB::table('articles')->insert(['id' => 2, 'category_id' => 12,  'slug' => 'Testing Article 2', 'photo_id' => 3, 'status' => 'published']);
        DB::table('articles')->insert(['id' => 3, 'category_id' => 13,  'slug' => 'Testing Article 3', 'photo_id' => 4, 'status' => 'published']);
        DB::table('articles')->insert(['id' => 4, 'category_id' => 14,  'slug' => 'Testing Article 3', 'photo_id' => 5, 'status' => 'published']);

        # create article detail
        # lang	title	short_desc	description	status	article_id
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Testing Article 1',
            'short_desc' => 'Marvel’s The Inhumans Series Heads to IMAX Theaters and ABC!',
            'description' => 'description 1 description 1 description 1 description 1 description 1 description 1',
            'status' => 'published',
            'article_id' => 1
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => 'Marvel’s The Inhumans Series Heads to IMAX Theaters and ABC!',
            'short_desc' => '短句 一 短句 一 短句 一 短句 一 短句 一 短句 一',
            'description' => '內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一',
            'status' => 'published',
            'article_id' => 1
        ]);
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Marvel’s Defenders Cast is Coming Together in New Photos',
            'short_desc' => 'short desc 2 short desc 2 short desc 2 short desc 2 short desc 2 ',
            'description' => 'description 2 description 2 description 2 description 2 description 2 description 2',
            'status' => 'published',
            'article_id' => 2
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => 'Marvel’s Defenders Cast is Coming Together in New Photos',
            'short_desc' => '短句 二 短句 二 短句 二 短句 二 短句 二 短句 二',
            'description' => '內容 二 內容 二 內容 二 內容 二 內容 二 內容 二 內容 二 內容 二',
            'status' => 'published',
            'article_id' => 2
        ]);
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Full Justice League Movie Cast Revealed',
            'short_desc' => 'short desc 3 short desc 3 short desc 3 short desc 3 short desc 3',
            'description' => 'description 3 description 3 description 3 description 3 description 3 description 3 description 3',
            'status' => 'published',
            'article_id' => 3
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => 'Full Justice League Movie Cast Revealed',
            'short_desc' => '短句 3 短句 3 短句 3 短句 3',
            'description' => '內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3',
            'status' => 'published',
            'article_id' => 3
        ]);
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Explore the Multiverse with Our Benedict Cumberbatch Doctor Strange Video Interview',
            'short_desc' => 'short desc 4 short desc 4 short desc 4 short desc 4 short desc 4',
            'description' => 'description 4 description 4 description 4 description 4 description 4 description 4 description 4',
            'status' => 'published',
            'article_id' => 4
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => 'Explore the Multiverse with Our Benedict Cumberbatch Doctor Strange Video Interview',
            'short_desc' => '短句 4 短句 4 短句 4 短句 4',
            'description' => '內容 4 內容 4 內容 4 內容 4 內容 4 內容 4 內容 4 內容 4 內容 4 內容 4',
            'status' => 'published',
            'article_id' => 4
        ]);

        # Featured Article
        DB::table('featured_articles')->insert(['article_id' => 1]);
        DB::table('featured_articles')->insert(['article_id' => 2]);
        DB::table('featured_articles')->insert(['article_id' => 3]);
        DB::table('featured_articles')->insert(['article_id' => 4]);

    }
}
