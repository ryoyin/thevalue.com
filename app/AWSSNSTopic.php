<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWSSNSTopic extends Model
{

    protected $table = 'aws_sns_topics';

    public function mobiles()
    {
        $this->hasMany('App\AWSSNSMobile');
    }

}
