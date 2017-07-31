//global api result
var $apiResult;
var s3_root;

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
        // Cookies.set('lang', lang);
        var url = $(obj).attr('url');
        window.location.href = url;
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

        switch(site_lang) {
            case 'trad':
                var relang = 'hk';
                break;
            case 'sim':
                var relang = 'cn';
                break;
            case 'en':
                var relang = 'en';
                break;
        }

        var target = "https://"+relang+".thevalue.com/search?item="+search;
        window.location = target;
    }
}

function simple_search() {
    var search = $('#sim_search').val();

    switch(site_lang) {
        case 'trad':
            var relang = 'hk';
            break;
        case 'sim':
            var relang = 'cn';
            break;
        case 'en':
            var relang = 'en';
            break;
    }

    var target = "https://"+relang+".thevalue.com/search?item="+search;
    window.location = target;
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
    var api_email_path = site_root+"share-the-value";
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method: "POST",
        url: api_email_path,
        data: { email: email },
        success:function(data) {
            $('#share-the-value-sent-email').show(function() {
                window.setTimeout(function() {
                    $('#share-the-value').modal('toggle');
                    $('#share-email').val('');
                    $('#share-the-value-sent-email').hide();
                },3000);
            });
        },
        error: function(xhr) {
            // console.log(xhr.responseText.email);
            $('#share-the-value-invalid-email').fadeIn();
        }
    });
}

function updateCounter(slug, type) {
    var url = site_root+"api/updateCounter?slug="+slug+"&type="+type;
    $.get( url );
}

$(window).scroll(function(){

    var scrollPX = 120;

    if($('#top-menu-banner').length) {

        var width = $(window).width();

        if(width <= 460) {
            scrollPX = 160;
        } else if(width <= 991) {
            scrollPX = 350;
        } else {
            scrollPX = 400;
        }

    }

    if($(window).scrollTop() > scrollPX){
        $(".head-dropdown-menu").css('display', 'block').addClass('navbar-fixed-top');
    }

    if($(window).scrollTop() < scrollPX){
        $(".head-dropdown-menu").css('display', 'none').removeClass('navbar-fixed-top');;
    }

});