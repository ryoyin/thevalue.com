var site_root = window.location.href;
var sr_split = site_root.split('/');
if(sr_split[2] == 'localhost') {
    site_root = 'http://localhost/public/';
} else {
    site_root = 'http://ryoyin.ddns.net/thevalue.com/public/';
}

$( document ).ready(function() {
    getInfo();
});

var $categoriesArray;
var $topBannersArray;
var $sideBannersArray;
var $featuredArticlesArray;
var $latestStoriesArray;
var $popularStoriesArray;

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
                case 'topBanners':
                    $topBannersArray = val;
                    break;
                case 'sideBanners':
                    $sideBannersArray = val;
                    break;
                case 'featuredArticles':
                    $featuredArticlesArray = val;
                    break;
                case 'latestStories':
                    console.log(val);
                    $latestStoriesArray = val;
                    break;
                case 'popularStories':
                    $popularStoriesArray = val;
                    break;
            }
        });

        showContent();
    });

}

function showContent() {
    makeCategoriesList();
    makeBanners();
    makeSideBanners();
    makeFeaturedArticles();

    var stories = $('#head').children('li:first');
    showStories(stories, 'latest');
}

function makeCategoriesList() {
    var categoriesItems = [];
    $.each($categoriesArray, function(key, val) {
        if(val.parent == null) {
            categoriesItems.push("<li><a href='"+val.url+"/"+val.slug+"'>"+val.name+"</a></li>")
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
        topBanners.push("<div class='item "+bannerClass+"'><img src='"+val.image_path+"' alt='...'><div class='carousel-caption'>"+val.alt+"</div></div>");
    });

    $('.carousel-indicators').html(indicators.join(""));
    $('.carousel-indicators').children('li').css('margin', '0 3px');

    $('.carousel-inner').html(topBanners.join(""));
}

function makeSideBanners() {
    var sideBanners = [];

    $.each($sideBannersArray, function(key, val) {
        sideBanners.push("<li><img src='"+val.image_path+"' style='width: 100%' class='img-responsive'></li>");
    });

    $('#advert').html(sideBanners.join(""));
}

function makeFeaturedArticles() {

    var featuredArticles = [];
    // console.log($featuredArticlesArray);
    $.each($featuredArticlesArray, function(key, val) {
        var category = getCategoryByID(val.category_id);

        var categoryName = category.name;

        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        featuredArticles.push("<ul class='col-md-3 ul-clean'>" +
            "<li><img src='"+val.photo.image_path+"' class='img-responsive'></li>" +
            "<li>"+categoryName+"</li><li><a href='"+site_root+"article/"+val.slug+"'>"+val.title+"</a></li>" +
            "</ul>");
    });

    $('#featured-article').html(featuredArticles.join(""));

}

function showStories(obj, topic) {

    var topicList = [];

    var stories = [];
    stories['latest'] = $latestStoriesArray;
    stories['popular'] = $popularStoriesArray;

    $.each(stories[topic], function(key, val) {

        $('.stories-active').removeClass('stories-active');
        $(obj).addClass('stories-active');

        var category = getCategoryByID(val.category_id);
        var categoryName = category.name;
        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        topicList.push("<div class='news'>\
        <div class='col-md-5 left'><img src='"+val.photo.image_path+"' class='img-responsive' style='width:100%'></div>\
            <div class='col-md-7 right'>\
            <ul class='ul-clean'>\
            <li class='cate'>"+categoryName+"</li>\
        <li class='title'>"+val.title+"</li>\
        <!--\
        <ul class='misc ul-clean'>\
            <li class='pull-left'>by <span>Stan</span> Nov 24, 2016 </li>\
        <li class='pull-right'>\
            <ul class='ul-clean share'>\
            <li><i class='fa fa-envelope' aria-hidden='true'></i></li>\
            <li><i class='fa fa-wechat' aria-hidden='true'></i></li>\
            <li><i class='fa fa-weibo' aria-hidden='true'></i></li>\
            <li><i class='fa fa-twitter' aria-hidden='true'></i></li>\
            <li><i class='fa fa-facebook-f' aria-hidden='true'></i></li>\
            <li><span>416 shares</span></li>\
        </ul>\
        </li>\
        </ul>\
        -->\
        <li class='desc' style='clear:both'>"+val.short_desc+"</li>\
        </ul>\
        </div>\
        </div>\
        <div style='clear:both'></div>");
    });

    $('#stories').html(topicList.join(""));
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

function showStoryCategories() {
    $('#stories-categories').toggle();
}