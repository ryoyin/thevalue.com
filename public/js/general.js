//set site information
var site_root = window.location.href;
var sr_split = site_root.split('/');

switch(sr_split[2]) {
    case '192.168.88.102':
        site_root = 'http://192.168.88.102/thevalue.com/public/';
        break;
    case 'localhost':
        site_root = 'http://localhost/thevalue.com/public/';
        break;
    case 'ryoyin':
        site_root = 'http://ryoyin.ddns.net/thevalue.com/public/';
        break;
    default:
        site_root = 'http://www.thevalue.com/';
}

//global api result
var $apiResult;

var default_language = Cookies.get('lang');
// console.log(default_language);
if(typeof default_language == 'undefined') default_language = 'trad';
// console.log(default_language);
$(document).ready(function() {
    $('#global-lang-'+default_language).addClass('lang-active');
});


function showLang() {
    $('#global-lang').children('li').show();
    $('#global-lang').removeClass('close').addClass('open');
}

function changeLang(obj, lang) {
    if($('#global-lang').hasClass('open')) {
        Cookies.set('lang', lang);
        location.reload();
    }
}

function showSearchBar() {
    $('#search-block').slideToggle(function() {
        $('#search-block').children('input').focus();
    });
}

function searchme(obj, e) {
    if (e.keyCode == 13) {
        var search = $(obj).val();
        Cookies.set('search', search);
        window.location = site_root+"search";
    }
}

function getInfo(api_path) {

    $.ajaxSetup({
        headers : {
            'Content-Language' : default_language
        }
    });

    // console.log(api_path);

    $.getJSON( api_path, function( data ) {
        $apiResult = data;
        // console.log($apiResult);

        showContent();
    });

}

function makeCategoriesList() {
    var categories = $apiResult.categories;
    // console.log(categories);

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

