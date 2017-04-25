<?php

use Illuminate\Database\Seeder;

class AWSSNSTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id	locale	name	topic_arn	description
        DB::table('aws_sns_topics')->insert([
            'id' => 2,
            'locale' => 'cn',
            'name' => 'notification_cn',
            'display_name' => '簡體',
            'topic_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:notification_cn'
        ]);

        DB::table('aws_sns_topics')->insert([
            'id' => 3,
            'locale' => 'en',
            'name' => 'notification_en',
            'display_name' => 'English',
            'topic_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:notification_en'
        ]);

        DB::table('aws_sns_topics')->insert([
            'id' => 1,
            'locale' => 'hk',
            'name' => 'notification_hk',
            'display_name' => '繁體',
            'topic_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:notification_hk'
        ]);
    }
}
