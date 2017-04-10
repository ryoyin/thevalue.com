// get information when ready
$( document ).ready(function() {
    switch (cat_slug) {
        case 'videos':
        case 'videos#':
            var api_path = site_root+"api/video";
            break;
        default:
            var api_path = site_root+"api/category/"+slug;
    }

    getInfo(api_path);
});

function showContent() {
    makeCategoriesList();
    makeSideBanners();
    var stories = $('#head').children('li:first');
    showStories(stories, 'popular');

    var category = $apiResult.category;

    switch (cat_slug) {
        case 'videos':
        case 'videos#':
            $('#category-head').html('Home > <span>'+video_loc+'</span>');
            break;
        default:
            $('#category-head').html('Home > <span>'+category.name+'</span>');
    }
    // console.log('done');
}

function showStories(obj, topic) {

    var topicList = [];

    var stories = [];

    switch (cat_slug) {
        case 'videos':
        case 'videos#':
            stories['popular'] = $apiResult.searchVideo;
            break;
        default:
            stories['popular'] = $apiResult.categoryStories;
    }

    $.each(stories[topic], function(key, val) {

        $('.stories-active').removeClass('stories-active');
        $(obj).addClass('stories-active');

        var category = getCategoryByID(val.category_id);
        var categoryName = category.name;
        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        topicList.push("<div class='news'>\
        <div class='col-md-5 left'><img src='"+site_root+val.photo.image_path+"' class='img-responsive' style='width:100%'></div>\
            <div class='col-md-7 right'>\
            <ul class='ul-clean'>\
            <li class='cate'>"+categoryName+"</li>\
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