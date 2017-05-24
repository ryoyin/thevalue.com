<div class="row" id="header-menu">
    <div class="col-md-12 col-sm-12" style="display:inline-block;">
        <ul class="ul-clean" id="categoriesList">
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
            <li class="pull-right"><a href="#" onclick="showSearchBar();"><i class="fa fa-search" aria-hidden="true"></i></a></li>
        </ul>
    </div>
    {{--<div class="col-md-4 text-right">
        <a href="#" onclick="showSearchBar();"><i class="fa fa-search" aria-hidden="true"></i></a>
    </div>--}}
</div> <!-- /header-menu-->

<div class="row" id="search-block">
    {{--<input type="text" class="pull-right" onkeypress="searchme(this, event)">--}}
    <div class="input-group">
        <input id="sim_search" type="text" class="form-control" placeholder="keywords..." onkeypress="searchme(this, event)">
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="simple_search();return false;">@lang('thevalue.search')</button>
        </span>
    </div><!-- /input-group -->
</div>

