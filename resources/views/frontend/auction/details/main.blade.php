@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/jquery/jquery.countdown.min.js') }}"></script>

    <hr style="padding: 0; margin:0">

    <div class="row auction auction-detail" id="home-content">

        <div id="left">

            <div id="block" style="border: 0px !important">

                <div id="category-head">

                </div>

                <div style="clear: both"></div>

                {{--<div class="hidden-md hidden-lg">--}}
                <div class="">

                    <div class="pre-auction-block">
                        <div class="store-name"><img src="{{ asset('images/company_logo/christie_logo.jpg') }}"><span>伦敦佳士得</span></div>
                        <div class="more"><a href="{{ route('frontend.auction.house', ['slug' => 'christies']) }}"">查看更多</a></div>
                        <div class="series">
                            <div class="title">拍卖预展 - 2017春季拍卖会</div>
                            <div class="input-group selection">
                                <span class="input-group-addon" id="basic-addon1">請選擇 :</span>
                                <select class="form-control" id="sel1" aria-describedby="basic-addon1">
                                    <option>中國當代水墨畫 1</option>
                                    <option>中國當代水墨畫 2</option>
                                    <option>中國當代水墨畫 3</option>
                                </select>
                            </div>

                            <div class="misc">
                                <div class="cell-name">中國當代水墨畫 1</div>

                                <div style="float:left">2017年05月09日 17:30</div>
                                <div id="date-counter-1" class="date-counter" style="float:left"></div>
                                <div style="clear:both"></div>
                                拍卖地点：<span>8 King Street St. James’s London SW1Y 6QT</span><br>
                                拍卖总数：<span>136</span> 件<br>

                            </div>

                            <script type="text/javascript">
                                $("#date-counter-1")
                                    .countdown("2017/05/09 17:30:00", function(event) {
                                        $(this).text(
                                            event.strftime('(%D days %H:%M:%S)')
                                        );
                                    });
                            </script>

                            <div class="row">
                                <?php for($i=0; $i<10; $i++) { ?>
                                <div class="col-xs-6 col-md-3 lot">
                                    <img src="{{ asset('images/auction-p1.jpg') }}" class="img-responsive">
                                    <div class="lot-detail">
                                        <div>Lot 102 Title Title Title Title Title Title Title Title Title</div>
                                        <div>Value: 100000000 -2300000000</div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>


                </div>

                {{--<div class="hidden-xs hidden-sm col-md-9 col-lg-9">--}}

                    {{--@include('frontend.auction.index.content-left')--}}

                {{--</div>--}}

            </div>

        </div>

        <div class="hidden-xs hidden-sm col-md-3 col-lg-3">

            @include('frontend.auction.pre.content-right')

        </div>

    </div>

@endsection