var site_root = window.location.href;
var sr_split = site_root.split('/');
if(sr_split[2] == 'localhost') {
    site_root = 'http://localhost/public/';
} else {
    site_root = 'http://ryoyin.ddns.net/public/';
}

$( document ).ready(function() {
    getInfo();
});

var $categoriesArray;
var $topBannersArray;

function getInfo() {

    $.ajaxSetup({
        headers : {
            'Content-Language' : 'trad'
        }
    });

    $.getJSON( site_root+"api/index", function( data ) {
        $.each( data, function( key, val ) {
            switch(key) {
                case 'categories':
                    $categoriesArray = val;
                    break;
                case 'topBanners':
                    $topBannersArray = val;
                    break;
            }
        });

        showContent();
    });

}

function showContent() {
    makeCategoriesList();
    makeBanners();
}

function makeCategoriesList() {
    var categoriesItems = [];
    $.each($categoriesArray, function(key, val) {
        if(val.parent == null) {
            categoriesItems.push("<li><a href='"+site_root+val.url+"/"+val.slug+"'>"+val.name+"</a></li>")
        }
    });
    $('#categoriesList').html(categoriesItems.join(""));
    $('#stories-categories').html(categoriesItems.join(""));
}

function makeBanners() {
    var indicators = [];
    var topBanners = [];

    $.each($topBannersArray, function(key, val) {
        var indicatorClass = "";
        if(key == 0) indicatorClass = "class='active'";
        indicators.push("<li data-target='#carousel-main-banner' data-slide-to='"+key+"' "+indicatorClass+"></li>");

        var bannerClass = "";
        if(key == 0) bannerClass = "active";
        topBanners.push("<div class='item "+bannerClass+"'><img src='"+site_root+val.image_path+"' alt='...'><div class='carousel-caption'>"+val.alt+"</div></div>");
    });

    $('.carousel-indicators').html(indicators.join(""));
    $('.carousel-indicators').children('li').css('margin', '0 3px');

    $('.carousel-inner').html(topBanners.join(""));
}

function getCategoryByID(id) {
    // console.log(id);
    for(var i = 0; i < $categoriesArray.length; i++ ) {

        if($categoriesArray[i].id == id) {
            var category = $categoriesArray[i];
            return category;
        }
    }
}

