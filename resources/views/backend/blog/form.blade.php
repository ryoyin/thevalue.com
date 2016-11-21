@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      網站文章平台 - {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="/bjsgadmin/blog/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/bjsgadmin/blog/">網站文章平台</a></li>
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
                <label>標題</label>
                <input name="title" type="text" class="form-control" placeholder="Enter ..." value="{{ $research['title'] }}">
              </div>

              <div class="form-group">
                <label>作者</label>
                <input name="author" type="text" class="form-control" placeholder="Enter ..." value="{{ $research['author'] }}">
              </div>

              <!-- textarea -->
              <div class="form-group">
                <label>簡介</label>
                  <textarea id="ckeditor1" name="short_desc" rows="10" cols="80">{{ $research['short_desc'] }}</textarea>
              </div>

              <!-- textarea -->
              <div class="form-group">
                <label>內文</label>
                <textarea id="ckeditor2" name="description" rows="10" cols="80">{{ $research['description'] }}</textarea>
              </div>

              <div class="form-group">
                <label for="uploadedFile">檔案</label>
                <input type="file" id="uploadedFile" name="uploaded_file">
              </div>

              <div class="form-group">
                <a href="{{ asset($research['file_path']) }}" target="_blank">
                    @if($research['file_path'] != '')
                        {{ public_path().'/'.$research['file_path'] }}
                    @endif
                </a>
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">
              <div class="form-group">
                <label>發佈日期</label><Br>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  {{--<input type="text" class="form-control pull-right" id="reservation">--}}
                  <input name="publish_date" type="text" data-provide="datepicker" class="form-control pull-right datepicker" value="{{ $research['publish_date'] }}">
                </div><!-- /.input group -->
              </div>

              <!-- time Picker -->
              <div class="form-group">
                <label>時間</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input name="publish_time" type="text" class="form-control timepicker" value="{{ $research['publish_time'] }}">
                </div><!-- /.input group -->
              </div><!-- /.form group -->

              <!-- select -->
              <div class="form-group">
                <label>狀態</label>
                  {{ Form::select('status', array( 0 => '草稿', 1 => '準備中', 2 => '發佈', 3 => '暫停', 4 => '刪除'), $research['status'], array('class' => 'form-control')) }}
{{--                <select name="status" class="form-control">
                  <option value="0">草稿</option>
                  <option value="1">準備中</option>
                  <option value="2">發佈</option>
                  <option value="3">暫停</option>
                  <option value="4">刪除</option>
                </select>--}}
              </div>

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

