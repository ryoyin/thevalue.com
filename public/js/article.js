//get api result
function getInfo() {

    //set header get content = traditional
    $.ajaxSetup({
        headers : {
            'Content-Language' : 'trad'
        }
    });

    var api_path = site_root+"api/article/"+slug;

    $.getJSON( api_path, function( data ) {
        $apiResult = data;
        showContent();
    });

}

function showContent() {
    makeCategoriesList();
    makeArticlePhotos();
    makeArticle();
    makeTags();
    makePopularStories();
}

function makeCategoriesList() {
    var categories = $apiResult.categories;

    var categoriesItems = [];
    $.each(categories, function(key, val) {
        if(val.parent == null) {
            categoriesItems.push("<li><a href='"+site_root+"category/"+val.slug+"'>"+val.name+"</a></li>")
        }
    });
    $('#categoriesList').html(categoriesItems.join(""));
    $('#stories-categories').html(categoriesItems.join(""));
}

function makeArticlePhotos() {
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
}

function makeArticle() {
    var article = $apiResult.articleDetails;
    console.log(article);

    $('#article-title').html(article.title);
    $('#article-note').html(article.note);
    $('#article-desc').html(article.description);
    $('#article-title').html(article.title);
    $('#article-title').html(article.title);
}

function makeTags() {
    var tags = $apiResult.tags;

    var tagsli = [];
    $.each(tags, function(key, val) {
        tagsli.push('<li><a href="'+site_root+'tag/'+val.slug+'">'+val.name+'</a></li>')
    });

    $('.tag').children('ul').html(tagsli.join(""));
    console.log('test');
}

function makePopularStories() {
    var popularStories = $apiResult.popularStories;
    console.log(popularStories);

    var popularStoriesList = [];
    $.each(popularStories, function(key, val) {
        var category = getCategoryByID(val.category_id);

        var categoryName = category.name;

        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        popularStoriesList.push('<div style="clear:both"></div>\
            <div class="popular-news-block">\
            <div id="popular-news">\
            <div class="col-md-5 left"><img src="'+site_root+val.photo.image_path+'" class="img-responsive"></div>\
            <div class="col-md-7 right">\
            <ul class="ul-clean">\
            <li><a href="'+site_root+'category/'+category.slug+'">'+categoryName+'</a></li>\
        <li><a href="'+site_root+'article/'+val.slug+'">'+val.title+'</a></li>\
        </ul>\
        </div>\
        </div>\
        </div>');
    });

    $('#popular-news').html(popularStoriesList.join(""));

}

function getCategoryByID(id) {
    var categories = $apiResult.categories;
    for(var i = 0; i < categories.length; i++ ) {

        if(categories[i].id == id) {
            var category = categories[i];
            return category;
        }
    }
}

