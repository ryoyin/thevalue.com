@extends('backend.template.layout')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      系統參數
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">系統參數</li>
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
            <h3 class="box-title">系統參數</h3> <a href="{{ action('SystemparamController@create') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">新增</a>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="research" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>參數名稱</th>
                <th>參數值</th>
                <th>建立日期</th>
                <th>最後更新</th>
                <th align="center">行動</th>
              </tr>
              </thead>
              <tbody>
              @foreach($parameters AS $param)
                <tr>
                  <td>{{$param['param_name']}}</td>
                  <td>{{$param['param_value']}}</td>
                  <td>{{$param['created_at']}}</td>
                  <td>{{$param['updated_at']}}</td>
                  <td align="center">
                    <form action="{{ url('bjsgadmin/systemparam/'.$param->id.'/edit') }}" method="GET" style="display: inline-block">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit
                      </button>
                    </form>

                    <!-- Delete Button -->
                    {{--<form action="{{ url('bjsgadmin/systemparam/'.$param->id) }}" method="POST" style="display: inline-block">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}

                      <button type="submit" class="btn btn-danger" onclick="return delete_research('{{ $param['param_name'] }}');">
                        <i class="fa fa-trash"></i> Delete
                      </button>
                    </form>--}}
                  </td>
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
    var cfm = confirm("是否確定刪除參數 \""+title+"\"");

    if(cfm === false) {
      return false;
    }

  }
</script>
@endsection

