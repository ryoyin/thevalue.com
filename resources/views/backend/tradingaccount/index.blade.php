@extends('backend.template.layout')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      交易系統帳號資訊
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">交易系統帳號資訊</li>
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
            <h3 class="box-title">帳號資訊</h3> {{--<a href="{{ action('TradingAccountController@create') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">新增</a>--}}
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="research" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>帳戶號碼</th>
                <th>性別</th>
                <th>性名</th>
                <th>電話號碼</th>
                <th>地址</th>
                <th>Email</th>
                <th>建立日期</th>
                <th>最後更新</th>
              </tr>
              </thead>
              <tbody>
              @foreach($tradingaccounts AS $account)
                <tr>
                  <td>{{$account['account_no']}}</td>
                  <td>{{$account['name']}}</td>
                  <td>{{$account['sexual']}}</td>
                  <td>{{$account['tel']}}</td>
                  <td>{{$account['address']}}</td>
                  <td>{{$account['email']}}</td>
                  <td>{{$account['created_at']}}</td>
                  <td>{{$account['updated_at']}}</td>
                  {{--<td align="center">
                    <form action="{{ url('bjsgadmin/tradingaccount/'.$account->id.'/edit') }}" method="GET" style="display: inline-block">
                      {{ csrf_field() }}
                      <button type="submit" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit
                      </button>
                    </form>

                    <form action="{{ url('bjsgadmin/tradingaccount/'.$account->id) }}" method="POST" style="display: inline-block">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}

                      <button type="submit" class="btn btn-danger" onclick="return delete_research('{{ $account['title'] }}');">
                        <i class="fa fa-trash"></i> Delete
                      </button>
                    </form>
                  </td>--}}
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

