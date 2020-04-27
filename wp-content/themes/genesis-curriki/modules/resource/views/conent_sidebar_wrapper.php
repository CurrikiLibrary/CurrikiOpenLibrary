<?php
function resource_conent_sidebar_wrapper() {    
    $res = new CurrikiResources();
    $resource = $res->getResourceById((int) $_GET['rid'], rtrim($_GET['pageurl'], '/'), true);    
        
    $widget_in_collections = !empty($resource['collections_resource_blogngs_to']) 
            ? widget_side_bar("In Collections", $resource['collections_resource_blogngs_to']) : "";
    $widget_you_may_like = widget_side_bar("You May Like");
    $widget_premium_resources = widget_side_bar("Featured");
    
//return <<<HTML
//<div id="resource-sidebar" class="resource-sidebar page-sidebar grid_2">    
//    <div id="container_you_may_like">$widget_you_may_like</div>
//    <div id="container_premium_resources">$widget_premium_resources</div>
//    <div id="container_in_collections">$widget_in_collections</div>
//</div>
//HTML;
return <<<HTML
<div id="resource-sidebar" class="resource-sidebar page-sidebar grid_2">    
    <div id="container_in_collections">$widget_in_collections</div>
</div>
HTML;

}