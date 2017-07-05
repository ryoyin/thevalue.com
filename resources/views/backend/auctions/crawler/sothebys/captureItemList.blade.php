@extends('backend.template.layout')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Crawler - Christie - Capture - Item List</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
                <li> Auction</li>
                <li> Crawler</li>
                <li> Sothebys</li>
                <li> Examine</li>
                <li class="active"> Item List</li>
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
                            <h3 class="box-title">Sale Content From Sothebys</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">
                                <table class="table table-bordered table-striped sale-header">
                                    <tbody>
                                        <tr>
                                            <td><img src="{{ asset($saleArray['sale']['stored_image_path']) }}" height="150px"></td>
                                            <td>
                                                <b>Sothebys Internal ID:</b> {{ $intSaleID }}
                                                <br>
                                                <b>Sale Title:</b> {{ $saleArray['sale']['en']['title'] }}<br>
                                                <b>Auction Date:</b> {{ date('Y-m-d H:i:s', $saleArray['sale']['en']['auction']['datetime']) }}<br>
                                                <b>Auction Location:</b> {{ $saleArray['sale']['en']['auction']['location'] }}<br>
                                                <b>Viewing Date:</b> {{ date('Y-m-d H:i:s', $saleArray['sale']['en']['viewing']['datetime']['start']) }} - {{ date('Y-m-d H:i:s', $saleArray['sale']['en']['viewing']['datetime']['end']) }}<br>
                                                <b>Viewing Location:</b> {{ $saleArray['sale']['en']['viewing']['location'] }}<br>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('backend.auction.sothebys.crawler.capture.import', ['intSaleID' => $intSaleID]) }}" class="form-group">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>Series</label>
                                                        <input name="auction_series_id" type="text" class="form-control" placeholder="Enter ..." required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Slug</label>
                                                        <input name="slug" type="text" class="form-control" placeholder="Enter ..." required>
                                                    </div>
                                                    <div class="form-footer">
                                                        <input name="submit" type="submit" class="form-control">
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Lot List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <th>No.</th>
                                    <th>Content</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach($saleArray['lots'] as $lot)
                                        <tr>
                                            <td>{{ $lot['number'] }}</td>
                                            <td>
                                                <b>Title:</b> {!! $lot['en']['title'] !!}<br>
                                                <b>Desc:</b> {!! $lot['en']['description'] !!}
                                                <hr>
                                                <b>Title:</b> {!! $lot['zh']['title'] !!}<br>
                                                <b>Desc:</b> {!! $lot['zh']['description'] !!}
                                                <hr>
                                                <div><b>Estimate:</b> {{ $lot['estimate_initial'] }} - {{ $lot['estimate_end'] }}</div>


                                            </td>
                                            <td>
                                                <div><b>Image Path:</b> <a href="{{ $lot['source_image_path'] }}" target="_blank">{{ $lot['source_image_path'] }}</a></div>

                                                <div>
                                                    @if(isset($lot['stored_image_path']))
                                                        <img src="{{ asset($lot['stored_image_path']['small']) }}" class="img-responsive">
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

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
//    alert(title);
            var cfm = confirm("Are you sure delete ID: "+id);

            if(cfm === false) {
                return false;
            }

        }
    </script>
@endsection

