<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $meta_description }}">
    <meta name="author" content="{{ config('blog.author') }}">
    <meta name="baidu-site-verification" content="9uAVSVY5kF" />
    <title>{{ $title or config('blog.title') }}</title>
    <link rel="alternate" type="application/rss+xml" href="{{ url('rss') }}"
          title="RSS Feed {{ config('blog.title') }}">

    {{-- Styles --}}
    <link href="{!! asset("assets/css/blog.css") !!}" rel="stylesheet">
    <link rel="shortcut icon" href="{!! asset("assets/images/favicon.png") !!}" type="image/vnd.microsoft.icon" />
    <link rel="icon" href="{!! asset("assets/images/favicon.png") !!}" type="image/vnd.microsoft.icon" />
    @yield('styles')

  {{-- HTML5 Shim and Respond.js for IE8 support --}}
  <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
@include('web.blog.partials.page-nav')

@yield('page-header')
@yield('content')

@include('web.blog.partials.page-footer')

{{-- Scripts --}}
<script src="{!! asset("assets/js/blog.js") !!}"></script>
@yield('scripts')

</body>
</html>