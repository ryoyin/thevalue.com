<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top head-dropdown-menu">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('frontend.index') }}">THE VALUE</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown lang-list-first">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-globe" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="redirectLang(this, 'en');">English</a></li></li>
                        <li><a href="#" onclick="redirectLang(this, 'trad');">繁體中文</a></li>
                        <li><a href="#" onclick="redirectLang(this, 'sim');">简体中文</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav head-dropdown-menu-list"></ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('frontend.aboutus') }}">@lang('thevalue.contact-us')</a></li>
                {{--<li><a href="{{ route('frontend.disclaimer') }}">@lang('thevalue.disclaimer')</a></li>--}}
                {{--<li class="dropdown lang-list-last">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-globe" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="redirectLang(this, 'en');">English</a></li></li>
                        <li><a href="#" onclick="redirectLang(this, 'trad');">繁體中文</a></li>
                        <li><a href="#" onclick="redirectLang(this, 'sim');">简体中文</a></li>
                    </ul>
                </li>--}}
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
