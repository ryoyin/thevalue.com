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
                <li class="active"> Catpure</li>
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
                                Year: <input type="text" name="year" value="{{ @$_GET['year'] }}">
                                Month: <input type="text" name="month" value="{{ @$_GET['month'] }}">
                                <input type="submit">
                            </form>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            @if(isset($sales))

                                <div class="form-group">

                                    Sale Count: {{ count($sales) }}

                                    <table class="table table-bordered table-striped">

                                        <thead>
                                            <th>Image</th>
                                            <th>Int Sale ID</th>
                                            <th>Sale ID</th>
                                            <th>Title</th>
                                            <th>Images</th>
                                            <th>Push S3</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </thead>

                                        <tbody>
                                            @foreach($sales as $sale)
                                                <?php
                                                    $intSaleID = $sale->int_sale_id;
                                                    $saleContent = $salesArray[$intSaleID];
                                                ?>
                                                <tr>
                                                    <td><img src="{{ $saleContent['sale']['image_path'] }}" height="150px"></td>
                                                    <td>{{ $intSaleID }}</td>
                                                    <td>{{ $saleContent['sale']['id'] }}</td>
                                                    <td>
                                                        EN -{{ $saleContent['sale']['en']['title'] }}<br>
                                                        Trad -{{ $saleContent['sale']['trad']['title'] }}<br>
                                                        Sim -{{ $saleContent['sale']['sim']['title'] }}
                                                    </td>
                                                    <td>{{ $sale->download_images }}</td>
                                                    <td>{{ $sale->push_s3 }}</td>
                                                    <td>
                                                        @if($saleContent === false)
                                                            Bad
                                                        @else
                                                            Good
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="{{ route('backend.auction.christie.crawler') }}" class="form-group">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" id="int_sale_id" name="int_sale_id" value="{{ $intSaleID }}">
                                                            <input type="submit" value="Try Again" class="btn btn-warning">
                                                        </form>
                                                        @if($saleContent !== false)
                                                            <a href="{{ route('backend.auction.christie.capture.itemList', ['intSaleID' => $intSaleID]) }}" class="btn btn-primary">Examine</a>
                                                        @endif
                                                        <a href="{{ route('backend.auction.christie.crawler.remove', ['$intSaleID' => $intSaleID]) }}" class="btn btn-danger" onclick="return delete_sale({{$intSaleID}});">Remove</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

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

