$( document ).ready(function() {
    getInfo();
});

var $categoriesArray;
var $bannersArray;

function getInfo() {

    $.ajaxSetup({
        headers : {
            'Content-Language' : 'trad'
        }
    });

    $.getJSON( "api/index", function( data ) {
        $.each( data, function( key, val ) {
            switch(key) {
                case 'categories':
                    $categoriesArray = val;
                    break;
                case 'banners':
                    $bannersArray = val;
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
            categoriesItems.push("<li><a href='"+val.url+"/"+val.slug+"'>"+val.name+"</a></li>")
        }
    });
    $('#categoriesList').html(categoriesItems.join(""));
}

function makeBanners() {
    var indicators = [];
    var banners = [];
    console.log($bannersArray);

    $.each($bannersArray, function(key, val) {
        var indicatorClass = "";
        if(key == 0) indicatorClass = "class='active'";
        indicators.push("<li data-target='#carousel-main-banner' data-slide-to='"+key+"' "+indicatorClass+"></li>");

        var bannerClass = "";
        if(key == 0) bannerClass = "active";
        banners.push("<div class='item "+bannerClass+"'><img src='"+val.image_path+"' alt='...'><div class='carousel-caption'>"+val.alt+"</div></div>");
    });

    $('.carousel-indicators').html(indicators.join(""));
    $('.carousel-indicators').children('li').css('margin', '0 3px');

    $('.carousel-inner').html(banners.join(""));
}
