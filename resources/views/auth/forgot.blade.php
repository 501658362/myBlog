@extends('layouts.auth.layout')
@section("content")
	<div id="forgot-box" class="forgot-box widget-box no-border visible">
		<div class="widget-body">
			<div class="widget-main">
				<h4 class="header red lighter bigger">
					<i class="icon-key"></i>
					{!! trans('language.retrieve_password_title') !!}
				</h4>

				<div class="space-6"></div>
				<p>
					{!! trans('language.retrieve_password_info') !!}
				</p>

				<form action="{!! url('auth/email') !!}" method="post">
					{!! csrf_field() !!}
					<fieldset>
						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" id="name"  value="{!! old('email') !!}" name="email" placeholder="{!! trans('language.email_placeholder') !!}" />
															<i class="icon-envelope"></i>
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

						@if(session('status'))
						<div class="green block">
							<ul class="list-unstyled spaced">
									<li>{{ session('status') }}</li>
							</ul>
						</div>
						@endif
						<div class="clearfix">
							<button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
								<i class="icon-lightbulb"></i>
								{!! trans('language.send') !!}
							</button>
						</div>
					</fieldset>
				</form>
			</div><!-- /widget-main -->

			<div class="toolbar center">
				<a href="{!! url('auth/login') !!}" class="back-to-login-link">
					{!! trans('language.return_login') !!}
					<i class="icon-arrow-right"></i>
				</a>
			</div>
		</div><!-- /widget-body -->
	</div><!-- /forgot-box -->

@stop