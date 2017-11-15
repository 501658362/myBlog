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
    <!-- start Dplus --><script type="text/javascript">!function(a,b){if(!b.__SV){var c,d,e,f;window.dplus=b,b._i=[],b.init=function(a,c,d){function g(a,b){var c=b.split(".");2==c.length&&(a=a[c[0]],b=c[1]),a[b]=function(){a.push([b].concat(Array.prototype.slice.call(arguments,0)))}}var h=b;for("undefined"!=typeof d?h=b[d]=[]:d="dplus",h.people=h.people||[],h.toString=function(a){var b="dplus";return"dplus"!==d&&(b+="."+d),a||(b+=" (stub)"),b},h.people.toString=function(){return h.toString(1)+".people (stub)"},e="disable track track_links track_forms register unregister get_property identify clear set_config get_config get_distinct_id track_pageview register_once track_with_tag time_event people.set people.unset people.delete_user".split(" "),f=0;f<e.length;f++)g(h,e[f]);b._i.push([a,c,d])},b.__SV=1.1,c=a.createElement("script"),c.type="text/javascript",c.charset="utf-8",c.async=!0,c.src="//w.cnzz.com/dplus.php?id=1269662568",d=a.getElementsByTagName("script")[0],d.parentNode.insertBefore(c,d)}}(document,window.dplus||[]),dplus.init("1269662568");</script><!-- end Dplus -->
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
{{--<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1269272405'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1269272405%26online%3D1%26show%3Dline' type='text/javascript'%3E%3C/script%3E"));</script>--}}
</body>
</html>