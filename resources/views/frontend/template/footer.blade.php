<div style="text-align: center" class="footer-download-link">
    <ul>
        <li><a href="https://itunes.apple.com/app/the-value/id1204432093" target="_blank"><img src="{{ asset('images/company_logo/app_store_download_icon.png') }}"></a></li>
        <li><a href="https://play.google.com/store/apps/details?id=com.thevaluecoreapp" target="_blank"><img src="{{ asset('images/company_logo/gp-logo.png') }}"></a></li>
        <li>
            <a href="{{ asset('app/android/thevalue-app-release-1.4.apk') }}" style="text-decoration: none; color: #000;">
                <img src="{{ asset('images/company_logo/Android_Robot_100.png') }}" class="android-apk-logo"><br>
                <span>@lang('thevalue.download-android-apk')</span>
            </a>
        </li>
    </ul>
</div>
<div id="footer" class="pull-right">
    <a href="{{ route('frontend.aboutus') }}">@lang('thevalue.contact-us')</a> |
    <a href="{{ route('frontend.disclaimer') }}">@lang('thevalue.disclaimer')</a> |
    Copyright &copy; 2017 TheValue.com All Rights Reserved.
</div>