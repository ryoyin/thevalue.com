<div id="carousel-main-banner" class="carousel slide" data-ride="carousel">
    <?php
        $indicators = "";
        $banners = "";

        foreach($topBanners as $bannerIndex => $banner) {

            $indicatorClass = "";

            if($bannerIndex == 0) $indicatorClass = "class='active'";
            $indicators .= "<li data-target='#carousel-main-banner' data-slide-to='".$bannerIndex."' ".$indicatorClass."></li>";


            $bannerClass = "";
            if($bannerIndex == 0) $bannerClass = "active";

            $image_root_path = $banner['s3'] ? config("app.s3_path").$banner['image_path'] : asset($banner['image_path']);

            $banners .= "<div class='item ".$bannerClass."'><img src='".$image_root_path."' alt='...'><div class='carousel-caption'>".$banner['alt']."</div></div>";
        }
    ?>
    <!-- Indicators -->
    <ol class="carousel-indicators">{!! $indicators !!}</ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">{!! $banners !!}</div>

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