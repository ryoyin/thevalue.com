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
        DB::table('articles')->insert(['id' => 1, 'slug' => 'Testing Article '.random(), 'slug' => 'Smart-Phone', 'sorting' => 2, 'status' => 'published']);
    }
}
