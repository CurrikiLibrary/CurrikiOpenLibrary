<?php
require_once dirname(__FILE__) . '/../functions.php';

echo json_encode(convertSdfFile($_GET));

?>