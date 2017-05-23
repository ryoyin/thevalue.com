var latestStoriesCounter = 1;
var currentStories;

// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/index";
    getInfo(api_path);

    // infinite scroll
    var win = $(window);

    // latestStoriesPaginationInfo
    // "total": 4,
    // "per_page": 2,
    // "current_page": 1,
    // "last_page": 2,
    // "next_page_url": "http://localhost/www.thevalue.com/public/api/index?page=2",
    // "prev_page_url": null,
    // "from": 1,
    // "to": 2,

    // Each time the user scrolls
    win.scroll(function() {

        // End of the document reached?
        if ($(document).height() - win.height() == win.scrollTop()) {

            if(currentStories == 'latest') {
                var url = site_root+"api/getLatestStoriesPaginationInfo?page="+latestStoriesCounter;
            } else {
                var url = site_root+"api/getPopularStoriesPaginationInfo?page="+latestStoriesCounter;
            }

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(json) {
                    genLastStories(json);
                }
            });

        }
    });
});

function genLastStories(latestStoriesPaginationInfo) {
    // console.log(latestStoriesPaginationInfo);
    // check not reaching last page
    latestStoriesCounter ++;

    var genStories = true;

    if(latestStoriesCounter > latestStoriesPaginationInfo.last_page) {
        genStories = false;
        latestStoriesCounter = latestStoriesPaginationInfo.last_page;
    }

    if(latestStoriesCounter <= latestStoriesPaginationInfo.last_page && genStories) {
        if(latestStoriesCounter <= 3) {
            // console.log('auto show...');

            $("#stories-loading-spinner").show().html('loading ...');

            if(currentStories == 'latest') {
                var url = site_root+"api/getLatestStories?page="+latestStoriesCounter;
            } else {
                var url = site_root+"api/getPopularStories?page="+latestStoriesCounter;
            }

            $.ajax({
                url: url,
                dataType: 'json',
                success: function(json) {
                    if(currentStories == 'latest') {
                        var stories = $('#head').children('li:nth-child(1)');
                    } else {
                        var stories = $('#head').children('li:nth-child(2)');
                    }

                    var topicList = makeStories(json, stories);
                    $('#stories').append(topicList);
                    $('#stories-loading-spinner').hide();
                }
            });
        } else {
            if(currentStories == 'latest') {
                var type = 'latestStories';
            } else {
                var type = 'popularStories';
            }
            $("#stories-loading-spinner").attr('type', type).show().html('click for more');
        }

    }
}

function showContent() {
    var stories = $('#head').children('li:first');
    showStories(stories, 'latest');
}

function showStories(obj, topic) {

    latestStoriesCounter = 1;

    var stories = [];
    stories['latest'] = $apiResult.latestStories;
    stories['popular'] = $apiResult.popularStories;

    var topicList = makeStories(stories[topic], obj);
    currentStories = topic;

    $('#stories').html(topicList.join(""));
}

function makeStories(topic, obj) {

    var topicList = [];

    $.each(topic, function(key, val) {

        // return;

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
        topicList.push(storyBoard(val, image_root_path, category, categoryName));
    });

    return topicList;
}

function storyBoard(val, image_root_path, category, categoryName) {

    return "<div class='news'>\
        <div class='col-xs-12 col-md-5 left'><a href='"+site_root+default_language+"/article/"+val.slug+"'><img src='"+image_root_path+val.photo.image_path+"' class='img-responsive' style='width:100%'></a></div>\
            <div class='col-xs-12 col-md-7 right'>\
            <ul class='ul-clean'>\
            <li class='cate'><a href='"+site_root+default_language+"/category/"+category.slug+"'>"+categoryName+"</a></li>\
        <li class='title'><a href='"+site_root+default_language+"/article/"+val.slug+"'>"+val.title+"</a></li>\
        <li class='desc' style='clear:both'>"+val.short_desc+"</li>\
        </ul>\
        </div>\
        </div>\
        <div style='clear:both'></div>";

}

function showStoryCategories() {
    $('#stories-categories').toggle();
}

function showMoreStores(obj) {
    // console.log('page: '+ latestStoriesCounter);
    var type = $(obj).attr('type');
    // console.log('object type: ' + type);

    if(type == 'latestStories') {
        // console.log('latest');
        var url = site_root+"api/getLatestStories?page="+latestStoriesCounter;
    } else {
        // console.log('popular');
        var url = site_root+"api/getPopularStories?page="+latestStoriesCounter;
    }

    // console.log(url);

    $("#stories-loading-spinner").show().html('loading ...');
    $.ajax({
        url: url,
        dataType: 'json',
        success: function(json) {
            // console.log(json);
            if(type == 'latestStories') {
                // console.log('latest');
                var stories = $('#head').children('li:nth-child(1)');
            } else {
                // console.log('popular');
                var stories = $('#head').children('li:nth-child(2)');
            }
            var topicList = makeStories(json, stories);
            $('#stories').append(topicList);
            $('#stories-loading-spinner').hide();
        }
    });
}