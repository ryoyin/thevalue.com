<div id="header-bar">

    <div class="pull-left" id="global-lang-block" onclick="showLang();">
        <i class="fa fa-globe pull-left" aria-hidden="true"></i>
        <ul id="global-lang" class="ul-clean pull-left close">
            <li id="global-lang-en"><a href="#" onclick="changeLang(this, 'en');">English</a></li>
            <li id="global-lang-trad"><a href="#" onclick="changeLang(this, 'trad');">繁體中文</a></li>
            <li id="global-lang-sim"><a href="#" onclick="changeLang(this, 'sim');">简体</a></li>
        </ul>
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
        <a href="{{ route('frontend.index') }}/">THE VALUE.COM</a>
    </div>

</div> <!--/header-bar-->