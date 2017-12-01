<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="陈彦瑾的博客,陈彦瑾,chenyanjin.陈超鹏">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $meta_description }}  陈彦瑾,陈超鹏,陈彦瑾的博客,chenyanjin,chenchaopeng,chenyanjin.top,www.chenyanjin.top,彦瑾,陈,博客">
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
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body>
@include('web.blog.partials.page-nav')

@yield('page-header')
@yield('content')

@include('web.blog.partials.page-footer')

{{-- Scripts --}}
<script src="{!! asset("assets/js/blog.js") !!}"></script>
@yield('scripts')


{{--添加站点统计--}}
{{--<script src="https://s22.cnzz.com/z_stat.php?id=1269272405&web_id=1269272405" language="JavaScript"></script>--}}
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1269272405'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1269272405%26online%3D1%26show%3Dline' type='text/javascript'%3E%3C/script%3E"));</script>

<script>
    // 自动推送是百度搜索资源平台为提高站点新增网页发现速度推出的工具，安装自动推送JS代码的网页，在页面被访问时，页面URL将立即被推送给百度。
    (function(){
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https') {
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        }
        else {
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
</script>

<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=2113073"></script>

</body>
</html>