$( document ).ready(function() {
    getInfo();
});

var $categoriesArray;
var $bannersArray;
var $featuredArticlesArray;

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
                case 'featuredArticles':
                    $featuredArticlesArray = val;
                    break;
            }
        });

        showContent();
    });

}

function showContent() {
    makeCategoriesList();
    makeBanners();
    makeFeaturedArticles();
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

function makeFeaturedArticles() {

    var featuredArticles = [];
    // console.log($featuredArticlesArray);
    $.each($featuredArticlesArray, function(key, val) {
        var category = getCategoryByID(val.category_id);

        var categoryName = category.name;

        if(category.name != category.default_name) categoryName = category.default_name+" "+category.name;

        featuredArticles.push("<ul class='col-md-3 ul-clean'><li><img src='"+val.photo.image_path+"' class='img-responsive'></li><li>"+categoryName+"</li><li>"+val.title+"</li></ul>");
    });

    $('#featured-article').html(featuredArticles.join(""));

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
