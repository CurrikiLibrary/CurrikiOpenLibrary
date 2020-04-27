<?php
if (!function_exists('init_form_application')) {
    function init_form_application()
    {
        include_once plugin_dir_path( __FILE__ ) . '/../widget.php';
        register_widget('FormApplicationWidget');  
    }
}

function header_menu($params) 
{
    $params['descr_landing_page'] = 'Note landing-page';
    return $params;
}
function activate_plugin_application()
{
    landing_page_wb::activate_plugin();
}
function deactivate_plugin_application()
{
    landing_page_wb::deactivate_plugin();
}

function post_form_check($obj) {
    landing_page_wb::setPost($obj);
} 
function checkCategory($categories, $cat, $returned = false)
{
    if( !empty($categories) ){
        $n = count($categories);
        $ret = array();
        for($i = 0; $i < $n; $i++) {
            if (is_array($cat)) {
                if (in_array($categories[$i]->slug, $cat)) {
                    if ($returned === false) {
                        return true;
                    }
                }
            } else {
                if ($categories[$i]->slug == $cat) {
                    if ($returned === false) {
                        return true;
                    }

                }
            }
            if ($returned) {
                $ret[] = $categories[$i]->slug;
            }
        }
    }
    if ($returned) {
        return $ret;
    }
    return false;
}