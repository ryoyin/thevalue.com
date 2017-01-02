@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/article.js') }}"></script>

    @include('frontend.homepage.carousel')

    <div class="row" id="article-content">

        <div class="col-md-9" id="left">
            <ul class='ul-clean'>
                <li class='title'>【HB FORECAST】中港台潮流業界 10 人談﹣2016/17 年總力回顧及展望（香港篇）</li>
                <li class='notes'>香港篇找來資深設計師、時裝編輯以及 Sneaker Collector，由 Saint Laurent 談到 Outdoor 文化以及 Nike Mag。</li>
                <ul class='misc ul-clean'>
                    <li class='pull-left'>by <span>Stan</span> Nov 24, 2016 </li>
                <li class='pull-right'>
                    <ul class='ul-clean share'>
                    <li><i class='fa fa-envelope' aria-hidden='true'></i></li>
                    <li><i class='fa fa-wechat' aria-hidden='true'></i></li>
                    <li><i class='fa fa-weibo' aria-hidden='true'></i></li>
                    <li><i class='fa fa-twitter' aria-hidden='true'></i></li>
                    <li><i class='fa fa-facebook-f' aria-hidden='true'></i></li>
                    <li><span>416 shares</span></li>
                </ul>
                </li>
                </ul>
                <li class='desc' style='clear:both'>
                    剛剛踏入 2017 年，我們編輯部特意找來 10 位來自中港台的時裝潮流界的 Insiders 一同回顧過去一年的潮流實況以及展望 2017 年個新興潮流元素，同時分享了他們過去一年最滿意的入手經驗，了解真正潮流 KOL 們對於時裝、球鞋之買物的理念。在香港區，我們找來香港時裝王國 I.T 的創作總監 Wallace、日本戶外時裝雜誌《GOOUT》國際中文版以及本地潮誌《TAO》編集長 Kenneth 以及本地元祖級球鞋 Collector 以及 HK-Kicks.com 主理人 Horace，從設計師、時裝編輯以及球鞋收藏達人的 3 個角度重新審視 2016/17 之潮流走勢。
                </li>
            </ul>
        </div>

        <div class="col-md-3" id="right">
            <div class="title">Popular News</div>
            <ul>
                <li></li>
            </ul>
        </div>

    </div>

@endsection