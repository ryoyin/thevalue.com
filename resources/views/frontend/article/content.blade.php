<script>
    var slug = "{{ $slug }}";
</script>

<script src="{{ asset('js/article.js') }}"></script>

@include('frontend.article.carousel')

<div class="row" id="article-content">

    <div class="col-md-12" id="left">
        @include('frontend.article.content-left')
    </div>

</div>