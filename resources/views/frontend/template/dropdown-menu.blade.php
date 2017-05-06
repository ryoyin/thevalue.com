<style>
    .dropdown-toggle,  .dropdown-toggle:hover {
        text-align: center;
        background-color: inherit !important;
    }
    .navbar-default .navbar-nav>.open>a, .navbar-default .navbar-nav>.open>a:focus, .navbar-default .navbar-nav>.open>a:hover {
        background-color: inherit !important;
    }
</style>
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top head-dropdown-menu">
    <div class="container">
        <div class="navbar-header">
            <ul class="nav navbar-nav" style="float: left; text-align: center; position:relative; z-index: 10; width: 100px;">
                <li class="dropdown lang-list-first">
                    <?php
                        $current_lang_array = array(
                            'sim' => '簡',
                            'en' => 'EN',
                            'trad' => '繁'
                        );
                    ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-globe" aria-hidden="true"></i> {{ $current_lang_array[App::getLocale()] }}</a>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="redirectLang(this, 'en');">English</a></li></li>
                        <li><a href="#" onclick="redirectLang(this, 'trad');">繁體中文</a></li>
                        <li><a href="#" onclick="redirectLang(this, 'sim');">简体中文</a></li>
                    </ul>
                </li>
            </ul>
            <div style="position: absolute; top: 17px; width: 100%; text-align: center; z-index: 17px;"><a class="navbar-brand" style="float: none;" href="{{ route('frontend.index') }}">THE VALUE</a></div>
            <div style="width: 20%; float:right;">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav head-dropdown-menu-list">
                @foreach($categories as $category)
                    <li><a href='{{ route('frontend.category', ['slug' => $category['slug']]) }}'>{{ $category['name'] }}</a></li>
                @endforeach
                <li><a href='{{ route('frontend.category', ['slug' => 'videos']) }}'>@lang('thevalue.video')</a></li>
            </ul>
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
