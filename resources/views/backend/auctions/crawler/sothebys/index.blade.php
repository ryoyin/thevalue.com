@extends('backend.template.layout')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Crawler - Sothebys</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
                <li> Auction</li>
                <li> Crawler</li>
                <li> Sothebys</li>
                <li class="active"> Spider</li>
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
                            <h3 class="box-title">Capture Sale Content From Sothebys</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <form method="POST" action="{{ route('backend.auction.sothebys.crawler') }}" class="form-group">
                                {{ csrf_field() }}
                                <label>Sale URL: </label>
                                <input type="text" id="url" name="url" required>
                                <input type="submit">
                            </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>ID</th>
                            <th>Int Sale ID</th>
                            <th>Info</th>
                            <th>HTML</th>
                            <th>JSON</th>
                            <th>Image</th>
                            <th>Resize</th>
                            <th>Push S3</th>
                            {{--<th>Import</th>--}}
                            <th>Auction Date</th>
                            <th>Status</th>
                            <th>Auction</th>
                        </thead>
                        <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->int_sale_id }}</td>
                                <td>
                                    {{ $sale->url }}<br>
                                    {{ $sale->title }}
                                </td>
                                <td>{{ isDone($sale->html) }}</td>
                                <td>{{ isDone($sale->json) }}</td>
                                <td>{{ isDone($sale->image) }}</td>
                                <td>{{ isDone($sale->resize) }}</td>
                                <td>{{ isDone($sale->pushS3) }}</td>
                                {{--<td>{{ isDone($sale->import) }}</td>--}}
                                <?php

                                    if($sale->start_date != null) {
                                        $start_date_timestamp = strtotime($sale->start_date);
                                        $end_date_timestamp = strtotime($sale->end_date);

                                        $startDatetime = date('Y-m-d H:i:s T', $start_date_timestamp);
                                        $endDatetime = date('Y-m-d H:i:s T', $end_date_timestamp);
                                    } else {
                                        $startDatetime = 'N.A.';
                                        $endDatetime = 'N.A.';
                                    }

                                ?>
                                <td>{{ $startDatetime }}<br>
                                    {{ $endDatetime }}</td>
                                <td>{{ $sale->status }}</td>
                                <td>
                                    @if(!$sale->html)
                                        <a href="{{ route('backend.auction.sothebys.crawler.capture', ['intSaleID' => $sale->int_sale_id]) }}"
                                           class="btn btn-primary">
                                            Download HTML</a>
                                    @elseif(!$sale->json)
                                        <a href="{{ route('backend.auction.sothebys.crawler.capture.items', ['intSaleID' => $sale->int_sale_id]) }}"
                                           class="btn btn-primary">
                                            Prepare Info</a>
                                    @elseif(!$sale->image)
                                        <form method="POST" action="{{ route('backend.auction.sothebys.crawler.capture.images', ['intSaleID' => $sale->int_sale_id]) }}">
                                            {{ csrf_field() }}
                                            Sale Image URL: <input type="text" name="sale_image_path">
                                            <input type="submit" class="btn btn-primary" value="Download Images">
                                        </form>
                                    @elseif(!$sale->resize)
                                        <a href="{{ route('backend.auction.sothebys.crawler.capture.resize', ['intSaleID' => $sale->int_sale_id]) }}"
                                           class="btn btn-primary">
                                            Resize Images</a>
                                    @elseif(!$sale->pushS3)
                                        <a href="{{ route('backend.auction.sothebys.crawler.capture.uploadS3', ['intSaleID' => $sale->int_sale_id]) }}"
                                           class="btn btn-primary">
                                            Push Images S3</a>
                                    {{--@elseif(!$sale->import)
                                        <a href="{{ route('backend.auction.sothebys.crawler.capture.examine', ['intSaleID' => $sale->int_sale_id]) }}"
                                           class="btn btn-primary">
                                            Examine</a>--}}
                                    @elseif(!$sale->status && $sale->import == 1)
                                        <form method="POST" action="{{ route('backend.auction.sothebys.crawler.capture.getRealizedPrice', ['intSaleID' => $sale->int_sale_id]) }}">
                                            {{ csrf_field() }}
                                            URL: <input type="text" name="url">
                                            <input type="submit" value="Get Realized Price" class="btn btn-primary">
                                        </form>
                                        <br>
                                        <a href="{{ route('backend.auction.sothebys.crawler.capture.confirmRealizedPrice', ['intSaleID' => $sale->int_sale_id]) }}"
                                           class="btn btn-primary">
                                            Confirm Realized Price</a>
                                    @endif
                                    <a href="{{ route('backend.auction.sothebys.crawler.capture.sorting', ['intSaleID' => $sale->int_sale_id]) }}"
                                       class="btn btn-primary">
                                        Sorting</a>
                                    <a href="{{ route('backend.auction.sothebys.crawler.remove', ['intSaleID' => $sale->int_sale_id]) }}"
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

