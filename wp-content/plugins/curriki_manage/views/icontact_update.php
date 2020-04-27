<h2>I-Contact Updates</h2>
<?php
if (count($data['size'])) {
    echo '<p>Currently there is <strong>' . $data['size'] . '</strong> Update pending (50 at a time)!</p>';
    ?>
    <form id="run-update">
        <button class="button-primary start"><img src="<?php echo plugins_url('/../images/loading.gif', __FILE__); ?>" class="hidden" style="width:19px;" />
            <span style="display: inline-block;position: relative;left: 4px;">
                Run Update
            </span>
        </button>
        <input type="hidden" id="admin_url" name="admin_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
    </form>
    <div id="icontact-result"></div>
    <script>
        var counter = 0;
        var $time = 0;
        var record_count = 0;
        var admin_url = jQuery('#admin_url').val();
        var timeoutId = null;
        jQuery(document).ready(function () {

            jQuery('#run-update').submit(function (e) {
                e.preventDefault();

                if (jQuery('#run-update button').hasClass('start')) {
                    counter = 0;
                    $time = 0;
                    record_count = 0;
                    jQuery('#icontact-result').empty();
                    doPoll();
                    jQuery('#run-update button').removeClass('start');
                    jQuery('#run-update button').addClass('stop');
                    jQuery('#run-update button span').text('Stop Update');
                    jQuery('#run-update button img').removeClass('hidden');
                } else if (jQuery('#run-update button').hasClass('stop')) {
                    jQuery('#run-update button').removeClass('stop');
                    jQuery('#run-update button').addClass('start');
                    jQuery('#run-update button span').text('Stopping...');
                    jQuery('#run-update button img').addClass('hidden');
                    counter = 100;
                    clearTimeout(timeoutId);
                    console.log(timeoutId);

                }
            });
        });

        function doPoll() {

            if (counter < 10) {
                counter++
                jQuery.ajax({
                    type: "POST",
                    url: admin_url,
                    data: {action: 'icontactajax_update', 'run_update': true},
                    success: function (result) {
                        jQuery('#icontact-result').prepend(result.msg);
                        $time += result.time;
                        record_count +=5;
                        timeoutId = setTimeout(doPoll, 0);
//                        jQuery('#run-update button').removeClass('stop');
//                        jQuery('#run-update button').addClass('start');
//                        jQuery('#run-update button span').text('Run Update');
//                        jQuery('#run-update button img').addClass('hidden');
                        console.log(result);
                    },
                    dataType: 'json'
                });
            }
            else {
                
                jQuery('#icontact-result').append("<br />Records count = "+record_count+" <br />(Total Time: "+($time/1000.00)+" seconds)<br />");
                jQuery('#run-update button').removeClass('stop');
                jQuery('#run-update button').addClass('start');
                jQuery('#run-update button span').text('Run Update');
                jQuery('#run-update button img').addClass('hidden');
            }

        }
    </script>
    <?php
} else {
    echo '<p>Currently there is no Update pending !</p>';
}
?>
