@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Article - {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/tvadmin/articles') }}"><i class="fa fa-dashboard"></i> Homepage</a></li>
      <li><a href="{{ url('/tvadmin/articles') }}">Article</a></li>
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
                  <label>Language</label>
                  <select class="form-control" onchange="changeLang(this)">
                      @foreach($langs as $lang)
                        <option value="{{ $lang }}">{{ $lang }}</option>
                      @endforeach
                  </select>
              </div>

              <style>
                  .article-lang {
                      display: none;
                  }
                  .active {
                      display: block;
                  }
              </style>
              @foreach($langs as $key => $lang)
              <div class="article-lang {{ $lang }}
                  @if($lang == 'en')
                    active
                  @endif
                  ">
                  <!-- text input -->
                  <div class="form-group">
                      <label>Title</label>
                      <input name="title-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['title-'.$lang] }}">
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Note</label>
                      <input name="note-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['note-'.$lang] }}">
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Short Description</label>
                      <input name="short_desc-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['short_desc-'.$lang] }}">
                  </div>

                  <!-- textarea -->
                  <div class="form-group">
                      <label>Description</label>
                      <textarea id="description-{{ $lang }}" name="description-{{ $lang }}" class="form-control">{{ $article['description-'.$lang] }}</textarea>
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Source</label>
                      <input name="source-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['source-'.$lang] }}">
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Author</label>
                      <input name="author-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['author-'.$lang] }}">
                  </div>

                  <!-- text input -->
                  <div class="form-group">
                      <label>Photographer</label>
                      <input name="photographer-{{ $lang }}" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['photographer-'.$lang] }}">
                  </div>

                  <div class="form-group">
                      <label>Status</label>
                      <select name="status-{{ $lang }}" class="form-control">
                          @foreach($status as $skey => $item)
                              <option value="{{ $skey }}"
                                  @if($article['status-'.$lang] == $skey)
                                      selected
                                  @endif
                              >{{ $item }}</option>
                          @endforeach
                      </select>
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
              {{--category_id, slug, photo_id, hit_counter, share_counter--}}
              <div class="form-group">
                  <label>Category</label>
                  <select name="category_id" class="form-control">
                  <?php
                      $categories = App\Category::where('parent_id', null)->get();
//                        dd($categories);
                      foreach($categories as $category) {
//                          dd($category->details->where());
                          $categoryDetail = $category->details->where('lang', 'en')->first();
//                          dd($categoryDetail);
                          $selected = '';
                          if($category->id == $article['category_id']) {
                              $selected = 'selected';
                          }
                          echo "<option value='".$category->id."' ".$selected.">".$categoryDetail->name."</option>";
                      }
                  ?>
                  </select>
              </div>
              <!-- text input -->
              <div class="form-group">
                  <label>Slug</label>
                  <input name="slug" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['slug'] }}" required>
              </div>
              <!-- text input -->
              <div class="form-group">
                  <label>Main Photo</label>
                  <input name="photo_id" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['photo_id'] }}" required>
              </div>
              <!-- text input -->
              <div class="form-group">
                  <label>Hit Counter</label>
                  <input name="hit_counter" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['hit_counter'] }}" required>
              </div>
              <!-- text input -->
              <div class="form-group">
                  <label>Share Counter</label>
                  <input name="share_counter" type="text" class="form-control" placeholder="Enter ..." value="{{ $article['share_counter'] }}" required>
              </div>
              <!-- text input -->
              <div class="form-group">
                  <label>Publish Date</label>
                  <?php $published_at = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $article['published_at'])->addHours(8); ?>
                  <input name="published_at" type="text" class="form-control" placeholder="YYYY-MM-DD HH:MM:SS" value="{{ $published_at }}" required>
              </div>
              <!-- select -->
              <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control">
                  @foreach($status as $key => $item)
                      <option value="{{ $key }}"
                          @if($article['status'] == $key)
                              selected
                          @endif
                      >{{ $item }}</option>
                  @endforeach
                  </select>
              </div>

              <div class="box-footer" style="text-align: right;">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->

          <div class="box box-info">
              <div class="box-body">
                  <!-- text input -->
                  <div class="form-group">
                      <label>Gallery</label>
                      <input name="gallery" type="text" class="form-control" placeholder="Enter ..." value="{{ $gallery }}">
                  </div>
              </div>
          </div>

          <div class="box box-info">
              <div class="box-body">
                  <!-- text input -->
                  <div class="form-group">
                      <label>Tags</label>
                      <input name="tags" type="text" class="form-control" placeholder="Enter ..." value="{{ $tags }}">
                  </div>
              </div>
          </div>

        </form>
    </div>

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script src="https://cdn.ckeditor.com/4.4.3/full/ckeditor.js"></script>
<script>

function changeLang(obj) {
    var $lang = $(obj).val();
    $(".article-lang").removeClass("active");
    $("."+$lang).addClass("active");
}

    $(function () {
        CKEDITOR.replace('description-en', {
            height: 600,
        });
        CKEDITOR.replace('description-trad', {
            height: 600,
        });
        CKEDITOR.replace('description-sim', {
            height: 600,
        });
        $(".textarea").wysihtml5();
    });
</script>




@endsection
