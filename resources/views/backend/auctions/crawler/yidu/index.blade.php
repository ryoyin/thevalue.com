@extends('backend.template.layout')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Crawler - YiDu</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
                <li> Auction</li>
                <li> Crawler</li>
                <li class="active"> YiDu</li>
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
                            <h3 class="box-title">Capture Sale Content From YiDu</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <form method="POST" action="{{ route('backend.auction.yidu.crawler') }}" class="form-group">
                                {{ csrf_field() }}
                                <label>Int Sale ID: </label>
                                <input type="text" id="int_sale_id" name="int_sale_id" required>
                                <input type="submit">
                            </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>ID</th>
                            <th>Int Sale ID</th>
                            <th>HTML</th>
                            <th>JSON</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Auction</th>
                        </thead>
                        <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->int_sale_id }}</td>
                                <td>{{ isDone($sale->html) }}</td>
                                <td>{{ isDone($sale->json) }}</td>
                                <td>{{ isDone($sale->image) }}</td>
                                <td>{{ $sale->status }}</td>
                                <td>
                                    @if(!$sale->html && !$sale->json && !$sale->image)
                                    <a href="{{ route('backend.auction.yidu.crawler.capture', ['intSaleID' => $sale->int_sale_id]) }}"
                                       class="btn btn-primary">
                                        Capture</a>
                                    @elseif($sale->html && !$sale->json && !$sale->image)
                                    <a href="{{ route('backend.auction.yidu.crawler.capture.items', ['intSaleID' => $sale->int_sale_id]) }}"
                                       class="btn btn-primary">
                                        Capture</a>
                                    @else
                                    <a href="{{ route('backend.auction.yidu.crawler.capture.images', ['intSaleID' => $sale->int_sale_id]) }}"
                                       class="btn btn-primary">
                                        Capture</a>
                                    @endif
                                    <a href="{{ route('backend.auction.yidu.crawler.remove', ['intSaleID' => $sale->int_sale_id]) }}"
                                       class="btn btn-danger" onclick="return delete_sale({{$sale->int_sale_id}});">
                                        Remove</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


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

        function delete_sale(id) {

            var cfm = confirm("Are you sure delete ID: "+id);

            if(cfm === false) {
                return false;
            }

        }
    </script>
@endsection

