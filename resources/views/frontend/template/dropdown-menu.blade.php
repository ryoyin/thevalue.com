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
{{--navbar-fixed-top--}}
<nav class="navbar navbar-default head-dropdown-menu">
    <div class="container">
        <div class="navbar-header">
            <div style="" class="navbar-brand navbar-custom-logo"><a class="navbar-brand" style="float: none;" href="{{ route('frontend.index') }}">THE VALUE</a></div>
            <ul class="nav navbar-nav lang-nav">
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
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li
                                    @if($localeCode == App::getLocale())
                                    class="lang-active"
                                    @endif
                            >
                                <a  class="lang-child" id="global-lang-{{ $localeCode }}" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    <?php
                                    $displayLang = array(
                                        'en' => 'English',
                                        'trad' => '繁體中文',
                                        'sim' => '简体中文',
                                    )
                                    ?>
                                    {{ $displayLang[$localeCode] }}
                                </a>
                            </li>
                        @endforeach
                        {{--<li><a href="#" onclick="redirectLang(this, 'en');" class="lang-child">English</a></li></li>--}}
                        {{--<li><a href="#" onclick="redirectLang(this, 'trad');" class="lang-child">繁體中文</a></li>--}}
                        {{--<li><a href="#" onclick="redirectLang(this, 'sim');" class="lang-child">简体中文</a></li>--}}
                    </ul>
                </li>
            </ul>
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
                    @if($category['slug'] == 'Global-Gallery')
                        <?php
                            $outsideLang = array('en' => 'en','trad' => 'hk','sim' => 'cn');
                        ?>
                        <li><a href='https://{{ $outsideLang[App::getLocale()] }}.thevalue.com/global-galleries'>@lang('thevalue.global-gallery')</a></li>
                        <li><a href='{{ route('frontend.auction.auction', ['slug' => 'upcoming']) }}'>@lang('thevalue.auctions-info')</a></li>
                    @else
                        <li><a href='{{ route('frontend.category', ['slug' => $category['slug']]) }}'>{{ $category['name'] }}</a></li>
                    @endif
                @endforeach
                <li><a href='{{ route('frontend.category', ['slug' => 'videos']) }}'>@lang('thevalue.video')</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
