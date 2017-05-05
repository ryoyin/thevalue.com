<div class="pre-auction-block pre-ab-1">

    <div class="series">

            <div class="title">2017春季拍卖会 1</div>
            <div class="datetime">拍賣日期: 2017年05月08日至09日</div>
            <!-- Swiper -->
            <div class="swiper-container">

                <div class="swiper-wrapper">
                    <?php for($i=0; $i<2; $i++) { ?>
                    <div class="swiper-slide">
                        <div class="row">
                            <div class="col-xs-5"><img src="{{ asset('images/auction-p1.jpg') }}" class="img-responsive"></div>
                            <div class="col-xs-7 detail">

                                <a class="cell-name" href="#">伦敦佳士得</a><br>

                                <div class="misc">
                                    <div class="cell-name">中國當代水墨畫</div>

                                    <div>2017年05月09日 17:30</div>
                                    <div id="date-counter-1" class="date-counter"></div>
                                    <div style="height: 15px"></div>
                                    拍卖地点：<span>8 King Street St. James’s London SW1Y 6QT</span><br>
                                    拍卖总数：<span>136</span> 件<br>
                                    <button class="btn btn-primary">觀看展品</button>

                                </div>

                                <script type="text/javascript">
                                    $("#date-counter-1")
                                        .countdown("2017/05/09 17:30:00", function(event) {
                                            $(this).text(
                                                event.strftime('%D days %H:%M:%S')
                                            );
                                        });
                                </script>

                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Add Scrollbar -->
                <div class="swiper-scrollbar"></div>

            </div>

    </div>
</div>
