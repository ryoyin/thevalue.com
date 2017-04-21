<?php

use Illuminate\Database\Seeder;

class AWSSNSPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id	platform	platform_arn	description
        DB::table('aws_sns_platforms')->insert([
            'id' => 1,
            'platform' => 'Apple iOS Dev',
            'type' => 'APNS',
            'platform_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:app/APNS_SANDBOX/theValueAppIOS',
        ]);

        DB::table('aws_sns_platforms')->insert([
            'id' => 2,
            'platform' => 'Google Android',
            'type' => 'GCM',
            'platform_arn' => 'arn:aws:sns:ap-southeast-1:527599532354:app/GCM/theValueAppAndroid',
        ]);
    }
}
