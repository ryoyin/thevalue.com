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

/*function makeArticlePhotos() {
    var articlePhotos = $apiResult.articlePhotos;

    var indicators = [];
    var topBanners = [];

    $.each(articlePhotos, function(key, val) {
        var indicatorClass = "";
        if(key == 0) indicatorClass = "class='active'";
        indicators.push("<li data-target='#carousel-main-banner' data-slide-to='"+key+"' "+indicatorClass+"></li>");

        var bannerClass = "";
        if(key == 0) bannerClass = "active";
        topBanners.push("<div class='item "+bannerClass+"'><img src='"+site_root+val.image_path+"' alt='"+val.alt+"' class='img-responsive'><div class='carousel-caption'>"+val.alt+"</div></div>");
    });

    $('.carousel-indicators').html(indicators.join(""));
    $('.carousel-indicators').children('li').css('margin', '0 3px');

    $('.carousel-inner').html(topBanners.join(""));
}*/

/*function makeArticle() {
    var article = $apiResult.articleDetails;
    // console.log(article);
    // console.log($apiResult.article);

    $('#article-title').html(article.title);
    $('#article-note').html(article.note);
    $('#article-date').html($apiResult.published_at);
    $('#article-desc').html(article.description);
    $('#article-author').html(article.author);
    $('#article-source').html(article.source);
    $('#article-photographer').html(article.photographer);
    $('.article-shares').html($apiResult.article['shares']+ ' shares');
}*/

/*function makeTags() {
    var tags = $apiResult.tags;

    var tagsli = [];
    $.each(tags, function(key, val) {
        tagsli.push('<li><a href="'+site_root+default_language+'/tag/'+val.slug+'">'+val.name+'</a></li>')
    });

    $('.tag').children('ul').html(tagsli.join(""));
    // console.log('test');
}*/

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
            <div class="col-md-5 left"><img src="'+image_root_path+val.photo.image_path+'" class="img-responsive"></div>\
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

