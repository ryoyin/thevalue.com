@extends('backend.template.layout')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                通訊錄
                <small>- 與公司有關的聯絡方法</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">通訊錄</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">内部伺服器运作情况</h3>
                            <div class="box-tools">
                                <button class="btn btn-sm btn-info" onclick="checkAllConnection(); return false;"> 重新測試 </button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover server-monitor">
                                <tbody><tr>
                                    <th>伺服器名称</th>
                                    <th>别名</th>
                                    <th>IP位址</th>
                                    <th>状态</th>
                                </tr>
                                @foreach($server_status AS $alias => $server)
                                    <tr class="alias" alias="{{ $alias }}">
                                        <td>{{ $server['fullname'] }}</td>
                                        <td>{{ $alias }}</td>
                                        <td>{{ $server['ip'] }}</td>
                                        <td>
                                            @if($server['status'] == "passed")
                                                <span class="label label-success">在线</span>
                                            @elseif($server['status'] == "pending")
                                                <span class="label label-warning">測試中</span>
                                            @else
                                                <span class="label label-danger">连接失败</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody></table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection

