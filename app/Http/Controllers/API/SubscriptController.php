<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

class SubscriptController extends Controller
{
    public function subscription(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

//        return true;
//
        $email = $request->input('email');

        $share = new App\ShareEmail;
        $share->email = $email;
        $share->save();

        $sns = \AWS::createClient('SNS');
        $sns->subscribe(array(
            // TopicArn is required
            'TopicArn' => 'arn:aws:sns:ap-southeast-1:527599532354:TheValue_Newsletter',
            // Protocol is required
            'Protocol' => 'email',
            'Endpoint' => $email,
        ));

    }

    public function registerEndpoint(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'token' => 'required',
            'userData' => 'required',
        ]);

        $sns = \AWS::createClient('SNS');

        $platformApplicationArn = array();
        $platformApplicationArn['APNS'] = 'arn:aws:sns:ap-southeast-1:527599532354:app/APNS_SANDBOX/theValueAppIOS';

        $endpointARN = $sns->createPlatformEndpoint(array(
            // PlatformApplicationArn is required
            'PlatformApplicationArn' => $platformApplicationArn[$request->input('type')],
            // Token is required
            'Token' => $request->input('token'),
            'CustomUserData' => $request->input('userData'),
        ));

        /*$subscriptionARN = $sns->subscribe(array(
            // TopicArn is required
            'TopicArn' => 'arn:aws:sns:ap-southeast-1:527599532354:TheValue_Newsletter',
            // Protocol is required
            'Protocol' => 'application',
            'Endpoint' => $endpointARN,
        ));*/

        return $endpointARN;
    }

}
