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
              <h3 class="box-title">Auction Sale</h3>
              {{--<a href="{{ route('backend.auction.items') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">Add</a>--}}
            </div><!-- /.box-header -->
            <div class="box-body">

              <form method="post" action="{{ route('backend.auction.sale.saleList') }}">
                {{ csrf_field() }}
                <div class="form-group">
                  <label>Sale Slug: </label>
                  <input name="slug" size="50">
                  <input type="submit">
                </div>
              </form>
            </div>
          </div>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Sale List</h3>
              <table id="research" class="table table-bordered table-striped">
                <thead>
                  <th>ID</th>
                  <th>House Number</th>
                  <th>Slug</th>
                  <th>Start Date</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  @foreach($sales as $sale)
                    <tr>
                      <td>{{ $sale->id }}</td>
                      <td>{{ $sale->number }}</td>
                      <td>{{ $sale->slug }}</td>
                      <td>{{ $sale->start_date }}</td>
                      <td>
                        <a href="{{ route('backend.auction.itemList', ['saleID' => $sale->id]) }}" class="btn btn-warning">Modify</a>
                        <a href="" class="btn btn-danger">Delete</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>

              {{ $sales->links() }}

            </div><!-- /.box-header -->
            <div class="box-body">

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

