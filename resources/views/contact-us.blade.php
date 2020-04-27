@extends('layouts.auth.layout')
@section("content")
    <div id="contact-box" class="contact widget-box no-border visible">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header red lighter bigger">
                    <i class="icon-key"></i>
                    {!! trans('language.contact_title') !!}
                </h4>

                <div class="space-6"></div>
                <p>
                    {!! trans('language.contact_info') !!}
                </p>

                <form action="{!! url('contact') !!}" method="post">
                    {!! csrf_field() !!}
                    <fieldset>
                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" name="email"
                                                                   value="{!! old('email') !!}" id="email"
                                                                   placeholder="{!! trans('language.email_placeholder') !!}"/>
															<i class="icon-envelope"></i>
														</span>

                        </label>


                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="name"
                                                                   value="{!! old('name') !!}" id="name"
                                                                   placeholder="{!! trans('language.name_placeholder') !!}"/>
															<i class="icon-user"></i>
														</span>
                        </label>

                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="phone"
                                                                   value="{!! old('phone') !!}" id="phone" required
                                                                   placeholder="{!! trans('language.phone_placeholder') !!}"/>
															<i class=" 	fa fa-phone"
                                                               style="display: inline-block;left: auto;right: 3px;"></i>
														</span>
                        </label>


                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<textarea class="form-control limited autosize-transition "
                                                                      maxlength="255" rows="5" name="message"
                                                                      id="message" required
                                                                      placeholder="{!! trans('language.message') !!}">{!! old('message') !!}</textarea>
															<i class="fa fa-file-text"></i>
														</span>
                        </label>


                        <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="code"
                                                                   value="" id="code"
                                                                   placeholder="请输入右下方的答案"/>
															<i class="icon-envelope"></i>
														</span>
                            <button type="button" name="submitType" value="1" class="width-35 pull-right btn btn-sm btn-success">
                               {!! $code !!}
                            </button>
                        </label>

                        @if (count($errors) > 0)
                            <div class="red error-info">
                                <ul class="list-unstyled spaced">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('Success'))
                            <div class="green block">
                                <ul class="list-unstyled spaced">
                                    <li>{{ session('Success') }}</li>
                                </ul>
                            </div>
                        @endif
                        <div class="clearfix">
                            <button type="submit" class="width-100 pull-right btn btn-sm btn-danger">
                                <i class="icon-lightbulb"></i>
                                {!! trans('language.send') !!}
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- /widget-main -->

            {{--<div class="toolbar center" style="    background: #c16050;border-top: 2px solid #976559;padding: 9px 18px;">--}}
                {{--<a href="{!! url('auth/login') !!}" class="back-to-login-link" style="    color: #FE9;font-size: 14px;font-weight: bold;text-shadow: 1px 0 1px rgba(0, 0, 0, 0.25);">--}}
                    {{--{!! trans('language.return_login') !!}--}}
                    {{--<i class="icon-arrow-right"></i>--}}
                {{--</a>--}}
            {{--</div>--}}
        </div>
        <!-- /widget-body -->
    </div><!-- /contact -->
    <script>


    </script>

@stop