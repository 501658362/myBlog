@extends('auth.layout')
@section("content")
	<div id="login-box" class="login-box visible widget-box no-border visible">
		<div class="widget-body">
			<div class="widget-main">
				<h4 class="header blue lighter bigger">
					<i class="icon-coffee green"></i>
					{!! trans('language.login_info') !!}
				</h4>

				<div class="space-6"></div>

				<form action="{!! url('auth/login') !!}" method="post">
					{!! csrf_field() !!}
					<fieldset>
						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="email" id="email" value="{!! old('email') !!}" placeholder="{!! trans('language.email_placeholder') !!}" />
															<i class="icon-user"></i>
														</span>
						</label>

						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" name="password"  id="password" placeholder="{!! trans('language.password_placeholder') !!}" />
															<i class="icon-lock"></i>
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
						<div class="space"></div>

						<div class="clearfix">
							<label class="inline">
								<input type="checkbox" name="remember" class="ace" {!! old('remember')?'checked':'' !!} />
								<span class="lbl"> {!! trans('language.remember_me') !!}</span>
							</label>

							<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
								<i class="icon-key"></i>
								{!! trans('language.login') !!}
							</button>
						</div>

						<div class="space-4"></div>
					</fieldset>
				</form>

				<!--<div class="social-or-login center">-->
				<!--<span class="bigger-110">Or Login Using</span>-->
				<!--</div>-->

				<!--<div class="social-login center">-->
				<!--<a class="btn btn-primary">-->
				<!--<i class="icon-facebook"></i>-->
				<!--</a>-->

				<!--<a class="btn btn-info">-->
				<!--<i class="icon-twitter"></i>-->
				<!--</a>-->

				<!--<a class="btn btn-danger">-->
				<!--<i class="icon-google-plus"></i>-->
				<!--</a>-->
				<!--</div>-->
			</div><!-- /widget-main -->

			<div class="toolbar clearfix">
				<div>
					<a href="{!! url('auth/email') !!}"  class="forgot-password-link">
						<i class="icon-arrow-left"></i>
						{!! trans('language.forgot_password') !!}
					</a>
				</div>

				<div>
					<a href="{!! url('auth/register') !!}"  class="user-signup-link">
						{!! trans('language.register') !!}
						<i class="icon-arrow-right"></i>
					</a>
				</div>
			</div>
		</div><!-- /widget-body -->
	</div><!-- /login-box -->
@stop