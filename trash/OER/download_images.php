<?php
$css = file_get_contents('assets/02c05097cb8c.css');
$parts = explode("url('",$css);


$url = 'https://staging.oercommons.org/';
for($i=1;$i<count($parts);$i++){
    $parts_2 = explode("')",$parts[$i]);
    $img = file_get_contents($url.$parts_2[0]);
    $file_name = 'images/'.end(explode('/', $parts_2[0]));
    file_put_contents($file_name,$img);
    $css = str_replace($parts_2[0], $file_name, $css);
    echo $parts_2[0];
    echo '<br/>';
}

file_put_contents('assets/02c05097cb8c-2.css',$css);
?>
