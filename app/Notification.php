<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function topic()
    {
        return $this->belongsTo('App\AWSSNSTopic', 'aws_sns_topic_id');
    }
}
