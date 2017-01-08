@extends('backend.template.layout')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Photo Library
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">Photo Library</li>
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
            <h3 class="box-title">List</h3> <a href="{{ action('PhotoController@create') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">Add</a>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="research" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>ID</th>
                <th>Alt</th>
                <th>Path</th>
                <th style="text-align: center;">Created At</th>
                <th style="text-align: center;">Updated At</th>
                <th style="text-align: center;">Action</th>
              </tr>
              </thead>
              <tbody>

              @foreach($photos as $photo)
                <tr>
                  <td>{{ $photo->id }}</td>
                  <td>{{ $photo->alt }}</td>
                  <td>{{ $photo->image_path }}</td>
                  <td align="center">{{ $photo->size }}</td>
                  <td align="center">{{ $photo->created_at }}</td>
                  <td align="center">{{ $photo->updated_at }}</td>
                  <td align="center"><a href="{{ url('tvadmin/photos/'.$photo->id.'/edit') }}" class="btn btn-warning">Modify</a> <button class="btn btn-danger">Delete</button></td>
                </tr>
              @endforeach

              </tbody>
              {{--<tfoot>
              <tr>
                <th>標題</th>
                <th>類型</th>
                <th>標籤</th>
                <th>日期</th>
                <th>狀態</th>
                <th>行動</th>
              </tr>
              </tfoot>--}}
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
      "order": [[5, "desc"]]
    });
  });

  function delete_research(title) {
//    alert(title);
    var cfm = confirm("是否確定刪除文章 \""+title+"\"");

    if(cfm === false) {
      return false;
    }

  }
</script>
@endsection

