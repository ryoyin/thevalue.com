<div id="left" class="col-md-9">

    <ul id="head" class="ul-clean">
        <li class="pull-left stories-active" onclick="showStories(this, 'latest')"><i class="fa fa-clock-o" aria-hidden="true"></i> Latest Stories</li>
        <li class="pull-left" onclick="showStories(this, 'popular')"><i class="fa fa-line-chart" aria-hidden="true"></i> Popular Stories</li>
        <li class="pull-right" style="position: relative" onclick="showStoryCategories()">Categories <i class="fa fa-chevron-circle-down" aria-hidden="true"></i></li>
    </ul>

    <div style="clear:both;"></div>

    <div id="block">
        <ul id="stories-categories" class="ul-clean" style="display: none;"></ul>
        <div id="stories"></div>
    </div>

</div>