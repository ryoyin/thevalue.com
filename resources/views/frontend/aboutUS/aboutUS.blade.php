@extends('frontend.template.layout')

@section('content')

    <script src="{{ asset('js/aboutus.js') }}"></script>

    <hr style="padding: 0; margin:0">

    <div id="aboutus-content"></div>

    <style>
        #aboutus-map iframe {
            width: 100% !important;
            /*height: 100% !important;*/
        }
    </style>

    <div id="aboutus-map"></div>

    <div id="block" style="border: 0px !important">
        <div>{{ trans('thevalue.address-title') }}: <span id="aboutus-address"></span></div>
        <div>{{ trans('thevalue.tel-title') }}: <span id="aboutus-tel"></span></div>
        <div>{{ trans('thevalue.fax-title') }}: <span id="aboutus-fax"></span></div>
        <div>{{ trans('thevalue.email-title') }}: <span id="aboutus-email"></span></div>
    </div>


@endsection