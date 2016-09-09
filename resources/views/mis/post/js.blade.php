<script src=" {!! asset('assets/pickadate/picker.js') !!}"></script>
<script src=" {!! asset('assets/pickadate/picker.date.js') !!}"></script>
<script src=" {!! asset('assets/pickadate/picker.time.js') !!}"></script>
<script src=" {!! asset('assets/selectize/selectize.min.js') !!}"></script>
<script>
    $(function() {
        $("#publish_date").pickadate({
            format: "Y-m-d"
        });
        $("#publish_time").pickatime({
            format: "H:i"
        });
        $("#tags").selectize({
            create: true
        });
    });
</script>