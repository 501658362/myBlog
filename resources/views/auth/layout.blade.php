<!DOCTYPE html>
<html lang="en">
<head>

    @include('layouts.Mis.meta')

    <title>BLOG MIS</title>

    @include('layouts.Mis.css')
</head>

<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="icon-leaf green"></i>
                            <span class="red">陈彦瑾</span>
                            <span class="white">博客</span>
                        </h1>
                        <h4 class="blue">&copy; 某某科技公司 <small>	<a class="white" href="{!! url('contact') !!}">	{!! trans('language.contact_title') !!}</a></small> </h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        @yield('content')
                    </div><!-- /position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->

@include("layouts.Mis.js")
        <!-- inline scripts related to this page -->


@yield('script')
</body>
<script>
    $('form').form_validate();

</script>
</html>
