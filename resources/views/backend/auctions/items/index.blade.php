@extends('backend.template.layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Auctions</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
        <li class="active">Item List</li>
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
              <h3 class="box-title">Auction Item List</h3>
              {{--<a href="{{ route('backend.auction.items') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">Add</a>--}}
            </div><!-- /.box-header -->
            <div class="box-body">

              <div class="form-group">
                <label>Sales: </label>
                <select id="sales" class="form-control" onchange="redirectSale(this)">
                  <option> -- please select -- </option>
                  @foreach($sales as $sale)
                    <?php
                      $saleDetail = $sale->details()->where('lang', 'trad')->first();
                    ?>
                    <option value="{{ route('backend.auction.itemList', ['saleID' => $sale->id]) }}"
                      @if($saleInfo['sale'] != null)
                        @if($sale->id == $saleInfo['sale']->id)
                          selected
                        @endif
                      @endif
                    >{{ $saleDetail->title }}</option>
                  @endforeach
                </select>
              </div>

              <table id="research" class="table table-bordered table-striped">
                {{--category_id, slug, photo_id, hit_counter, share_counter--}}
                <thead>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Maker</th>
                <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($items as $item)
                  <?php $itemDetail = $item->details()->where('lang', $locale)->first(); ?>
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td><img src="{{ config('app.s3_path').$item->image_small_path }}"</td>
                    <td>{{ mb_substr($itemDetail->title, 0, 50, 'utf-8') }}</td>
                    <td>{{ $itemDetail->make }}</td>
                    <td align="center">
                      <a href="{{ route('backend.auction.itemEdit', ['itemID' => $item->id]) }}" class="btn btn-warning">Modify</a>
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
              "order": [[0, "desc"]]
          });
      });

      function delete_research(id) {
//    alert(title);
          var cfm = confirm("Are you sure delete ID: "+id);

          if(cfm === false) {
              return false;
          }

      }

      function redirectSale(obj) {
          var url = $(obj).val();
          window.location = url;
      }

  </script>
@endsection

