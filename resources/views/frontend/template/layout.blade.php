<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Static Top Navbar Example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('assets/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/navbar-static-top.css') }}" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="{{ asset('assets/js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
    <script src="{{ asset('assets/js/ie-emulation-modes-warning.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://use.fontawesome.com/13a5048f89.js"></script>

    <!-- custom -->
    <link href="{{ asset('css/web.css') }}" rel="stylesheet">

</head>

<body>

<div class="container">

    <div id="header-bar">

        <div class="pull-left">
            <i class="fa fa-globe" aria-hidden="true"></i> 香港
        </div>

        <div class="pull-right">
            <ul id="header-bar-misc" class="ul-clean">
                <li><i class="fa fa-envelope" aria-hidden="true"></i></li>
                <li><i class="fa fa-wechat" aria-hidden="true"></i></li>
                <li><i class="fa fa-weibo" aria-hidden="true"></i></li>
                <li><i class="fa fa-twitter" aria-hidden="true"></i></li>
                <li><i class="fa fa-facebook-f" aria-hidden="true"></i></li>
            </ul>
        </div>

        <div class="pull-center" style="text-align: center">
            THE VALUE.COM
        </div>

    </div> <!--/header-bar-->

    <div style="clear:both"></div>

    <div class="row" id="header-menu">
        <div class="col-md-7">
            <ul class="ul-clean">
                <li>時下焦點</li>
                <li>專題專訪</li>
                <li>拍賣資訊</li>
                <li>全球藝廊</li>
                <li>數據中心</li>
                <li>LIVE SMART</li>
                <li>視頻</li>
            </ul>
        </div>
        <div class="col-md-5 text-right">
            <i class="fa fa-search" aria-hidden="true"></i>
        </div>
    </div> <!-- /header-menu-->

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
                <img src="{{ asset('images/banners/01-authenticity-chow-1920.jpg.webrend.1125.2000.jpeg') }}" alt="...">
                <div class="carousel-caption">
                    <!-- caption here -->
                </div>
            </div>
            <div class="item">
                <img src="{{ asset('images/banners/tiqi-1920-e42543.jpg.webrend.1125.2000.jpeg') }}" alt="...">
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
            <li><img src="" class="img-responsive"></li>
            <li>Title 標題</li>
            <li>text text text text text text text text text text</li>
        </ul>
        <ul class="col-md-3 ul-clean">
            <li><img src="" class="img-responsive"></li>
            <li>Title 標題</li>
            <li>text text text text text text text text text text</li>
        </ul>
        <ul class="col-md-3 ul-clean">
            <li><img src="" class="img-responsive"></li>
            <li>Title 標題</li>
            <li>text text text text text text text text text text</li>
        </ul>
        <ul class="col-md-3 ul-clean">
            <li><img src="" class="img-responsive"></li>
            <li>Title 標題</li>
            <li>text text text text text text text text text text</li>
        </ul>
    </div> <!-- news-brief -->

    <div class="row">

        <div id="home-left-col" class="col-md-9">

            <ul id="home-left-head" class="ul-clean">
                <li class="pull-left">Latest Stories</li>
                <li class="pull-left">Popular Stories</li>
                <li class="pull-right">Categories</li>
            </ul> <!-- home-left-head -->

            <div id="home-left-block">
                <div class="col-md-5" class="left-image"><img src=""></div>
                <div class="col-md-7">
                    <ul id="right-content" class="ul-clean">
                        <li class="cate">Design 設計</li>
                        <li class="title">Title Title Title Title Title Title Title Title Title Title </li>
                        <li class="misc">
                            <ul class="ul-clean">
                                <li class="pull-left">by Stan Nov 24, 2016</li>
                                <li class="pull-right"> fb twitter weibo mail</li>
                            </ul>
                        </li>
                        <li class="desc">desc desc desc desc desc desc desc desc desc desc desc desc desc desc desc desc desc desc </li>
                    </ul>
                </div> <!-- right-content -->
            </div> <!-- home-left-block -->

        </div> <!-- home-left-col -->

        <div id="home-right-col" class="col-md-3">

            <ul class="advert ul-clean">
                <li><img src=""></li>
                <li><img src=""></li>
            </ul> <!-- advert -->

        </div> <!-- home-right-col -->

    </div>

    <div id="footer" class="pull-right">Copyright (c) 2017 thevalue.com</div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="{{ asset('dist/js/bootstrap.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('assets/js/ie10-viewport-bug-workaround.js') }}"></script>
</body>
</html>
