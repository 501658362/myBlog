@extends('layouts.auth.layout')
@section("content")
	<div id="signup-box" class="signup-box widget-box no-border visible">
		<div class="widget-body">
			<div class="widget-main">
				<h4 class="header green lighter bigger">
					<i class="icon-group blue"></i>
					{!! trans('language.register_title') !!}
				</h4>

				<div class="space-6"></div>
				<p>
					{!! trans('language.register_info') !!}</p>

				<form action="{!! url('auth/register') !!}" method="post">
					{!! csrf_field() !!}
					<fieldset>
						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" name="email" value="{!! old('email') !!}" id="email" placeholder="{!! trans('language.email_placeholder') !!}" />
															<i class="icon-envelope"></i>
														</span>
						</label>

						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="name" value="{!! old('name') !!}" id="name" placeholder="{!! trans('language.name_placeholder') !!}" />
															<i class="icon-user"></i>
														</span>
						</label>

						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" name="password" id="password" placeholder="{!! trans('language.password_placeholder') !!}" />
															<i class="icon-lock"></i>
														</span>
						</label>

						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" name="password_confirmation"  id="password_confirmation" placeholder="{!! trans('language.confirm_password_placeholder') !!}" />
															<i class="icon-retweet"></i>
														</span>
						</label>

						<label class="block">
							<input type="checkbox" class="ace" />
														<span class="lbl">
															{!! trans('language.accept') !!}
															<a href="#">
																{!! trans('language.user_agreement') !!}
															</a>
														</span>
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
						<div class="space-24"></div>

						<div class="clearfix">
							<button type="reset" class="width-30 pull-left btn btn-sm">
								<i class="icon-refresh"></i>
								{!! trans('language.reset') !!}
							</button>

							<button type="submit" class="width-65 pull-right btn btn-sm btn-success">
								{!! trans('language.register') !!}
								<i class="icon-arrow-right icon-on-right"></i>
							</button>
						</div>
					</fieldset>
				</form>
			</div>

			<div class="toolbar center">
				<a href="{!! url('auth/login') !!}"  class="back-to-login-link">
					<i class="icon-arrow-left"></i>
					{!! trans('language.return_login') !!}
				</a>
			</div>
		</div><!-- /widget-body -->
	</div><!-- /signup-box -->
@stop