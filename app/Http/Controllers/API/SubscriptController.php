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

        /*$fp = fopen('/opt/lampp/htdocs/www.thevalue.com/public/images/data.txt', 'w');
        fwrite($fp, $request->getContent());
        fclose($fp);

        return $request->getContent();*/

        $sentData = json_decode($request->getContent(), true);

/*        $this->validate($sentData, [
            'os' => 'required',
            'type' => 'required',
            'token' => 'required',
            'userData' => 'required',
        ]);*/


        $acceptedType = array('APNS', 'GCM');
        if(!in_array($sentData['type'], $acceptedType)) return false;

        $sns = \AWS::createClient('SNS');

        $platformApplicationArn = array();
        $platformApplicationArn['APNS'] = 'arn:aws:sns:ap-southeast-1:527599532354:app/APNS_SANDBOX/theValueAppIOS';
        $platformApplicationArn['GCM'] = 'arn:aws:sns:ap-southeast-1:527599532354:app/GCM/theValueAppAndroid';

        $endpointARN = $sns->createPlatformEndpoint(array(
            // PlatformApplicationArn is required
            'PlatformApplicationArn' => $platformApplicationArn[$sentData['type']],
            // Token is required
            'Token' => $sentData['token'],
            'CustomUserData' => $sentData['userData'],
        ));

        return $endpointARN;
    }

}
