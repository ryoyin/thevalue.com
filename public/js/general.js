//set site information
var site_root = window.location.href;
var sr_split = site_root.split('/');

if(sr_split[2] == 'localhost') {
    site_root = 'http://localhost/public/';
} else {
    site_root = 'http://ryoyin.ddns.net/thevalue.com/public/';
}

//global api result
var $apiResult;

function getInfo(api_path) {

    $.ajaxSetup({
        headers : {
            'Content-Language' : 'trad'
        }
    });

    $.getJSON( api_path, function( data ) {
        $apiResult = data;

        showContent();
    });

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

function makeSideBanners() {
    var $sideBannersArray = $apiResult.sideBanners;
    var sideBanners = [];

    $.each($sideBannersArray, function(key, val) {
        sideBanners.push("<li><img src='"+site_root+val.image_path+"' style='width: 100%' class='img-responsive'></li>");
    });

    $('#advert').html(sideBanners.join(""));
}

function getCategoryByID(id) {
    // console.log(id);
    var $categories = $apiResult.categories;
    for(var i = 0; i < $categories.length; i++ ) {

        if($categories[i].id == id) {
            var category = $categories[i];
            return category;
        }
    }
}

