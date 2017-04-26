<!DOCTYPE html>
<html lang="en">

@include('mobile.template.head')

<body>

<div class="container">

    <div style="clear:both"></div>

    @yield('content')

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="{{ asset('dist/js/bootstrap.min.js') }}"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{ asset('assets/js/ie10-viewport-bug-workaround.js') }}"></script>

<script>
    (function(document, $, postMessage){
        if (postMessage) {
            $(function(){
                // inject click event to WebView in RN
                $('img').on('click', function(){
                    var data = {
                        src: this.attr('src'),
                        type: 'image'
                    }
                    postMessage( JSON.stringify(data) )
                })

                $('a').on('click', function(){
                    var data = {
                        href: this.attr('href'),
                        type: 'link'
                    }
                    postMessage( JSON.stringify(data) )
                })
            })
        }
    }(document, $, postMessage))
</script>
</body>
</html>
