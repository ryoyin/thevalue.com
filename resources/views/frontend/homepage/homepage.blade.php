@extends('frontend.template.layout')

@section('content')
<div id="carousel-main-banner" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#carousel-main-banner" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-main-banner" data-slide-to="1"></li>
        <li data-target="#carousel-main-banner" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="{{ asset('images/banners/banner-01.jpeg') }}" alt="...">
            <div class="carousel-caption">
                <!-- caption here -->
            </div>
        </div>
        <div class="item">
            <img src="{{ asset('images/banners/banner-02.jpeg') }}" alt="...">
            <div class="carousel-caption">
                <!-- caption here -->
            </div>
        </div>
        <!-- another title -->
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

<div class="row" id="news-brief">
    <ul class="col-md-3 ul-clean">
        <li><img src="{{ asset('images/articles/temp/article-01.jpg') }}" class="img-responsive"></li>
        <li>Title 標題</li>
        <li>Marvel’s The Inhumans Series Heads to IMAX Theaters and ABC!</li>
    </ul>
    <ul class="col-md-3 ul-clean">
        <li><img src="{{ asset('images/articles/temp/article-02.jpg') }}" class="img-responsive"></li>
        <li>Title 標題</li>
        <li>Marvel’s Defenders Cast is Coming Together in New Photos</li>
    </ul>
    <ul class="col-md-3 ul-clean">
        <li><img src="{{ asset('images/articles/temp/article-03.jpg') }}" class="img-responsive"></li>
        <li>Title 標題</li>
        <li>Full Justice League Movie Cast Revealed</li>
    </ul>
    <ul class="col-md-3 ul-clean">
        <li><img src="{{ asset('images/articles/temp/article-04.jpg') }}" class="img-responsive"></li>
        <li>Title 標題</li>
        <li>Explore the Multiverse with Our Benedict Cumberbatch Doctor Strange Video Interview</li>
    </ul>
</div> <!-- news-brief -->

<hr>

<div class="row" id="home-content">

    <div id="left" class="col-md-9">

        <ul id="head" class="ul-clean">
            <li class="pull-left"><i class="fa fa-clock-o" aria-hidden="true"></i> Latest Stories</li>
            <li class="pull-left"><i class="fa fa-line-chart" aria-hidden="true"></i> Popular Stories</li>
            <li class="pull-right">Categories <i class="fa fa-chevron-circle-down" aria-hidden="true"></i></li>
        </ul>

        <div style="clear:both;"></div>

        <div id="block">
            <?php for($i = 0; $i<4; $i++): ?>
            <div class="news">
                <div class="col-md-5 left"><img src="{{ asset('images/articles/temp/article-05.jpg') }}" class="img-responsive"></div>
                <div class="col-md-7 right">
                    <ul class="ul-clean">
                        <li class="cate">Design 設計</li>
                        <li class="title">Title Title Title Title Title Title Title Title Title Title </li>
                        <ul class="misc ul-clean">
                            <li class="pull-left">by <span>Stan</span> Nov 24, 2016 </li>
                            <li class="pull-right">
                                <ul class="ul-clean share">
                                    <li><i class="fa fa-envelope" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-wechat" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-weibo" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-twitter" aria-hidden="true"></i></li>
                                    <li><i class="fa fa-facebook-f" aria-hidden="true"></i></li>
                                    <li><span>416 shares</span></li>
                                </ul>
                            </li>
                        </ul>
                        <li class="desc" style="clear:both">A new photo from the upcoming Wonder Woman movie has debuted online (via EW) featuring Gal Gadot’s title hero gaining one of her key accessories – her sword.</li>
                    </ul>
                </div>
            </div>
            <div style="clear:both"></div>
            <?php endfor ?>
        </div>

    </div>

    <div id="right" class="col-md-3">

        <ul class="advert ul-clean">
            <li><img src="{{ asset('images/advert/advert-01.png') }}" style="width: 100%" class="img-responsive"></li>
        </ul> <!-- advert -->

    </div> <!-- home-right-col -->

</div>
@endsection