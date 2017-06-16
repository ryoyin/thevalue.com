@extends('backend.template.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Auction House - {{ $title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('/tvadmin/auction/house') }}"><i class="fa fa-dashboard"></i> Homepage</a></li>
      <li><a href="{{ url('/tvadmin/auction/house') }}">Auction House</a></li>
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
              <h4 class="box-header">EN</h4>
              <div class="form-group">
                  <label>Name</label>
                  <input name="en-name" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['en-name'] }}" required>
              </div>
              <div class="form-group">
                  <label>Country</label>
                  <input name="en-country" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['en-country'] }}" required>
              </div>
              <div class="form-group">
                  <label>City</label>
                  <input name="en-city" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['en-city'] }}" required>
              </div>
              <div class="form-group">
                  <label>Address</label>
                  <input name="en-address" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['en-address'] }}">
              </div>
              <div class="form-group">
                  <label>Office Hour</label>
                  <input name="en-office_hour" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['en-office_hour'] }}" >
              </div>

              <h4 class="box-header">Trad</h4>
              <div class="form-group">
                  <label>Name</label>
                  <input name="trad-name" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['trad-name'] }}" required>
              </div>
              <div class="form-group">
                  <label>Country</label>
                  <input name="trad-country" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['trad-country'] }}" required>
              </div>
              <div class="form-group">
                  <label>City</label>
                  <input name="trad-city" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['trad-city'] }}" required>
              </div>
              <div class="form-group">
                  <label>Address</label>
                  <input name="trad-address" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['trad-address'] }}">
              </div>
              <div class="form-group">
                  <label>Office Hour</label>
                  <input name="trad-office_hour" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['trad-office_hour'] }}" >
              </div>

              <h4 class="box-header">Sim</h4>
              <div class="form-group">
                  <label>Name</label>
                  <input name="sim-name" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['sim-name'] }}" required>
              </div>
              <div class="form-group">
                  <label>Country</label>
                  <input name="sim-country" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['sim-country'] }}" required>
              </div>
              <div class="form-group">
                  <label>City</label>
                  <input name="sim-city" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['sim-city'] }}" required>
              </div>
              <div class="form-group">
                  <label>Address</label>
                  <input name="sim-address" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['sim-address'] }}">
              </div>
              <div class="form-group">
                  <label>Office Hour</label>
                  <input name="sim-office_hour" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['sim-office_hour'] }}" >
              </div>
              {{--slug, image_path, tel_no, fax_no, email, status, name, address, lang, office_house, auction_house_id--}}

          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-xs-3">
        <!-- general form elements disabled -->
        <div class="box box-info">
          <div class="box-body">
              <!-- text input -->
              <div class="form-group">
                  <label>Logo</label>
                  @if(isset($house['image_path']))
                    <img src="{{ asset($house['image_path']) }}" class="img-responsive">
                  @endif
                  <input name="uploaded_file" type="file" class="form-control">
              </div>
              <div class="form-group">
                  <label>Slug</label>
                  <input name="slug" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['slug'] }}" required>
              </div>
              <div class="form-group">
                  <label>Tel No.</label>
                  <input name="tel_no" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['tel_no'] }}" >
              </div>
              <div class="form-group">
                  <label>Fax No.</label>
                  <input name="fax_no" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['fax_no'] }}" >
              </div>
              <div class="form-group">
                  <label>Email</label>
                  <input name="email" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['email'] }}" >
              </div>
              <div class="form-group">
                  <label>Dollar Sign</label>
                  <input name="dollar_sign" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['dollar_sign'] }}" >
              </div>
              <div class="form-group">
                  <label>Currency Code</label>
                  <input name="currency_code" type="text" class="form-control" placeholder="Enter ..." value="{{ @$house['currency_code'] }}" >
              </div>
              <div class="form-group">
                  <label>Status</label>
                  <select name="status" id="status" class="form-control">
                      <option
                          @if(0 == @$house['status'])
                          selected
                          @endif
                          value="0">Off</option>
                      <option
                          @if(1 == @$house['status'])
                          selected
                          @endif
                          value="1">On</option>
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

