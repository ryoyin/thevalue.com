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
                <li class="active"> Import Sale</li>
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

                            <form method="POST" action="{{ route('backend.auction.sothebys.sale.uploadSaleFile') }}" class="form-group" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <label>Import Sale File: </label>
                                <input type="file" id="upload_file" name="upload_file" class="form-control" required><br>
                                <input type="submit">
                            </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Sales waiting for import</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <table class="table table-bordered table-striped sale-header">
                                    <tr>
                                        <th>Int Sale ID</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Auction Date</th>
                                        <th>Total Lots</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach($salesArray as $intSaleID => $saleArray)
                                        <tr>
                                            <td>{{ $intSaleID }}</td>
                                            <td>{{ $saleArray['sale']['en']['title'] }}</td>
                                            <td>{{ $saleArray['sale']['en']['auction']['location'] }}</td>
                                            <td>{{ date('Y-m-d H:i:s T', $saleArray['sale']['en']['auction']['datetime']['start_datetime']) }} - {{ date('Y-m-d H:i:s T', $saleArray['sale']['en']['auction']['datetime']['end_datetime']) }}</td>
                                            <td>{{ count($saleArray['sale']['en']['lots']) }}</td>
                                            <td>
                                                <form method="post" action="{{ route('backend.auction.sothebys.sale.importSaleFile', ['intSaleID' => $intSaleID]) }}">
                                                    <?php
                                                        $slug = strtolower($saleArray['sale']['en']['title']);
                                                        $slug = str_replace(' ', '-', $slug);
                                                        $slug = str_replace('&', 'n', $slug);
                                                    ?>
                                                    {{ csrf_field() }}
                                                    <table class="table table-bordered table-striped sale-header">
                                                        <tr>
                                                            <th>Auction Series ID</th>
                                                            <th>Slug</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="text" name="auction_series_id" required></td>
                                                            <td><input type="text" name="slug" required value="{{ $slug }}"></td>
                                                            <td><input type="submit" value="Import" class="btn btn-primary"></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

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

        function delete_sale(id) {

            var cfm = confirm("Are you sure delete ID: "+id);

            if(cfm === false) {
                return false;
            }

        }
    </script>
@endsection

