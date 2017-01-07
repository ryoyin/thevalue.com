<div class="header">
    <div class="news pull-left">POPULAR NEWS</div>
    <div class="recent pull-right">Recent <i class="fa fa-chevron-down" aria-hidden="true"></i></div>
</div>
<?php for($i=0; $i <6; $i++) { ?>
<div style="clear:both"></div>
<div class="popular-news-block">
    <div class="col-md-5 left"><img src="{{ asset('images/articles/temp/article-01.jpg') }}" class="img-responsive"></div>
    <div class="col-md-7 right">
        <ul class="ul-clean">
            <li>Categories Name</li>
            <li>Title Title Title</li>
        </ul>
    </div>
</div>
<?php } ?>