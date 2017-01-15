@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Tag - {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/tvadmin/tags') }}"><i class="fa fa-dashboard"></i> Homepage</a></li>
      <li><a href="{{ url('/tvadmin/tags') }}">Tag</a></li>
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

              {{--lang, name, category_id--}}
              <!-- text input -->
              <div class="form-group">
                  <label>English Name</label>
                  <input name="name-en" type="text" class="form-control" placeholder="Enter ..." value="{{ $tag['name-en'] }}" required>
              </div>

              <!-- text input -->
              <div class="form-group">
                  <label>Traditional Name</label>
                  <input name="name-trad" type="text" class="form-control" placeholder="Enter ..." value="{{ $tag['name-trad'] }}" required>
              </div>

              <!-- text input -->
              <div class="form-group">
                  <label>Simplified Name</label>
                  <input name="name-sim" type="text" class="form-control" placeholder="Enter ..." value="{{ $tag['name-sim'] }}" required>
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">

              <!-- text input -->
              <div class="form-group">
                  <label>Slug</label>
                  <input name="slug" type="text" class="form-control" placeholder="Enter ..." value="{{ $tag['slug'] }}" required>
              </div>

              <div class="box-footer" style="text-align: right;">
                <button type="submit" class="btn btn-primary">Submit</button>
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

