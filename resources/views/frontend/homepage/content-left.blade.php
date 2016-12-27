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