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
            'id' => 1,
            'locale' => 'cn',
            'name' => 'notification_cn',
            'topic_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:notification_cn'
        ]);

        DB::table('aws_sns_topics')->insert([
            'id' => 2,
            'locale' => 'en',
            'name' => 'notification_en',
            'topic_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:notification_en'
        ]);

        DB::table('aws_sns_topics')->insert([
            'id' => 3,
            'locale' => 'hk',
            'name' => 'notification_hk',
            'topic_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:notification_hk'
        ]);
    }
}
