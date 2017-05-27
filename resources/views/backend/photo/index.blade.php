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
      <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
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
            <h3 class="box-title">List</h3> <a href="{{ action('Backend\PhotoController@create') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">Add</a>
          </div><!-- /.box-header -->
          <div class="box-body">
            {{ $photos->links() }}
            <table id="research" class="table table-bordered table-striped">
              <thead>
                <th>ID</th>
                <th>Image</th>
                <th>Alt</th>
                <th>Path</th>
                <th style="text-align: center;">Action</th>
              </tr>
              </thead>
              <tbody>

              @foreach($photos as $photo)
                <tr>
                  <td>{{ $photo->id }}</td>
                  <?php $image_path = $photo->image_medium_path == "" ? $photo->image_path : $photo->image_medium_path; ?>
                  <td><img src="{!! asset($image_path) !!} " style="height: 100px;"></td>
                  <td>{{ $photo->alt }}</td>
                  <td><input type="text" value = "{{ url($photo->image_path) }}"> <a href="#" onclick="copyClipBoard(this);">copy</a></td>
                  <td align="center">
                    <a href="{{ url('tvadmin/photos/'.$photo->id.'/edit') }}" class="btn btn-warning">Modify</a>
                    <form action="{{ url('tvadmin/photos/'.$photo->id) }}" method="POST" style="display: inline-block">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}

                      <button type="submit" class="btn btn-danger" onclick="return delete_item('{{ $photo['id'] }}');">
                        <i class="fa fa-trash"></i> Delete
                      </button>
                    </form>
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
            {{ $photos->links() }}
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
      "order": [[0, "desc"]],
      "paging":   false,
        "info":     false
    });
  });

  function delete_item(id) {
//    alert(id);
      var cfm = confirm("Are you sure delete ID: "+id);

    if(cfm === false) {
      return false;
    }

  }

  function copyClipBoard(obj) {
      $(obj).parent().children('input').select();
      try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
//          console.log('Copying text command was ' + msg);
      } catch (err) {
//          console.log('Oops, unable to copy');
      }
  }
</script>
@endsection

