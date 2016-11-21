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
        <p>系统管理员</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
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
      <li class="header">文章</li>
      <!-- Optionally, you can add icons to the links -->
      {{--<li><a href="{{ route('admin-blog') }}"><i class="fa fa-camera-retro"></i> <span>圖片庫   </span></a></li>--}}
      <li class="treeview {{ isActiveMenu('blog', $menu) }}">
        <a href="#"><i class="fa fa-file-text-o"></i> <span>網站文章平台</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
          <li class="{{ isActiveMenu('blog.list', $menu) }}"><a href="#"><a href="{{ action('PhotoController@index') }}"><i class="fa fa-dot-circle-o"></i> <span>文章列表   </span></a></a></li>
          <li class="{{ isActiveMenu('blog.create', $menu) }}"><a href="{{ action('PhotoController@create') }}"><i class="fa fa-dot-circle-o"></i> <span>新增文章   </span></a></li>
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