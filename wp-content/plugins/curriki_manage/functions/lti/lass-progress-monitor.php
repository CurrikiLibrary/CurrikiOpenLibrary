<?php

function laasAfterRegisterProgramModal($slug,$title){
    $oer_link = site_url('/oer/'.$slug);
    return '
    <span id="after-register-resource-popup-wrapper" style="display:none;">
        <div id="after-register-resource-popup" >
            <div class = "submit-card grid_8 card center" style="width: 600px;">
                <h4>You are Entrolled in "'.$title.'"</h4>  
                <div class = "my-library-actions grid_10" style="margin:0 auto">
                <a href="'.$oer_link.'" class = "resource-button small-button red-button" style="width: 220px;color: #FFFFFF;text-transform: capitalize;border-radius: 8px;">START</a>
                </div>              
            </div>
        </div>
        <a id="fancyBoxInlineAfterRegister" href="#after-register-resource-popup"></a>
    </span>
    ';
}

function laasGetPlaylistActivitiesProgressView($lti_resources = [], $user_id, $program_id, $playlist_id_filter){
    
    $view = '';
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    foreach($lti_resources as $lti_resource){
        
        $progress_lti_resource = laas_get_user_performance($user_id, $program_id, $playlist_id_filter, $lti_resource['resourceid']);        
        $progress = strip_tags($progress_lti_resource['progress']);
        if($progress == ACTIVITY_STATUS_NOT_COMPLETED){
            $activity_progress = ACTIVITY_STATUS_NOT_COMPLETED_PROGRESS_MONITOR;
            $activity_progress_class = 'danger';
        }else{
            $activity_progress = substr($progress,0,strlen($progress)-13);
            $activity_progress_class = 'success';
        }        

        $activity_link = site_url().'/oer/'.$lti_resource['pageurl'];
        $view .= '        
            <div class="media media-secondary">
                <div class="media-thumbnail">
                    <img src="' . $stylesheet_directory_uri . '/images/collection-page/icon_file.png" width="26" height="35" alt="icon">
                </div>
                <div class="media-body">
                    <h5 class="media-title">'.$lti_resource['title'].'</h5>
                    <div class="stat">
                        <span class="status status-'.$activity_progress_class.'">'.$activity_progress.'</span>
                    </div>
                </div>
            </div>
        ';        
    }

    return $view;

}

function laasGetProgramPlaylistsProgressView($playlists = [], $user_id = 0, $program_id = 0){
    global $wpdb;
    $view = '<div class="panel-group" id="accordion">';
    foreach($playlists as $index => $playlist){        
        $progress = laas_get_user_performance($user_id, $program_id, $playlist['resourceid']);
        $progress_completed = $progress['progress']['completed'];
        $progress_total = $progress['progress']['total'];
        $copmleted_percent = 0;
        if(intval($progress_total) > 0){
            $copmleted_percent = round((intval($progress_completed)/intval($progress_total))*100,0,PHP_ROUND_HALF_UP);
        }        
        
        
        $lt_rs = currGetPlaylistCollectionResources($playlist['resourceid']);
        $lti_resources = is_array($lt_rs) ? $lt_rs : [];
        $lti_resources_view = laasGetPlaylistActivitiesProgressView($lti_resources, $user_id, $program_id, $playlist['resourceid']);

        $folder_icon = $index == 0 ? "fa-folder-open" : "fa-folder";
        $link_playlist = site_url().'/oer/'.$playlist['pageurl'];
        $view .= ' 
                <div class="panel-header collapsed" data-toggle="collapse" data-target="#collapse'.$playlist['resourceid'].'" data-parent="#accordion">
                    <div class="folder-icon">
                        <i class="fa '.$folder_icon.'"></i>
                    </div>
                    <div class="panel-header-content">
                        <h5 class="media-title">'.$playlist['title'].'</h5>
                        <div class="progress-holder">
                            <div class="progress-box">
                                <div class="progress">
                                    <div class="progress-bar width-'.$copmleted_percent.'-percent"></div>
                                </div>
                                <div class="progress-percent">'.$copmleted_percent.'%</div>
                            </div>
                        </div>
                        &nbsp; &nbsp;
                        |
                        &nbsp; &nbsp;
                        <span class="status status-danger">Not Completed</span>
                    </div>
                    <div class="toggle-icon">
                        <i class="fa fa-minus"></i>
                    </div>
                </div>
                <div class="panel-body collapse" id="collapse'.$playlist['resourceid'].'">
                    '.$lti_resources_view.'
                </div>                    
        ';
    }
    $view .= '</div>';
    return $view;
}

function laasGetProgramProgressView($child_resources = [], $user_id, $program_id){
    global $wpdb;
    $progress = laas_get_user_performance($user_id, $program_id);
    $progress_completed = $progress['progress']['completed'];
    $progress_total = $progress['progress']['total'];
    $copmleted_percent = 0;
    if(intval($progress_total) > 0){
        $copmleted_percent = round((intval($progress_completed)/intval($progress_total))*100,0,PHP_ROUND_HALF_UP);
    }
    $row = $wpdb->get_row("select * from resources where resourceid = ".$program_id, ARRAY_A);    
    $view = '<div class="pb-10">
                <h3 class="section-title">'.$row['title'].'</h3>
            </div>';
    $view .= '<div class="infobox">
                <div class="infobox-column">
                    <div class="infobox-title">Playlists Completed</div>
                    <div class="infobox-data">'.$progress_completed."/".$progress_total.'</div>
                </div>
                <div class="infobox-column">
                    <div class="infobox-title">Progress in %</div>
                    <div class="progress-box">
                        <div class="progress">
                            <div class="progress-bar width-'.$copmleted_percent.'-percent"></div>
                        </div>
                        <div class="progress-percent">'.$copmleted_percent.'%</div>
                    </div>
                </div>
            </div>';
    $view .= laasGetProgramPlaylistsProgressView($child_resources, $user_id, $program_id);        
    return $view;
}
function laasGetPlaylistProgressView($lti_resources = [], $user_id, $program_id, $playlist_id_filter){
    global $wpdb;
    $progress = laas_get_user_performance($user_id, $program_id, $playlist_id_filter);
    $progress_completed = $progress['progress']['completed'];
    $progress_total = $progress['progress']['total'];
    $copmleted_percent = 0;
    if(intval($progress_total) > 0){
        $copmleted_percent = round((intval($progress_completed)/intval($progress_total))*100,0,PHP_ROUND_HALF_UP);
    }
    $row = $wpdb->get_row("select * from resources where resourceid = ".$playlist_id_filter, ARRAY_A);
    $on_click_parent = 'laasLoadProgressMonitor("program"'.$program_id.')';    
    $view = '<div class="pb-10">
                <h3 class="section-title">'.$row['title'].'</h3>
            </div>';
    $view .= '<div class="infobox">
                <div class="infobox-column">
                    <div class="infobox-title">Activities Completed</div>
                    <div class="infobox-data">'.$progress_completed."/".$progress_total.'</div>
                </div>
                <div class="infobox-column">
                    <div class="infobox-title">Progress in %</div>
                    <div class="progress-box">
                        <div class="progress">
                            <div class="progress-bar width-'.$copmleted_percent.'-percent"></div>
                        </div>
                        <div class="progress-percent">'.$copmleted_percent.'%</div>
                    </div>
                </div>
            </div>';

    $view .= laasGetPlaylistActivitiesProgressView($lti_resources, $user_id, $program_id, $playlist_id_filter);
    return $view;
}

function laasGetProgressMonitorView($progress_monitor_for, $child_resources = [], $user_id = 0, $program_id = 0, $playlist_id_filter = 0, $activity_id_filter = 0){
    $progress_monitor_view = '';
    switch($progress_monitor_for){
        case 'playlist':
            $progress_monitor_view = laasGetPlaylistProgressView($child_resources, $user_id, $program_id, $playlist_id_filter);
            break;
        case 'program':
            $progress_monitor_view = laasGetProgramProgressView($child_resources, $user_id, $program_id);
            break;
        default:
            $progress_monitor_view = '';
    }
    return $progress_monitor_view;
}

function laasProgressMonitorModal($slug, $progress_monitor_for, $child_resources = [], $user_id = 0, $program_id = 0, $playlist_id_filter = 0, $activity_id_filter = 0){
    $oer_link = site_url('/oer/'.$slug); 
    $progress_monitor_view = laasGetProgressMonitorView($progress_monitor_for, $child_resources, $user_id, $program_id, $playlist_id_filter, $activity_id_filter);   
    $view = '
    <div class="modal modal-secondary fade" id="modal-progress-2" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-wrap">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h4 class="modal-title">My Progress</h4>
					</div>
					<div class="modal-body">
						<div class="section-content">
                            '.$progress_monitor_view.'
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';

    return $view;
}

add_action('wp_ajax_nopriv_load_progress_monitor', 'ajax_load_progress_monitor');
add_action('wp_ajax_load_progress_monitor', 'ajax_load_progress_monitor');

function ajax_load_progress_monitor() {

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    global $curriki_lti_instance;      
    
}