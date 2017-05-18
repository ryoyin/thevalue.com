<?php
/**
 * Created by PhpStorm.
 * User: Roy Ho
 * Date: 2017/5/18
 * Time: 下午 01:16
 */

namespace app\Classes;
use App;

class Auction
{
    public function getPreAuction()
    {
        echo App::getLocale();
    }
}