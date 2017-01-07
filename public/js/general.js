//set site information
var site_root = window.location.href;
var sr_split = site_root.split('/');

if(sr_split[2] == 'localhost') {
    site_root = 'http://localhost/public/';
} else {
    site_root = 'http://ryoyin.ddns.net/thevalue.com/public/';
}

// get information when ready
$( document ).ready(function() {
    getInfo();
});

//global api result
var $apiResult;