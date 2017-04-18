// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/article/"+slug;
    getInfo(api_path);
});

function showContent() {
    // makeCategoriesList();
    // makeArticlePhotos();
    // makeArticle();
    // makeTags();
    makePopularStories();
}

function makePopularStories() {
    var popularStories = $apiResult.popularStories;
    // console.log(popularStories);

    var popularStoriesList = [];
    $.each(popularStories, function(key, val) {
        var category = getCategoryByID(val.category_id);

        var categoryName = category.name;

        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        if(val.photo.s3) {
            var image_root_path = s3_root;
        } else {
            var image_root_path = site_root;
        }

        popularStoriesList.push('<div style="clear:both"></div>\
            <div class="popular-news-block">\
            <div id="popular-news">\
            <div class="col-md-5 left"><a href="'+site_root+default_language+'/article/'+val.slug+'"><img src="'+image_root_path+val.photo.image_path+'" class="img-responsive"></a></div>\
            <div class="col-md-7 right">\
            <ul class="ul-clean">\
            <li><a href="'+site_root+default_language+'/category/'+category.slug+'">'+categoryName+'</a></li>\
        <li><a href="'+site_root+default_language+'/article/'+val.slug+'">'+val.title+'</a></li>\
        </ul>\
        </div>\
        </div>\
        </div>');
    });

    $('#popular-news').html(popularStoriesList.join(""));

}

