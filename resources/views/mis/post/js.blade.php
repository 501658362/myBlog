<script src=" {!! asset('assets/pickadate/picker.js') !!}"></script>
<script src=" {!! asset('assets/pickadate/picker.date.js') !!}"></script>
<script src=" {!! asset('assets/pickadate/picker.time.js') !!}"></script>
<script src=" {!! asset('assets/selectize/selectize.min.js') !!}"></script>
<script>
    $(function() {
        $("#publish_date").pickadate({
            format: "mmm-d-yyyy"
        });
        $("#publish_time").pickatime({
            format: "h:i A"
        });
        $("#tags").selectize({
            create: true
        });
    });
</script>