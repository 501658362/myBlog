<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $meta_description }}">
    <meta name="author" content="{{ config('blog.author') }}">
    <meta name="baidu-site-verification" content="b3yOLF9KNE" />
    <title>{{ $title or config('blog.title') }}</title>
    <link rel="alternate" type="application/rss+xml" href="{{ url('rss') }}"
          title="RSS Feed {{ config('blog.title') }}">

    {{-- Styles --}}
    <link href="{!! asset("assets/css/blog.css") !!}" rel="stylesheet">
    <link rel="shortcut icon" href="{!! asset("assets/images/favicon.ico") !!}" type="image/vnd.microsoft.icon" />
    <link rel="icon" href="{!! asset("assets/images/favicon.ico") !!}" type="image/vnd.microsoft.icon" />
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

<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=2113073"></script>

{{--添加站点统计--}}
{{--<script src="https://s22.cnzz.com/z_stat.php?id=1269272405&web_id=1269272405" language="JavaScript"></script>--}}
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1269272405'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1269272405%26online%3D1%26show%3Dline' type='text/javascript'%3E%3C/script%3E"));</script>
</body>
</html>