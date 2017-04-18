// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/index";
    getInfo(api_path);
});

function showContent() {
    // makeSideBanners();

    var stories = $('#head').children('li:first');
    showStories(stories, 'latest');
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

        if(val.photo.s3) {
            var image_root_path = s3_root;
        } else {
            var image_root_path = site_root;
        }
        topicList.push("<div class='news'>\
        <div class='col-xs-5 col-md-5 left'><a href='"+site_root+default_language+"/article/"+val.slug+"'><img src='"+image_root_path+val.photo.image_path+"' class='img-responsive' style='width:100%'></a></div>\
            <div class='col-xs-7 col-md-7 right'>\
            <ul class='ul-clean'>\
            <li class='cate'><a href='"+site_root+default_language+"/category/"+category.slug+"'>"+categoryName+"</a></li>\
        <li class='title'><a href='"+site_root+default_language+"/article/"+val.slug+"'>"+val.title+"</a></li>\
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