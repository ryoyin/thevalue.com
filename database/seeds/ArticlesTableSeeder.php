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
        DB::table('articles')->insert(['id' => 1, 'category_id' => 1, 'slug' => 'Testing-Article-1', 'status' => 'published']);
        DB::table('articles')->insert(['id' => 2, 'category_id' => 1, 'slug' => 'Testing-Article-2', 'status' => 'published']);
        DB::table('articles')->insert(['id' => 3, 'category_id' => 2,'slug' => 'Testing-Article-3', 'status' => 'published']);

        # create article detail
        # lang	title	short_desc	description	status	article_id
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Testing Article 1',
            'short_desc' => 'short desc 1',
            'description' => 'description 1 description 1 description 1 description 1 description 1 description 1',
            'status' => 'published',
            'article_id' => 1
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => '測試 一',
            'short_desc' => '短句 一',
            'description' => '內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一 內容 一',
            'status' => 'published',
            'article_id' => 1
        ]);
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Testing Article 2',
            'short_desc' => 'short desc 2',
            'description' => 'description 2 description 2 description 2 description 2 description 2 description 2',
            'status' => 'published',
            'article_id' => 2
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => '測試 二',
            'short_desc' => '短句 二',
            'description' => '內容 二 內容 二 內容 二 內容 二 內容 二 內容 二 內容 二 內容 二',
            'status' => 'published',
            'article_id' => 2
        ]);
        DB::table('article_details')->insert([
            'lang' => 'en',
            'title' => 'Testing Article 3',
            'short_desc' => 'short desc 3',
            'description' => 'description 3 description 3 description 3 description 3 description 3 description 3 description 3',
            'status' => 'published',
            'article_id' => 3
        ]);
        DB::table('article_details')->insert([
            'lang' => 'trad',
            'title' => '測試 3',
            'short_desc' => '短句 3',
            'description' => '內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3 內容 3',
            'status' => 'published',
            'article_id' => 3
        ]);

        # Featured Article
        DB::table('featured_articles')->insert(['article_id' => 1]);

        # Tag


    }
}
