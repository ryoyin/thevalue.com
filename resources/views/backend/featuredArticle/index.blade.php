@extends('backend.template.layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Featured Article</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
        <li class="active">Featured Articles</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
          @if (session('alert-success'))
            <div class="callout callout-success">
              <b>{!! session('alert-success') !!}</b>
            </div>
          @endif
          @if (session('alert-warning'))
            <div class="callout callout-warning">
              <b>{!! session('alert-warning') !!}</b>
            </div>
          @endif
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List</h3> <a href="{{ action('Backend\FeaturedArticleController@create') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">Add</a>
            </div><!-- /.box-header -->
            <div class="box-body">
              <table id="research" class="table table-bordered table-striped">
                <thead>
                <th>ID</th>
                <th>Article ID</th>
                <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($featureArticles as $featuredArticle)
                  <tr>
                    <td>{{ $featuredArticle->id }}</td>
                    <td><img src="{{ asset($featuredArticle->article->photo->image_path) }}"></td>
                    <td align="center">
{{--                      <a href="{{ url('tvadmin/featuredArticles/'.$featuredArticle->id.'/edit') }}" class="btn btn-warning">Modify</a>--}}
                      <form action="{{ url('tvadmin/featuredArticles/'.$featuredArticle->id) }}" method="POST" style="display: inline-block">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        <button type="submit" class="btn btn-danger" onclick="return delete_research('{{ $featuredArticle['id'] }}');">
                          <i class="fa fa-trash"></i> Delete
                        </button>
                      </form>
                  </tr>
                @endforeach

                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div>
      </div>

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables/dataTables.bootstrap.css') }}">

  <!-- DataTables -->
  <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- SlimScroll -->
  <script src="{{ asset('admin/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ asset('admin/plugins/fastclick/fastclick.min.js') }}"></script>

  <script>
      $(function () {
          $("#research").DataTable({
              "order": [[0, "desc"]]
          });
      });

      function delete_research(id) {
//    alert(title);
          var cfm = confirm("Are you sure delete ID: "+id);

          if(cfm === false) {
              return false;
          }

      }

      function copyClipBoard(obj) {
          var copyTarget = $(obj).parent().children('span').html();

      }
  </script>
@endsection

