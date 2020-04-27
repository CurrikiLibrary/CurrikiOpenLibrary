<?php 

get_header();

global $curriki;
$oer = $curriki->container->get('oer');
?>


    <?php        
        var_dump($oer->resource);
    ?>

<?php get_footer(); ?>