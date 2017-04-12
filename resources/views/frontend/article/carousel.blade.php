<?php

    $carouselIndicators = "";
    $topBanners = "";

    foreach($articlePhotos as $photoKey => $photo) {
        $indicatorClass = "";
        if($photoKey == 0) $indicatorClass = "class='active'";
        $carouselIndicators .= "<li data-target='#carousel-main-banner' data-slide-to='".$photoKey."' ".$indicatorClass."></li>";

        $bannerClass = "";
        if($photoKey == 0) $bannerClass = "active";
//        print_r($photo);
        $topBanners .= "<div class='item ".$bannerClass."'><img src='".asset($photo['image_path'])."' alt='".$photo['alt']."' class='img-responsive'><div class='carousel-caption'>".$photo['alt']."</div></div>";
    }

?>
<div id="carousel-main-banner" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        {!! $carouselIndicators !!}
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <!-- another title -->
        {!! $topBanners !!}
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-main-banner" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-main-banner" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div> <!-- carousel-main-banner -->