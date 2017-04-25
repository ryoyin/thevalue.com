<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = App\Notification::orderBy('created_at', 'desc')->get();
        $topics = App\AWSSNSTopic::all();

        $data = array(
            'menu' => array('notification', 'notification.list'),
            'topics' => $topics,
            'notifications' => $notifications,
        );

        return view('backend.notifications.index', $data);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'aws_sns_topic_id' => 'required|integer',
            'message' => 'required|max:256',
        ]);

        $aws_sns_topic_id = $request->aws_sns_topic_id;
        $message = $request->message;

        $topic = App\AWSSNSTopic::where('id', $aws_sns_topic_id)->first();
        $topicARN = $topic->topic_arn;

        $publish_result = $this->publish($topicARN, $message);

//        echo $publish_result['MessageId'];

        $notification = new App\Notification;
        $notification->aws_sns_topic_id = $aws_sns_topic_id;
        $notification->message = $message;
        $notification->save();

        return $notification;

    }

    public function publish($topicARN, $message)
    {
        $sns = \AWS::createClient('SNS');

        $result = $sns->publish(array(
            'TopicArn' => $topicARN,
            'Message' => $message,
        ));

        return $result;

    }
}
