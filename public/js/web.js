// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/index";
    getInfo(api_path);
});

function showContent() {
    makeCategoriesList();
    makeBanners();
    makeSideBanners();
    makeFeaturedArticles();

    var stories = $('#head').children('li:first');
    showStories(stories, 'latest');
}

function makeBanners() {
    var $topBannersArray = $apiResult.topBanners;
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

function makeFeaturedArticles() {
    var $featuredArticlesArray = $apiResult.featuredArticles;
    var featuredArticles = [];
    // console.log($featuredArticlesArray);
    $.each($featuredArticlesArray, function(key, val) {
        var category = getCategoryByID(val.category_id);

        var categoryName = category.name;

        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        featuredArticles.push("<ul class='col-xs-6 col-md-3 ul-clean'>" +
            "<li><img src='"+val.photo.image_path+"' class='img-responsive'></li>" +
            "<li><a href='"+site_root+"category/"+category.slug+"' class='category_name'>"+categoryName+"</a></li><li><a href='"+site_root+"article/"+val.slug+"'>"+val.title+"</a></li>" +
            "</ul>");
    });

    $('#featured-article').html(featuredArticles.join(""));

}

function showStories(obj, topic) {

    var topicList = [];

    var stories = [];
    stories['latest'] = $apiResult.latestStories;
    stories['popular'] = $apiResult.popularStories;

    $.each(stories[topic], function(key, val) {

        $('.stories-active').removeClass('stories-active');
        $(obj).addClass('stories-active');

        var category = getCategoryByID(val.category_id);
        var categoryName = category.name;
        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        topicList.push("<div class='news'>\
        <div class='col-xs-5 col-md-5 left'><img src='"+val.photo.image_path+"' class='img-responsive' style='width:100%'></div>\
            <div class='col-xs-5 col-md-7 right'>\
            <ul class='ul-clean'>\
            <li class='cate'><a href='"+site_root+"category/"+category.slug+"'>"+categoryName+"</a></li>\
        <li class='title'><a href='"+site_root+"article/"+val.slug+"'>"+val.title+"</a></li>\
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



function showStoryCategories() {
    $('#stories-categories').toggle();
}