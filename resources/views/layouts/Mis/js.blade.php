<script src="{!! asset("assets/js/jquery.min.js") !!}"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

<!--[if !IE]> -->

<script type="text/javascript">
    window.jQuery || document.write("<script src='{!! asset("assets/js/jquery-2.0.3.min.js") !!}'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='{!! asset("assets/js/jquery-1.10.2.min.js") !!}'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if("ontouchend" in document) document.write("<script src='{!! asset("assets/js/jquery.mobile.custom.min.js") !!}'>"+"<"+"/script>");
</script>
<script src="{!! asset("assets/js/bootstrap.min.js") !!}"></script>
<script src="{!! asset("assets/js/typeahead-bs2.min.js") !!}"></script>

<!-- page specific plugin scripts -->

<script src="{!! asset("assets/js/jquery-ui-1.10.3.custom.min.js") !!}"></script>
<script src="{!! asset("assets/js/jquery.ui.touch-punch.min.js") !!}"></script>
<script src="{!! asset("assets/js/fullcalendar.min.js") !!}"></script>
<script src="{!! asset("assets/js/bootbox.min.js") !!}"></script>

<!-- ace scripts -->

<script src="{!! asset("assets/js/ace-elements.min.js") !!}"></script>
<script src="{!! asset("assets/js/ace.min.js") !!}"></script>

<script src="{!! asset("assets/js/jquery.validate.min.js") !!}"></script>

<script src="{!! asset("assets/js/jquery.autosize.min.js") !!}"></script>
<script src="{!! asset("assets/js/jquery.inputlimiter.1.3.1.min.js") !!}"></script>

<script src="{!! asset("assets/js/my-js/base.js") !!}"></script>

<script>
    $('textarea[class*=autosize]').autosize({append: "\n"});
    $('textarea.limited').inputlimiter({
        remText: '剩 %n 字可以输入',
        limitText: '最多允许输入 : %n 个字'
    });
</script>