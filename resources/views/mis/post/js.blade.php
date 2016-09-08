<script src=" {!! asset('assets/pickadate/picker.js') !!}"></script>
<script src=" {!! asset('assets/pickadate/picker.date.js') !!}"></script>
<script src=" {!! asset('assets/pickadate/picker.time.js') !!}"></script>
<script src=" {!! asset('assets/selectize/selectize.min.js') !!}"></script>
<script>
    $(function() {
        $("#publish_date").my_pickadate();
        $("#publish_time").my_pickatime();
        $("#tags").selectize({
            create: true
        });
    });
</script>