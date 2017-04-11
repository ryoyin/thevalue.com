<div class="row" id="header-menu">
    <div class="col-md-8">
        <ul class="ul-clean" id="categoriesList"></ul>
    </div>
    <div class="col-md-4 text-right">
        <a href="#" onclick="showSearchBar();"><i class="fa fa-search" aria-hidden="true"></i></a>
    </div>
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

