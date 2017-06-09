@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Auction Item - Modify
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/tvadmin/articles') }}"><i class="fa fa-dashboard"></i> Homepage</a></li>
      <li><a href="{{ route('backend.auction.itemList', ['saleID' => $sale->id]) }}">Auction Item List</a></li>
      <li class="active">Item Modify</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">

      <form role="form" action="{{ route('backend.auction.itemUpdate', ['itemID' => $item['id']]) }}" method="POST" enctype="multipart/form-data">

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
                  <label>Language</label>
                  <select class="form-control" onchange="changeLang(this)">
                      @foreach($langs as $lang)
                        <option value="{{ $lang }}">{{ $lang }}</option>
                      @endforeach
                  </select>
              </div>

              <style>
                  .item-lang {
                      display: none;
                  }
                  .active {
                      display: block;
                  }
              </style>
              @foreach($langs as $key => $lang)
              <div class="item-lang {{ $lang }}
                  @if($lang == 'en')
                    active
                  @endif
                  ">

                  <!-- text input -->
                  <div class="form-group">
                      <label>Title</label>
                      <input name="title-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $item['title-'.$lang] }}">
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Maker</label>
                      <input name="maker-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $item['maker-'.$lang] }}">
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Misc</label>
                      <input name="misc-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $item['misc-'.$lang] }}">
                  </div>

                  <!-- textarea -->
                  <div class="form-group">
                      <label>Description</label>
                      <textarea id="description-{{ $lang }}" name="description-{{ $lang }}" class="form-control">{!! $item['description-'.$lang] !!}</textarea>
                  </div>

                  <!-- textarea -->
                  <div class="form-group">
                      <label>Provenance</label>
                      <textarea id="provenance-{{ $lang }}" name="provenance-{{ $lang }}" class="form-control">{!! $item['provenance-'.$lang] !!}</textarea>
                  </div>

                  <!-- textarea -->
                  <div class="form-group">
                      <label>Post Lot Text</label>
                      <textarea id="post_lot_text-{{ $lang }}" name="post_lot_text-{{ $lang }}" class="form-control">{{ $item['post_lot_text-'.$lang] }}</textarea>
                  </div>

              </div>
              @endforeach
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">

              <!-- text input -->
              <div class="form-group">
                  <label>Photo</label>
                  <img src="{{ config('app.s3_path').$item['image_medium_path'] }}" class="img-responsive">
              </div>

              <!-- text input -->
              <div class="form-group">
                  <label>Slug</label>
                  <input name="slug" type="text" class="form-control" placeholder="Enter ..." value="{{ $item['slug'] }}" required>
              </div>

              <!-- text input -->
              <div class="form-group">
                  <label>Dimension</label>
                  <input name="dimension" type="text" class="form-control" placeholder="Enter ..." value="{{ $item['dimension'] }}">
              </div>

              <!-- text input -->
              <div class="form-group">
                  <label>Sorting</label>
                  <input name="sorting" type="text" class="form-control" placeholder="Enter ..." value="{{ $item['sorting'] }}" required>
              </div>

              <div class="box-footer" style="text-align: right;">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->


        </form>
    </div>

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script src="https://cdn.ckeditor.com/4.4.3/full/ckeditor.js"></script>
<script>

function changeLang(obj) {
    var $lang = $(obj).val();
    $(".item-lang").removeClass("active");
    $("."+$lang).addClass("active");
}

    $(function () {
        CKEDITOR.replace('description-en', {
            height: 300,
        });
        CKEDITOR.replace('description-trad', {
            height: 300,
        });
        CKEDITOR.replace('description-sim', {
            height: 300,
        });
        CKEDITOR.replace('provenance-en', {
            height: 300,
        });
        CKEDITOR.replace('provenance-trad', {
            height: 300,
        });
        CKEDITOR.replace('provenance-sim', {
            height: 300,
        });
        CKEDITOR.replace('post_lot_text-en', {
            height: 300,
        });
        CKEDITOR.replace('post_lot_text-trad', {
            height: 300,
        });
        CKEDITOR.replace('post_lot_text-sim', {
            height: 300,
        });
        $(".textarea").wysihtml5();
    });
</script>




@endsection
