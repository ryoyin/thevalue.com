<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWSSNSMobile extends Model
{

    protected $table = 'aws_sns_mobiles';

    public function platform()
    {
        return $this->belongsTo('App\AWSSNSPlatform');
    }

    public function topic()
    {
        return $this->belongsTo('App\AWSSNSTopic');
    }

}
