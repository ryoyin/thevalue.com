<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{asset('admin/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>Administrator</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form (Optional) -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="搜寻...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
        </span>
      </div>
    </form>
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">Main</li>
      <!-- Optionally, you can add icons to the links -->
      {{--<li><a href="{{ route('admin-blog') }}"><i class="fa fa-camera-retro"></i> <span>圖片庫   </span></a></li>--}}
      <li class="treeview {{ isActiveMenu('photo', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Photo Library</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('photo.list', $menu) }}"><a href="#"><a href="{{ action('Backend\PhotoController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Photo List   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('photo.create', $menu) }}"><a href="{{ action('Backend\PhotoController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Photo   </span></a></li>--}}
        </ul>
      </li>

      <li class="treeview {{ isActiveMenu('banner', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Banner</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('banner.list', $menu) }}"><a href="#"><a href="{{ action('Backend\BannerController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Banner List   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('banner.create', $menu) }}"><a href="{{ action('Backend\BannerController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Banner   </span></a></li>--}}
        </ul>
      </li>

      <li class="treeview {{ isActiveMenu('article', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Article</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('article.list', $menu) }}"><a href="#"><a href="{{ action('Backend\ArticleController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Article List   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('banner.create', $menu) }}"><a href="{{ action('Backend\BannerController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Banner   </span></a></li>--}}
        </ul>
      </li>

      <li class="treeview {{ isActiveMenu('featuredArticle', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Featured Article</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('featuredArticle.list', $menu) }}"><a href="#"><a href="{{ action('Backend\FeaturedArticleController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Featured Article List   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('banner.create', $menu) }}"><a href="{{ action('Backend\BannerController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Banner   </span></a></li>--}}
        </ul>
      </li>

      <li class="treeview {{ isActiveMenu('category', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Category</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('category.list', $menu) }}"><a href="#"><a href="{{ action('Backend\CategoryController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Categories List   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('banner.create', $menu) }}"><a href="{{ action('Backend\BannerController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Banner   </span></a></li>--}}
        </ul>
      </li>


      <li class="treeview {{ isActiveMenu('tag', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Tag</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('tag.list', $menu) }}"><a href="#"><a href="{{ action('Backend\TagController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Tags List   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('banner.create', $menu) }}"><a href="{{ action('Backend\BannerController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Banner   </span></a></li>--}}
        </ul>
      </li>

      <li class="treeview {{ isActiveMenu('notification', $menu) }}">
        <a href="#" class="active"><i class="fa fa-file-text-o"></i> <span>Notification</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('notification.list', $menu) }}"><a href="#"><a href="{{ action('Backend\NotificationController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>Notification   </span></a></a></li>
          {{--<li class="{{ isActiveMenu('banner.create', $menu) }}"><a href="{{ action('Backend\BannerController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>Add Banner   </span></a></li>--}}
        </ul>
      </li>

      {{--<li class="treeview {{ isActiveMenu('tradingaccount', $menu) }}">--}}
        {{--<a href="{{ action('TradingAccountController@index') }}"><i class="fa fa-info"></i> <span>交易系統帳號</span></a>--}}
      {{--</li>--}}

      {{--<li class="treeview {{ isActiveMenu('systemparam', $menu) }}">--}}
        {{--<a href="#"><i class="fa fa-info"></i> <span>系統參數</span>  <i class="fa fa-angle-left pull-right"></i></a>--}}
        {{--<ul class="treeview-menu">--}}
          {{--<li class="{{ isActiveMenu('systemparam.list', $menu) }}"><a href="#"><a href="{{ action('SystemparamController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>參數列表   </span></a></a></li>--}}
          {{--<li class="{{ isActiveMenu('systemparam.create', $menu) }}"><a href="{{ action('SystemparamController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>新增參數   </span></a></li>--}}
        {{--</ul>--}}
      {{--</li>--}}

      <li class="treeview">
        {{--<a href="#"><i class="fa fa-picture-o"></i> <span>圖片庫</span></a>--}}
      </li>

    </ul><!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>