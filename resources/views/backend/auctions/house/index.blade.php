@extends('backend.template.layout')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Houses</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
        <li class="active">Houses</li>
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
              <h3 class="box-title">List</h3> <a href="{{ action('Backend\AuctionHouseController@create') }}" type="button" class="btn btn-primary" style="padding: 3px 10px; margin-left: 10px;">Add</a>
            </div><!-- /.box-header -->
            <div class="box-body">
              <table id="research" class="table table-bordered table-striped">
                <thead>
                  <th>ID</th>
                  <th>Company Logo</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Status</th>
                  <th style="text-align: center;">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($houses as $house)
                  <?php
                          $houseDetail = $house->details()->where('lang', 'trad')->first();
                  ?>
                  <tr>
                    <td>{{ $house['id'] }}</td>
                    <td><img src="{{ asset($house['image_path']) }}" width="100px;"></td>
                    <td>{{ $houseDetail['name'] }}</td>
                    <td>{{ $house['slug'] }}</td>
                    <td>{{ showStatusSim($house['status']) }}</td>
                    <td align="center">
                      <a href="{{ url('tvadmin/auction/house/'.$house->id.'/edit') }}" class="btn btn-warning">Modify</a>
                      <form action="{{ url('tvadmin/auction/house/'.$house->id) }}" method="POST" style="display: inline-block">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        <button type="submit" class="btn btn-danger" onclick="return delete_research('{{ $house['id'] }}');">
                          <i class="fa fa-trash"></i> Delete
                        </button>
                      </form>
                    </td>
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

      function copyClipBoard(obj) {
          var copyTarget = $(obj).parent().children('span').html();

      }
  </script>
@endsection

