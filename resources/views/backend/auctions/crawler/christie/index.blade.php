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
                <li class="active"> Christie</li>
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
                            <h3 class="box-title">Capture Sale Content From Christie</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <form method="POST" action="{{ route('backend.auction.christie.crawler') }}" class="form-group">
                                {{ csrf_field() }}
                                <label>Int Sale ID: </label>
                                <input type="text" id="int_sale_id" name="int_sale_id" required>
                                <input type="submit">
                            </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Get Sale List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <form method="POST" action="{{ route('backend.auction.christie.getList') }}" class="form-group">
                                {{ csrf_field() }}
                                <label>Please enter </label><br>
                                Year: <input type="text" id="year" name="year" required>
                                Month: <input type="text" id="month" name="month" required>
                                <input type="submit">
                            </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Download Sales Images</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <a href="{{ route('backend.auction.christie.capture.listDownloadImages') }}" class="btn btn-primary">Start Download, do not close the this screen!</a>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">Spider Records</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <td>Year</td>
                                    <td>Month</td>
                                </thead>
                                <tbody>
                                    @foreach($spiderRecords as $record)
                                        <tr>
                                            <td>{{ $record->year }}</td>
                                            <td>{{ $record->month }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
    </script>
@endsection

