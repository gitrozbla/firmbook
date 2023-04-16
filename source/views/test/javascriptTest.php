<input type="checkbox" class="test" name="test" />

<link rel="stylesheet" type="text/css" href="js/vendor/bootstrap-switch/css/bootstrap2/bootstrap-switch.min.css" />
<script type="text/javascript" src="js/vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
    jQuery(function($){
        $('.test').bootstrapSwitch();
        $('.test').on('switchChange.bootstrapSwitch', function (e, data) {
            alert(data.value);
        });
    });
</script>