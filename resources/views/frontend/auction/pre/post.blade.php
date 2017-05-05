{{--Post Auction--}}
<div class="subject">@lang('thevalue.post-auction')</div>
<div class="store-name">
    <ul>
        <li onclick="changeAuction('post', 1);">伦敦佳士得 1</li>
        <li onclick="changeAuction('post', 2);">伦敦佳士得 2</li>
    </ul>
</div>

<div style="clear:both"></div>

<div class="post-auction-block post-ab-1">
    <div class="series-title">2017春季拍卖会 1 - 2017年05月08日至09日</div>

<?php for($x=0; $x<2; $x++) { ?>
<!-- Swiper -->
    <div class="swiper-container">

        <div class="swiper-wrapper">
            <?php for($i=0; $i<2; $i++) { ?>
            <div class="swiper-slide">
                <div class="row">
                    <div class="col-xs-5"><img src="{{ asset('images/auction-p1.jpg') }}" class="img-responsive"></div>
                    <div class="col-xs-7 detail">
                        拍卖行名称：伦敦佳士得<br>
                        拍卖总数：136<br>
                        拍卖时间：2017年05月09日 17:30 （北京时间）<br>
                        拍卖地点：8 King Street St. James’s London SW1Y 6QT<br>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Add Scrollbar -->
        <div class="swiper-scrollbar"></div>

    </div>
    <?php } ?>
</div>

<div class="post-auction-block post-ab-2">
    <div class="series-title">2017春季拍卖会 2 - 2017年05月08日至09日</div>

<?php for($x=0; $x<2; $x++) { ?>
<!-- Swiper -->
    <div class="swiper-container">

        <div class="swiper-wrapper">
            <?php for($i=0; $i<2; $i++) { ?>
            <div class="swiper-slide">
                <div class="row">
                    <div class="col-xs-5"><img src="{{ asset('images/auction-p2.jpg') }}" class="img-responsive"></div>
                    <div class="col-xs-7 detail">
                        拍卖行名称：伦敦佳士得<br>
                        拍卖总数：136<br>
                        拍卖时间：2017年05月09日 17:30 （北京时间）<br>
                        拍卖地点：8 King Street St. James’s London SW1Y 6QT<br>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Add Scrollbar -->
        <div class="swiper-scrollbar"></div>

    </div>
    <?php } ?>
</div>