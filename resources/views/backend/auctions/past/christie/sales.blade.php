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
                <li> Past</li>
                <li> Christie</li>
                <li class="active"> Sales</li>
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
                            <hr>
                            <form action="{{ route('backend.auction.christie.capture') }}" method="get">
                                Internal Sale ID: <input type="text" name="int_sale_id" value="{{ @$_GET['int_sale_id'] }}">
                                Sale ID: <input type="text" name="sale_id" value="{{ @$_GET['sale_id'] }}">
                                <input type="submit">
                            </form>
                        </div><!-- /.box-header -->

                        <hr>

                        <div class="box-body">

                            @if(isset($sales))

                                <div class="form-group">

                                    <table class="table table-bordered table-striped">

                                        <thead>
                                            <th>ID</th>
                                            <th>Year</th>
                                            <th>Month</th>
                                            <th>Internal Sale ID</th>
                                            <th>Retrieve Server</th>
                                            <th>Data</th>
                                            <th>Auction</th>
                                        </thead>

                                        <tbody>
                                            @foreach($sales as $sale)
                                                <tr>
                                                    <td>{{ $sale->id }}</td>
                                                    <td>{{ $sale->year }}</td>
                                                    <td>{{ $sale->month }}</td>
                                                    <td>{{ $sale->int_sale_id }}</td>
                                                    <td>{{ $sale->retrieve_server }}</td>
                                                    <td>
                                                        @if($sale->json == 1)
                                                            <button class="btn btn-primary">Exists</button>
                                                            @else
                                                            <button class="btn btn-danger">Not Exist</button>
                                                        @endif
                                                    </td>
                                                    <td><a href="#" class="btn btn-primary">Examine</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                    {{ $sales->links() }}

                                </div>

                            @else

                                No result found!

                            @endif

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
            $("#records").DataTable({
                "order": [[0, "desc"]],
                "pageLength": 5
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

