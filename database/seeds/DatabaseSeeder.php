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
        $this->call(CategoriesTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
        $this->call(TagsTableSeeder::class);

        DB::table('users')->insert([
            'name' => 'Roy Ho',
            'email' => 'kwanyin2000@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
