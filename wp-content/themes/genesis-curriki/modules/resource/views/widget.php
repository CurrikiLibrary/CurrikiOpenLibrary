<?php
function widget_side_bar($title = null,$resources = []){
    
    $spinner = site_url() . "/wp-content/themes/genesis-curriki/images/loader.gif";    
    $widget_title = __($title,'curriki');
    $widget_body = '<img src="'.$spinner.'}" />';
    
    if(!empty($resources)){
        $widget_body = widget_prepare_body($resources);
    }
    
    return <<<HTML
    <div class="toc toc-card card rounded-borders-full border-grey no-min-width">
        <div class="toc-header blue-bg">{$widget_title}</div>
        <div class="toc-body">              
            $widget_body                
        </div>
    </div>
HTML;
                
}

function widget_prepare_body($resources){
    foreach($resources as $resource){
            $url = site_url() . "/oer/".$resource->pageurl;
            $resources_li .= "<li class=\"toc-file toc-image\"><span class=\"fa fa-li fa-file-o\"></span> <a href=\"{$url}\">{$resource->title}</a></li>";
    }
    $widget_body = '<ul class="fa fa-ul toc-collection toc-folder">'.$resources_li.'</ul>';
    return $widget_body;
}