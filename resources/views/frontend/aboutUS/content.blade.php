<div id="static-page">

    <div id="static-page-title">@lang('thevalue.contact-us')</div>

    <div id="static-page-content">@lang('thevalue.aboutUSContent')</div>

</div>

<style>
    #aboutus-map iframe {
        width: 100% !important;
        /*height: 100% !important;*/
    }
</style>

<div id="aboutus-map">@lang('thevalue.googleMap')</div>

<div id="block" style="border: 0px !important">
    <div>{{ trans('thevalue.address-title') }}: <span id="aboutus-address">@lang('thevalue.address')</span></div>
    <div>{{ trans('thevalue.tel-title') }}: <span id="aboutus-tel">+852 3972 5725</span></div>
    {{--<div>{{ trans('thevalue.fax-title') }}: <span id="aboutus-fax"></span></div>--}}
    <div>{{ trans('thevalue.email-title') }}: <span id="aboutus-email">itsupport@thevalue.com</span></div>
</div>