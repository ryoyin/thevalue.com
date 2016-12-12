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
        # Smart Phone
        DB::table('categories')->insert(['id' => 1, 'parent_id' => null, 'slug' => 'Smart-Phone', 'sorting' => 2, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 2, 'parent_id' => 1, 'slug' => 'Samsung', 'sorting' => 2, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 3, 'parent_id' => 1, 'slug' => 'iPhone', 'sorting' => 1, 'status' => 'published']);

        #TV
        DB::table('categories')->insert(['id' => 4, 'parent_id' => null, 'slug' => 'TV', 'sorting' => 1, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 5, 'parent_id' => 4, 'slug' => 'LCD', 'sorting' => 1, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 6, 'parent_id' => 5, 'slug' => 'Samsung', 'sorting' => 1, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 7, 'parent_id' => 5, 'slug' => 'Sony', 'sorting' => 1, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 8, 'parent_id' => 5, 'slug' => 'LG', 'sorting' => 1, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 9, 'parent_id' => 4, 'slug' => 'Plasma', 'sorting' => 1, 'status' => 'published']);
        DB::table('categories')->insert(['id' => 10, 'parent_id' => 8, 'slug' => 'Panasonic', 'sorting' => 1, 'status' => 'published']);

        #Computer
        DB::table('categories')->insert(['id' => 11, 'parent_id' => null, 'slug' => 'Computer', 'sorting' => 3, 'status' => 'suspend']);

        # Category Details
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Smart Phone', 'photo_id' => null, 'category_id' => 1]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '智能電話', 'photo_id' => null, 'category_id' => 1]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Samsung', 'photo_id' => null, 'category_id' => 2]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '三星', 'photo_id' => null, 'category_id' => 2]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'iPhone', 'photo_id' => null, 'category_id' => 3]);

        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'TV', 'photo_id' => null, 'category_id' => 4]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '電視', 'photo_id' => null, 'category_id' => 4]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'LCD', 'photo_id' => null, 'category_id' => 5]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '液晶顯示器', 'photo_id' => null, 'category_id' => 5]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Samsung', 'photo_id' => null, 'category_id' => 6]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '三星', 'photo_id' => null, 'category_id' => 6]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Sony', 'photo_id' => null, 'category_id' => 7]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '索尼', 'photo_id' => null, 'category_id' => 7]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'LG', 'photo_id' => null, 'category_id' => 8]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => 'LG', 'photo_id' => null, 'category_id' => 8]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Plasma', 'photo_id' => null, 'category_id' => 9]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '離子顯示器', 'photo_id' => null, 'category_id' => 9]);
        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Panasonic', 'photo_id' => null, 'category_id' => 10]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '松下', 'photo_id' => null, 'category_id' => 10]);

        DB::table('category_details')->insert(['lang' => 'en', 'name' => 'Computer', 'photo_id' => null, 'category_id' => 11]);
        DB::table('category_details')->insert(['lang' => 'trad', 'name' => '電腦', 'photo_id' => null, 'category_id' => 11]);
    }
}
