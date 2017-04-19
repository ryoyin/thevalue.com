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

}
