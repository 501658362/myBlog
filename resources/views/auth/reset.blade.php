@extends('layouts.auth.layout')
@section("content")
	<div id="reset-box" class="reset-box widget-box no-border visible">
		<div class="widget-body">
			<div class="widget-main">
				<h4 class="header green lighter bigger">
					<i class="icon-group blue"></i>
					{!! trans('language.reset_title') !!}
				</h4>

				<div class="space-6"></div>
				<p>
					{!! trans('language.reset_info') !!}</p>

				<form action="{!! url('password/reset') !!}" method="post">
					{!! csrf_field() !!}
					<input type="hidden" name="token" value="{{ $token }}">
					<fieldset>
						<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" name="email" value="{!! old('email') !!}" id="email" placeholder="{!! trans('language.email_placeholder') !!}" />
															<i class="icon-envelope"></i>
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
								{!! trans('language.save') !!}
								<i class="icon-arrow-right icon-on-right"></i>
							</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div><!-- /widget-body -->
	</div><!-- /reset-box -->
@stop