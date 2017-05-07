@extends('frontend.template.layout')

@section('content')

    <style>

        #left {
            padding-right: inherit !important;
        }

        .pre-auction-block {
            /*display: none;*/
            width: auto;
            position: relative;
        }

        .active {
            display: block !important;
        }

    </style>

    <script src="{{ asset('js/jquery/jquery.countdown.min.js') }}"></script>

    <hr style="padding: 0; margin:0">

    <div class="row auction auction-company" id="home-content">

        <div id="left">

            <div id="block" style="border: 0px !important">

                <div class="logo">
                    <img src="{{ asset('images/company_logo/christie_logo.jpg') }}">
                    <div class="name">伦敦佳士得</div>
                </div>

                <div id="category-head" class="tab">
                    <ul>
                        <li><a href="{{ route('frontend.auction.pre') }}" class="tab-1 active">@lang('thevalue.pre-auction')</a></li>
                        <li><a href="{{ route('frontend.auction.post') }}" class="tab-2">@lang('thevalue.post-auction')</a></li>
                        <li><a href="#" class="tab-3">@lang('thevalue.about-us')</a></li>
                    </ul>
                </div>

                <div style="clear: both"></div>

                <div class="pre-auction-block pre-ab-1">

                    <div class="series">

                        <div class="input-group selection">
                            <span class="input-group-addon" id="basic-addon1">請選擇 :</span>
                            <select class="form-control" id="sel1" aria-describedby="basic-addon1">
                                <option>2017春季拍卖会 1</option>
                                <option>2017春季拍卖会 2</option>
                                <option>2017春季拍卖会 3</option>
                            </select>
                        </div>

                        <div class="a-detail">
                            <div class="title">伦敦邦瀚斯2017骑士桥五月拍卖会</div>
                            <div class="ele">预展时间：2017年5月4日-7日</div>
                            <div class="ele">拍卖时间：2017年05月08日-09日</div>
                        </div>
                        <!-- Swiper -->
                        <div class="swiper-container">

                            <div class="swiper-wrapper">
                                <?php for($i=0; $i<2; $i++) { ?>
                                <div class="swiper-slide">
                                    <div class="row">
                                        <div class="col-xs-5"><img src="{{ asset('images/auction_p1.jpg') }}" class="img-responsive"></div>
                                        <div class="col-xs-7 detail">

                                            <div class="misc">

                                                <div class="cell-name">中國當代水墨畫</div>
                                                拍卖地点：<span>8 King Street St. James’s London SW1Y 6QT</span><br>
                                                拍卖总数：<span>136</span> 件<br>

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

                                        <div class="col-xs-7 detail bottom">
                                            <div class="misc">
                                                <div class="sepline"></div>
                                                <div>2017年05月09日 17:30</div>
                                                <div id="date-counter-1" class="date-counter"></div>

                                                <button class="btn btn-primary">觀看展品</button>

                                            </div>
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


            </div>

        </div>

        <div class="hidden-xs hidden-sm col-md-3 col-lg-3">

            @include('frontend.auction.pre.content-right')

        </div>

    </div>

@endsection