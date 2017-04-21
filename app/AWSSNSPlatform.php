<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWSSNSPlatform extends Model
{

    protected $table = 'aws_sns_platforms';

    public function mobiles()
    {
        $this->hasMany('App\AWSSNSMobile');
    }

}
