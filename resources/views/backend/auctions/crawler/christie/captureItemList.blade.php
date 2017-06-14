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
                <li> Christie</li>
                <li> Catpure</li>
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
                            <h3 class="box-title">Sale Content From Christie</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="form-group">

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>Image</th>
                                        <th>Int Sale ID</th>
                                        <th>Sale ID</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>

                                            <tr>
                                                <td><img src="{{ $saleArray['sale']['image_path'] }}" height="150px"></td>
                                                <td>{{ $intSaleID }}</td>
                                                <td>{{ $saleArray['sale']['id'] }}</td>
                                                <td>
                                                    EN -{{ $saleArray['sale']['en']['title'] }}<br>
                                                    Trad -{{ $saleArray['sale']['trad']['title'] }}<br>
                                                    Sim -{{ $saleArray['sale']['sim']['title'] }}
                                                </td>
                                                <td>
                                                    @if($saleArray === false)
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
                                                    @if($saleArray !== false)
                                                        <a href="{{ route('backend.auction.christie.capture.itemList', ['intSaleID' => $intSaleID]) }}" class="btn btn-primary">Examine</a>
                                                    @endif
                                                    <a href="{{ route('backend.auction.christie.capture.downloadImages', ['intSaleID' => $intSaleID]) }}" class="btn btn-primary">Download Images</a>
                                                    <a href="{{ route('backend.auction.christie.crawler.remove', ['$intSaleID' => $intSaleID]) }}" class="btn btn-danger" onclick="return delete_sale({{$intSaleID}});">Remove</a>
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
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <th>EN</th>
                                                        <th>Trad</th>
                                                        <th>Sim</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <b>Title:</b><br>{!! $lot['en']['title'] !!}
                                                                <hr>
                                                                <b>Desc:</b><br>{!! $lot['en']['description'] !!}
                                                            </td>
                                                            <td>
                                                                <b>Title:</b><br>{!! $lot['trad']['title'] !!}
                                                                <hr>
                                                                <b>Desc:</b><br>{!! $lot['trad']['description'] !!}
                                                            </td>
                                                            <td>
                                                                <b>Title:</b><br>{!! $lot['sim']['title'] !!}
                                                                <hr>
                                                                <b>Desc:</b><br>{!! $lot['sim']['description'] !!}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <div><b>Image Path:</b> <a href="{{ $lot['image_path'] }}" target="_blank">{{ $lot['image_path'] }}</a></div>
                                                <div><b>Maker:</b> {{ $lot['maker'] }}</div>
                                                <div><b>Dimensions:</b> {{ $lot['medium-dimensions'] }}</div>
                                                <div><b>Estimate:</b> {{ $lot['estimate'] }}</div>
                                                <div><b>Realized:</b> {{ $lot['price'] }}</div>
                                                <div>
                                                    <img src="{{ asset('images/auctions/christie/sale/'.$intSaleID.'/'.$lot['number'].'-150.jpg') }}" class="img-responsive">
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

