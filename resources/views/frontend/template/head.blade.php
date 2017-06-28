<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    {{--<meta name="author" content="">--}}
    <title>TheValue.com</title>

    <script src="{{ asset('js/jquery/jquery-3.2.1.slim.min.js') }}"></script>

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
    <script src="{{ asset('js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('js/respond.min.js') }}"></script>
    <![endif]-->

    <script src="{{ asset('js/fontawesome.js') }}"></script>

    <!-- Photo Swipe -->
    <!-- Core CSS file -->
    <link rel="stylesheet" href="{{ asset('js/photoswipe/photoswipe.css') }}">

    <!-- Skin CSS file (styling of UI - buttons, caption, etc.)
         In the folder of skin CSS file there are also:
         - .png and .svg icons sprite,
         - preloader.gif (for browsers that do not support CSS animations) -->
    <link rel="stylesheet" href="{{ asset('js/photoswipe/default-skin/default-skin.css') }}">

    <!-- Core JS file -->
    <script src="{{ asset('js/photoswipe/photoswipe.min.js') }}"></script>

    <!-- UI JS file -->
    <script src="{{ asset('js/photoswipe/photoswipe-ui-default.min.js') }}"></script>

    <!-- custom -->
    <link href="{{ asset('css/web.css') }}?refresh=2017062301" rel="stylesheet">

    <script src="{{ asset('js/js.cookie.js') }}"></script>

    <script>
        var site_root = "{{ env("APP_URL") }}/";
        var site_lang = "{{ App::getLocale() }}";
//        console.log(site_root);
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/general.js') }}?refresh=2017053101"></script>

    <!-- Google GA -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-91745739-1', 'auto');
        ga('send', 'pageview');
    </script>

    <meta property="og:site_name" content="{{ $fbMeta['site_name'] }}">
    <meta property="og:url" content="{{ $fbMeta['url'] }}">
    <meta property="og:type" content="{{ $fbMeta['type'] }}">
    <meta property="og:title" content="{{ $fbMeta['title'] }}">
    <meta property="og:description" content="{{ $fbMeta['description'] }}">
    {{--<meta property="og:image" content="{{ $fbMeta['image'] }}">--}}
    {{--<meta property="og:image:url" content="{{ $fbMeta['image'] }}">--}}
    <meta property="og:image:secure_url" content="{{ $fbMeta['image'] }}">
    <meta property="fb:app_id" content="{{ $fbMeta['app_id'] }}">

    @if(isset($fbMeta['image_width']) && isset($fbMeta['image_width']))
        <meta property="og:image:width" content="{{ $fbMeta['image_width'] }}">
        <meta property="og:image:height" content="{{ $fbMeta['image_height'] }}">
    @endif
    {{--<meta property="fb:admins" content="1136380453091512">--}}

    <!-- site icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset("images/icons/apple-icon-57x57.png") }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset("images/icons/apple-icon-60x60.png") }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset("images/icons/apple-icon-72x72.png") }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("images/icons/apple-icon-76x76.png") }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset("images/icons/apple-icon-114x114.png") }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset("images/icons/apple-icon-120x120.png") }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset("images/icons/apple-icon-144x144.png") }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset("images/icons/apple-icon-152x152.png") }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("images/icons/apple-icon-180x180.png") }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset("images/icons/android-icon-192x192.png") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("images/icons/favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset("images/icons/favicon-96x96.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("images/icons/favicon-16x16.png") }}">
    <link rel="manifest" href="{{ asset("images/icons/manifest.json") }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset("images/icons/ms-icon-144x144.png") }}"> <meta name="theme-color" content="#ffffff">

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-8545127753274353",
            enable_page_level_ads: false
        });
    </script>

</head>