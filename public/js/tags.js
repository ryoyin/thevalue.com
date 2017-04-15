// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/tag/"+slug;
    getInfo(api_path);
});

function showContent() {
    makeCategoriesList();
    makeSideBanners();
    var stories = $('#head').children('li:first');
    showStories(stories, 'popular');

    var tag = $apiResult.tag;
    $('#category-head').html('Home > <span>'+tag.name+'</span>');
    console.log('done');
}

function showStories(obj, topic) {

    var topicList = [];

    var stories = [];
    stories['popular'] = $apiResult.tagStories;;

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
        <div class='col-md-5 left'><img src='"+image_root_path+val.photo.image_path+"' class='img-responsive' style='width:100%'></div>\
            <div class='col-md-7 right'>\
            <ul class='ul-clean'>\
            <li class='cate'>"+categoryName+"</li>\
        <li class='title'><a href='"+site_root+default_language+"/article/"+val.slug+"'>"+val.title+"</a></li>\
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