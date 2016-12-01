<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(['id' => 1, 'name' => 'TV']);
        DB::table('categories')->insert(['id' => 2, 'name' => 'Cell Phone']);
        DB::table('categories')->insert(['id' => 3, 'name' => 'Computer']);

        DB::table('categories')->insert(['id' => 4, 'name' => 'LCD', 'parent_id' => 1]);
        DB::table('categories')->insert(['id' => 5, 'name' => 'Plasma', 'parent_id' => 1]);
        DB::table('categories')->insert(['id' => 6, 'name' => 'iPhone', 'parent_id' => 2]);
        DB::table('categories')->insert(['id' => 7, 'name' => 'Android', 'parent_id' => 2]);
        DB::table('categories')->insert(['id' => 8, 'name' => 'Samsung Note 7', 'parent_id' => 7]);

        //title caption alt status
        DB::table('photos')->insert('title'=>'');
    }
}
