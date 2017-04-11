// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/disclaimer";
    getInfo(api_path);
});

function showContent() {
    makeCategoriesList();
    makeAboutUS();
}

function makeAboutUS() {
    console.log($apiResult);
    var disclaimer = $apiResult.disclaimer;
    console.log(disclaimer.aboutUSContent);

    $('#static-page-title').html(disclaimer.title);
    $('#static-page-content').html(disclaimer.content);
}