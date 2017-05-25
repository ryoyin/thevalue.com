<div id="header-bar">

    <div class="pull-left" id="global-lang-block" onclick="showLang();">
        <i class="fa fa-globe pull-left" aria-hidden="true"></i>
        <ul id="global-lang" class="ul-clean pull-left">
            <li id="global-lang-en"><a href="#" onclick="changeLang(this, 'en');">EN</a></li>
            <li id="global-lang-trad"><a href="#" onclick="changeLang(this, 'trad');">繁</a></li>
            <li id="global-lang-sim"><a href="#" onclick="changeLang(this, 'sim');">简</a></li>
        </ul>
    </div>

    <div class="pull-right">
        <ul id="header-bar-misc" class="ul-clean">
            <li><i class="fa fa-envelope" aria-hidden="true" data-toggle="modal" data-target="#share-the-value"></i></li>
            {{--<li><a href="">@lang('thevalue.disclaimer')</li>--}}
            <li><a href="{{ route('frontend.disclaimer') }}">@lang('thevalue.disclaimer')</li>
            <li>|</li>
            <li><a href="{{ route('frontend.aboutus') }}">@lang('thevalue.contact-us')</li>
            {{--<li><i class="fa fa-wechat" aria-hidden="true"></i></li>
            <li><i class="fa fa-weibo" aria-hidden="true"></i></li>
            <li><i class="fa fa-twitter" aria-hidden="true"></i></li>
            <li><i class="fa fa-facebook-f" aria-hidden="true"></i></li>--}}
        </ul>
    </div>

    <div class="pull-center" style="text-align: center">
        <a href="{{ route('frontend.index') }}">THE VALUE</a>
    </div>

</div> <!--/header-bar-->

<!-- Modal -->
<div id="share-the-value" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('thevalue.subscript-the-value')</h4>
            </div>
            <div class="modal-body">
                <p>
                    <div class="input-group">
                        <input type="text" id="share-email" class="form-control" placeholder="@lang('thevalue.subscript-please-enter-email').....">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="shareme();return false;">@lang('thevalue.subscription')</button>
                        </span>
                    </div><!-- /input-group -->
                </p>
                <p id="share-the-value-invalid-email" style="color: red; display:none;">@lang('thevalue.subscript-invalid-email')</p>
                <p id="share-the-value-sent-email" style="display:none;">@lang('thevalue.subscript-sent-email')</p>
            </div>
        </div>

    </div>
</div>