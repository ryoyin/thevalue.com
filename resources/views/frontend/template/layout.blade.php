<!DOCTYPE html>
<html lang="en">

@include('frontend.template.head')

<body>

@include('frontend.template.dropdown-menu')

<div class="container">

    @include('frontend.template.header')

    <div style="clear:both"></div>

    @include('frontend.template.header-menu')

    @yield('content')

    @include('frontend.template.footer')

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
