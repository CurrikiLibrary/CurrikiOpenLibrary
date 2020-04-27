<?php

$pre_req = '<script id="jw_player_script" type="text/javascript" src="js/tinymce/plugins/fileuploader/jwplayer/jwplayer.js"></script>
            <script type="text/javascript">jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>';

if (isset($_REQUEST['area']))
    file_put_contents('raw.html', $pre_req.$_REQUEST['area']);
header('Location: raw.html');

?>