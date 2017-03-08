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
    case 'ryoyin.ddns.net':
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
        window.location.href = site_root+lang;
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

function makeFBMeta() {
    var fbMeta = $apiResult.fbMeta;

    console.log(fbMeta);

    var meta = "<meta property=\"og:site_name\" content=\""+fbMeta.site_name+"\">"+
    "<meta property=\"og:url\" content=\""+fbMeta.url+"\">"+
    "<meta property=\"og:type\" content=\""+fbMeta.type+"\">"+
    "<meta property=\"og:title\" content=\""+fbMeta.title+"\">"+
    "<meta property=\"og:description\" content=\""+fbMeta.description+"\">"+
    "<meta property=\"og:image\" content=\""+site_root+fbMeta.image+"\">"+
    "<meta property=\"fb:app_id\" content=\""+fbMeta.app_id+"\">";
    // "<meta property=\"fb:admins\" content=\"1136380453091512\">"
    $('head').append(meta);
    // console.log(meta);
}