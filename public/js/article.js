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

        popularStoriesList.push('\
        <div class="col-xs-6 col-sm-4 col-md-12 col-lg-12 popular-news-block">\
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

function subscription() {
    var email = $('#subsciprtion-email').val();
    var api_email_path = site_root+"share-the-value";
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method: "POST",
        url: api_email_path,
        data: { email: email },
        success:function(data) {
            $('#article-share-the-value-sent-email').show(function() {
                window.setTimeout(function() {
                    $('#subsciprtion-email').val('');
                    $('#article-share-the-value-sent-email').hide();
                },5000);
            });
        },
        error: function(xhr) {
            // console.log(xhr.responseText.email);
            $('#article-share-the-value-invalid-email').show(function() {
                window.setTimeout(function() {
                    // $('#subsciprtion-email').val('');
                    $('#article-share-the-value-invalid-email').hide();
                },5000);
            });
        }
    });
}