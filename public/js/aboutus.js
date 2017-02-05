// get information when ready
$( document ).ready(function() {
    var api_path = site_root+"api/about-us";
    getInfo(api_path);
});

function showContent() {
    makeCategoriesList();
    makeAboutUS();
}

function makeAboutUS() {
    console.log($apiResult);
    var aboutus = $apiResult.aboutUS;
    console.log(aboutus.aboutUSContent);

    $('#aboutus-content').html(aboutus.content);
    $('#aboutus-address').html(aboutus.address);
    $('#aboutus-tel').html(aboutus.tel);
    $('#aboutus-fax').html(aboutus.fax);
    $('#aboutus-email').html(aboutus.email);
    $('#aboutus-map').html(aboutus.googleMap);

}