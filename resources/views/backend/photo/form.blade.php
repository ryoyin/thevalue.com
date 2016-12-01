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
      <li class="active">Add</li>
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
                <input name="title" type="text" class="form-control" placeholder="Enter ..." value="{{ $photo['title'] }}">
              </div>

              <div class="form-group">
                <label>作者</label>
                <input name="author" type="text" class="form-control" placeholder="Enter ..." value="{{ $photo['caption'] }}">
              </div>

              <!-- textarea -->
              <div class="form-group">
                <label>簡介</label>
                  <textarea id="ckeditor1" name="short_desc" rows="10" cols="80">{{ $photo['alt'] }}</textarea>
              </div>

              <div class="form-group">
                <label for="uploadedFile">檔案</label>
                <input type="file" id="uploadedFile" name="uploaded_file">
              </div>

              <div class="form-group">
                <a href="{{ asset($photo['filePath']) }}" target="_blank">

                </a>
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">


              <!-- select -->
              <div class="form-group">
                <label>狀態</label>
                  {{ Form::select('status', array( 'pending' => 'pending', 'published' => 'published', 'syspend' => 'syspend'), $photo['status'], array('class' => 'form-control')) }}
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

<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>


@endsection

