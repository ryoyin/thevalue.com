@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      系統參數 - {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="/bjsgadmin/systemparam/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/bjsgadmin/systemparam/">系統參數</a></li>
      <li class="active">新增</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">

      <form role="form" action="{{ url($action) }}" method="POST" enctype="multipart/form-data">

          @if(isset($formMethod))
            {{ method_field($formMethod) }}
          @endif

          @if (count($errors) > 0)
              <div class="col-xs-12">
                  <div class="callout callout-warning">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              </div>
          @endif

      <div class="col-xs-9">
        <!-- general form elements disabled -->
        <div class="box box-default">
          <div class="box-body">
              {{ csrf_field() }}
              <!-- text input -->
              <div class="form-group">
                <label>參數名稱</label>
                <input name="param_name" type="text" class="form-control" placeholder="Enter ..." value="{{ $param['param_name'] }}">
              </div>

              <div class="form-group">
                <label>參數值</label>
                <input name="param_value" type="text" class="form-control" placeholder="Enter ..." value="{{ $param['param_value'] }}">
              </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">

              <div class="box-foote" style="text-align: right;">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
        </form>
    </div>

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script src="{{ asset('admin/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>

<link rel="stylesheet" href="{{asset('admin/plugins/datepicker/datepicker3.css')}}">
<script>
  $('.datepicker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd"
  });
  $(".timepicker").timepicker({
    showInputs: false
  });

  CKEDITOR.replace('ckeditor1');
  CKEDITOR.replace('ckeditor2');
</script>

@endsection

