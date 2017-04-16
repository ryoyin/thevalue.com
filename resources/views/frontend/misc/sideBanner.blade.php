<ul id="advert" class="ul-clean">
    <?php
        foreach($sideBanners as $banner) {
        $image_path = $banner['s3'] ? config("app.s3_path").$banner['image_path'] : asset($banner['image_path']);
    ?>

        <li><img src='{{ $image_path }}' style='width: 100%' class='img-responsive'></li>

    <?php
        }
    ?>
</ul> <!-- advert -->