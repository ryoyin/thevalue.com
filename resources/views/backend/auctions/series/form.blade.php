@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Auction Series - {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/tvadmin/auction/series') }}"><i class="fa fa-dashboard"></i> Homepage</a></li>
      <li><a href="{{ url('/tvadmin/auction/series') }}">Auction Series</a></li>
      <li class="active">Add</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">

      <form role="form" action="{{ url($action) }}" method="POST">

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
              <h4 class="box-header">EN</h4>
              <div class="form-group">
                  <label>Name</label>
                  <input name="en-name" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['en-name'] }}" required>
              </div>
              <div class="form-group">
                  <label>Country</label>
                  <input name="en-country" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['en-country'] }}">
              </div>
              <div class="form-group">
                  <label>Location</label>
                  <input name="en-location" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['en-location'] }}" >
              </div>

              <h4 class="box-header">Trad</h4>
              <div class="form-group">
                  <label>Name</label>
                  <input name="trad-name" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['trad-name'] }}" required>
              </div>
              <div class="form-group">
                  <label>Country</label>
                  <input name="trad-country" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['trad-country'] }}">
              </div>
              <div class="form-group">
                  <label>Location</label>
                  <input name="trad-location" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['trad-location'] }}" >
              </div>

              <h4 class="box-header">Sim</h4>
              <div class="form-group">
                  <label>Name</label>
                  <input name="sim-name" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['sim-name'] }}" required>
              </div>
              <div class="form-group">
                  <label>Country</label>
                  <input name="sim-country" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['sim-country'] }}">
              </div>
              <div class="form-group">
                  <label>Location</label>
                  <input name="sim-location" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['sim-location'] }}" >
              </div>

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">
              {{--slug start_date end_date auction_house_id status--}}
              <!-- text input -->
              <div class="form-group">
                  <label>Slug</label>
                  <input name="slug" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['slug'] }}" required>
              </div>
              <div class="form-group">
                  <label>Start Date</label>
                  <input name="start_date" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['start_date'] }}" >
              </div>
              <div class="form-group">
                  <label>End Date</label>
                  <input name="end_date" type="text" class="form-control" placeholder="Enter ..." value="{{ @$series['end_date'] }}" >
              </div>
              <div class="form-group">
                  <label>House</label>
                  <select name="auction_house_id" class="form-control">
                      @foreach($houses as $house)
                          <?php
                              $houseDetail = $house->getDetailByLang('trad');
                          ?>
                          <option value="{{ $house->id }}"
                            @if(isset($series['auction_house_id']))
                                @if($house->id == $series['auction_house_id'])
                                    selected
                                @endif
                            @endif
                          >{{ $houseDetail->name }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="form-group">
                  <label>Status</label>
                  <select name="status" id="status" class="form-control">
                      <?php
                        $statusArray = array('pending', 'published', 'withdraw');
                        ?>
                      @foreach($statusArray as $status)
                          <option value="{{ $status }}"
                              @if(isset($series['status']))
                                  @if($series['status'] == $status)
                                      selected
                                  @endif
                              @endif
                          >{{ $status }}</option>
                      @endforeach
                  </select>
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

