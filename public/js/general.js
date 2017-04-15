//set site information
var site_root = window.location.href;
var sr_split = site_root.split('/');

// console.log(sr_split[2]);

switch(sr_split[2]) {
    case '192.168.88.102':
        site_root = 'http://192.168.88.102/thevalue.com/public/';
        var site_lang = (sr_split[5]);
        break;
    case 'localhost':
        site_root = 'http://localhost/thevalue.com/public/';
        var site_lang = (sr_split[5]);
        var cat_slug = (sr_split[7]);
        break;
    case 'ryoyin.ddns.net':
        site_root = 'http://ryoyin.ddns.net/thevalue.com/public/';
        var site_lang = (sr_split[5]);
        break;
    case 'thevalue.com':
        window.location.href = "http://www.thevalue.com";
        break;
    default:
        site_root = 'http://www.thevalue.com/';
        var site_lang = (sr_split[3]);
        var cat_slug = (sr_split[5]);
}
// console.log(cat_slug);

var video_loc = '';
switch (site_lang) {
    case 'en':
        video_loc = 'Videos';
        break;
    case 'trad':
        video_loc = '視頻';
        break;
    case 'sim':
        video_loc = '视频';
        break;
}

//global api result
var $apiResult;
var s3_root;
/*
var default_language = Cookies.get('lang');
// console.log(default_language);
if(typeof default_language == 'undefined') default_language = 'trad';
*/

// console.log(site_lang);

default_language = site_lang;
Cookies.set('lang', default_language);

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
        window.location.href = site_root+lang;
        // console.log(site_root+lang);
    }
}

function redirectLang(obj, lang) {
    Cookies.set('lang', lang);
    window.location.href = site_root+lang;
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

function simple_search() {
    var search = $('#sim_search').val();
    Cookies.set('search', search);
    window.location = site_root+"search";
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
        s3_root = $apiResult.s3_path;
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
            categoriesItems.push("<li><a href='"+site_root+site_lang+"/category/"+val.slug+"'>"+val.name+"</a></li>")
        }
    });

    categoriesItems.push("<li><a href='"+site_root+site_lang+"/category/videos'>"+video_loc+"</a></li>")

    $('#categoriesList').html(categoriesItems.join(""));
    $('#stories-categories').html(categoriesItems.join(""));
    $('.head-dropdown-menu-list').html(categoriesItems.join(""));
}

function makeSideBanners() {
    var $sideBannersArray = $apiResult.sideBanners;
    var sideBanners = [];

    $.each($sideBannersArray, function(key, val) {
        if(val.s3) {
            var image_root_path = s3_root;
        } else {
            var image_root_path = site_root;
        }
        sideBanners.push("<li><img src='"+image_root_path+val.image_path+"' style='width: 100%' class='img-responsive'></li>");
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

function shareme() {
    var email = $('#share-email').val();
    $('#share-the-value-invalid-email').hide();
    if(validate_email(email) && email.trim() != '') {
        var api_email_path = site_root+"api/share-the-value";
        $.ajax({
            method: "POST",
            url: api_email_path,
            data: { email: email }
        }).done(function() {
            $('#share-the-value-sent-email').fadeIn(3000, function() {
                $('#share-the-value').modal('toggle');
                $('#share-email').val('');
                $('#share-the-value-sent-email').hide();
            });
        });
    } else {
        $('#share-the-value-invalid-email').fadeIn();
    }
}

function validate_email(email) {
    var input = document.createElement('input');
    input.type='email';
    input.value=email;

    return input.checkValidity();
}

$(window).scroll(function(){
    if($(window).scrollTop() > 120){
        $(".head-dropdown-menu").css('display', 'block')
    }

    if($(window).scrollTop() < 120){
        $(".head-dropdown-menu").css('display', 'none');
    }
});