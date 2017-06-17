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
        $share->locale = App::getLocale();
        $share->save();

        return $share;

    }

    public function registerEndpoint(Request $request)
    {
        // $userData: type, token, os, locale, userdata
        $userData = json_decode($request->getContent(), true);

        $acceptedType = array('APNS', 'GCM');
        if(!in_array($userData['type'], $acceptedType)) return false;

        $mobile = App\AWSSNSMobile::where('uuid', $userData['uuid'])->first();

//        return $mobile;

        if($mobile != null) { // check device exists or not

            // if diff locale
            if($mobile->locale != $userData['locale']) {

                // unsubscribe topic
                $this->unsubscribe($mobile->subscription_arn);

                // get topic locale ARN
                $topic = App\AWSSNSTopic::where('locale', $userData['locale'])->first();
                $topicARN = $topic->topic_arn;

                // subscription ARN
                $newSubscriptionARN = $this->subscribe($mobile->endpoint_arn, $topicARN);

                // change DB locale
                $mobile->locale = $userData['locale'];
                $mobile->subscription_arn = $newSubscriptionARN;
                $mobile->save();

            }

        } else {// else device not exists

            // get platform ARN
            $platform = App\AWSSNSPlatform::where('type', $userData['type'])->first();
            $platformARN = $platform->platform_arn;
            $platformID = $platform->id;

            // add device to platform
            $endpointARN = $this->createPlatformEndpoint($userData, $platformARN);

            // insert mobile data to DB

            // os	type	token	user_data	locale	aws_sns_platform_id	endpoint_arn	aws_sns_topic_id	subscription_arn
            $mobile = new App\AWSSNSMobile;
            $mobile->uuid = $userData['uuid'];
            $mobile->os = $userData['os'];
            $mobile->type = $userData['type'];
            $mobile->token = $userData['token'];
            $mobile->user_data = $userData['userData'];
            $mobile->locale = $userData['locale'];
            $mobile->aws_sns_platform_id = $platformID;
            $mobile->endpoint_arn = $endpointARN;
            $mobile->aws_sns_topic_id = $topicID;
            $mobile->subscription_arn = $subscriptionARN;
            $mobile->save();

            // subscribe topic
            $topic = App\AWSSNSTopic::where('locale', $userData['locale'])->first();
            $topicARN = $topic->topic_arn;
            $topicID = $topic->id;
//            $subscriptionARN = $this->subscribe($endpointARN, $topicARN);
            $subscriptionARN = $this->subscribe($endpointARN, 'arn:aws:sns:ap-southeast-1:527599532354:ios-pn-fail');

        }

        return $request->getContent();

    }

    public function listSubscriptionsByTopic()
    {
        $sns = \AWS::createClient('SNS');

        $result = $sns->listSubscriptionsByTopic([
            'TopicArn' => '<string>', // REQUIRED
        ]);
    }

    public function createPlatformEndpoint($userData, $platformARN)
    {
        $sns = \AWS::createClient('SNS');

        $result = $sns->createPlatformEndpoint(array(
            // PlatformApplicationArn is required
            'PlatformApplicationArn' => $platformARN,
            // Token is required
            'Token' => $userData['token'],
            'CustomUserData' => $userData['userData'],
        ));

        return $result['EndpointArn'];
    }

    public function subscribe($endpointARN, $topicARN)
    {
        $sns = \AWS::createClient('SNS');

        $resultSubscribe = $sns->subscribe([
            'Endpoint' => $endpointARN,
            'Protocol' => 'application', // REQUIRED
            'TopicArn' => $topicARN // REQUIRED
        ]);

        $subscriptionARN =  $resultSubscribe['SubscriptionArn'];

        return $subscriptionARN;
    }

    public function unsubscribe($subscriptionARN)
    {
        $sns = \AWS::createClient('SNS');

        $sns->unsubscribe([
            'SubscriptionArn' => $subscriptionARN, // REQUIRED
        ]);

        return true;
    }

}
