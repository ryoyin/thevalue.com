@extends('backend.template.layout')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Crawler - Christie</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
                <li> Auction</li>
                <li> Crawler</li>
                <li> Christie</li>
                <li class="active"> Past Auction</li>
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
                            <h3 class="box-title">Spider Records</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <td>Year</td>
                                    <td>Month</td>
                                    <td>Int Sale ID</td>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)

                                        <tr>
                                            <td>{{ $sale->year }}</td>
                                            <td>{{ $sale->month }}</td>
                                            <td>{{ $sale->int_sale_id }}</td>
                                            <td>
                                                <?php

                                                $saleSpiderDetail = $sale->details;
                                                dd($saleSpiderDetail);
                                                ?>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>



                            {{ $sales->links() }}

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
//                "order": [[0, "desc"]]
            });
        });
    </script>
@endsection

