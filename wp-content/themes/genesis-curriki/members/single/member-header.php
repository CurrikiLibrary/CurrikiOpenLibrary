<?php do_action( 'bp_before_member_header' ); ?>

<?php // echo curriki_member_header(); ?>

<?php // echo curriki_member_page_body(); ?>

<?php
remove_action( 'bp_member_header_actions', 'bp_send_public_message_button' );
//remove_action( 'bp_member_header_actions' );

        $current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
    
	$userid = bp_displayed_user_id();
        //$userid = 10000;  
	$full_name = bp_get_displayed_user_fullname();
	$member_url = bp_displayed_user_domain();
        global $wpdb,$bp;
        $q_userinfo = "select * from users
                        left join cur_users on cur_users.ID = users.userid
                       where users.userid = '".$userid."'";
        
        $userinfo = $wpdb->get_row($q_userinfo);
        
	//$city = $userinfo->city;//cur_get_user_nonwp_data ( $userid, 'city' );
	//$state = $userinfo->state;//cur_get_user_nonwp_data ( $userid, 'state' );
	//$country = $userinfo->country;//cur_get_user_nonwp_data ( $userid, 'country' );
        
        $location = ucwords($userinfo->city);
	if ( $location != '' ) $location .= ', ';
        $location .= ucwords($userinfo->state);
	if ( $location != '' ) $location .= ', ';
        $location .= strtoupper($userinfo->country);
        
	//$bio = cur_get_user_nonwp_data ( $userid, 'bio' );
        $bio = $userinfo->bio;
	$profession = false;

//	$link = 'http://BAKEDevMathFC.members.curriki.org';
        $link = get_bloginfo('url').'/members/'.$userinfo->user_login;
        //$link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        //$link = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        
	$last_activity = bp_get_user_last_activity( $userid );
	$subjects = cur_get_user_subjectareas ( $userid , $current_language );
        
	$joined = $userinfo->registerdate;
        
	$organization = cur_get_user_nonwp_data ( $userid, 'organization' );
	$website = cur_get_user_nonwp_data ( $userid, 'blogs' );
	
        $facebookurl = cur_get_user_nonwp_data ( $userid, 'facebookurl' );
        $twitterurl = cur_get_user_nonwp_data ( $userid, 'twitterurl' );
        $showemail = cur_get_user_nonwp_data ( $userid, 'showemail' );
        $email = $wpdb->get_var("SELECT user_email FROM cur_users where ID='".$userid."'"); 
        
	$languages = cur_get_user_lang ( $userid );
        
        $education_levels = get_member_education_levels($userid,$current_language);
	
?>

<div class="member-header page-header">
	<div class="wrap container_12">
		<div class="member-join page-join grid_2">
			<!-- <img class="circle aligncenter" src="placehold.it/100x100" /> -->
			<a href="<?php bp_displayed_user_link(); ?>">
				<?php //bp_displayed_user_avatar( 'type=full' ); 
                                if(empty($userinfo->uniqueavatarfile)){
                                    echo '<img width="150" height="150" alt="Profile picture of '.$full_name.'" class="circle aligncenter user-'.$userid.'-avatar avatar-150 photo" src="'.get_stylesheet_directory_uri().'/images/user-icon-sample.png">';
//                                    echo '<img class="border-grey" src="' . get_stylesheet_directory_uri() . '/images/user-icon-sample.png" alt="member-name" />';
                                }else{
                                    echo '<img width="150" height="150" alt="Profile picture of '.$full_name.'" class="circle aligncenter user-'.$userinfo->uniqueavatarfile.'-avatar avatar-'.$userinfo->uniqueavatarfile.' photo" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/'.$userinfo->uniqueavatarfile.'">';
                                    //echo '<img class="border-grey" src="https://archivecurrikicdn.s3-us-west-2.amazonaws.com/avatars/'.$userinfo->uniqueavatarfile.'" alt="member-name" />';
                                }
                                ?>
			</a>
			<!-- <button class="green-button">Follow</button> -->
			<div id="item-buttons">
				<?php do_action( 'bp_member_header_actions' ); ?>
			</div><!-- #item-buttons -->
		</div>
		<div class="member-info page-info grid_10">
			<h3 class="member-title page-title"><a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_fullname(); ?></a></h3>
			<div class="member-profile"><?php if ( $profession ) { ?> <?php } ?><?php if ( $location && $profession ) { ?>-<?php } ?><?php if ( $location ) { echo $location; } ?></div>
			<div class="member-link page-link"><a href="<?php echo $link; ?>"><?php echo $link; ?></a></div>
			<div id="member-info-accordion">
				<h4 class="member-more-info fa"> <?php echo __('More Information','curriki'); ?></h4>
				<div>
					<?php echo $bio; ?>

					<ul class="info">
						
						<div class="grid_3">
							<li><?php echo __('Subjects of Interest','curriki'); ?>:
								<?php if ( $subjects ) { ?>
								<ul>
									<?php foreach ($subjects as $key => $value) { ?>
									<li><?php echo ($value->item); ?></li>
									<?php } ?>
								</ul>
								<?php } ?>
							</li>
						</div>
                                            
                                                <div class="grid_3">
							<li><?php echo __('Education Levels','curriki'); ?>:
                                                            <ul id="educationlevel_list_container">
                                                             <?php if($education_levels != null) {?>
                                                                <?php foreach($education_levels as $education_level) { ?>
                                                                    <li><?php echo $education_level; ?></li>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </ul>
                                                        </li>
						</div>  
						
						<div class="grid_3">
							<li> <?php echo __('Organization','curriki'); ?>:							
							<ul><li><?php echo $organization ? $organization : '--'; ?></li></ul>							
							</li>
                                                        <li><span> <?php echo __('Website/Blogs','curriki'); ?></span>:								
								<ul><li><?php echo $website ? $website : '--'; ?></li></ul>								
							</li>
                                                        <?php if($showemail == "Always" || ($showemail == "Members" && is_user_logged_in()) ){ ?>
                                                            <li> <?php echo __('Email','curriki'); ?>:								
                                                                    <ul><li><?php echo ($email) ? $email : "--"; ?></li></ul>								
                                                            </li>
                                                        <?php } ?>
							<li> <?php echo __('Facebook','curriki'); ?>:								
								<ul><li><?php echo ($facebookurl) ? $facebookurl : "--"; ?></li></ul>								
							</li>
							<li> <?php echo __('Twitter','curriki'); ?>:								
								<ul><li><?php echo ($twitterurl) ? $twitterurl : "--"; ?></li></ul>								
							</li>
						</div>
						<div class="grid_3">
								<li> <?php echo __('Language','curriki'); ?>:
								<?php if ( $languages ) { ?>
								<ul>
									<?php                                                                              
                                                                             foreach ($languages as $language) { 
                                                                        ?>
									<li>
                                                                            <?php                                                                                 
                                                                                $q_languages_single = cur_languages_query($current_language,$language->language);
                                                                                $language_single = $wpdb->get_row($q_languages_single);                                                                                
                                                                                echo $language_single->displayname;
                                                                            ?>
                                                                        </li>
									<?php } ?>
								</ul>
								<?php } ?>
								</li>
								<!-- <li>Member Policy:<ul><li>Mathematics</li><li>Science</li></ul></li> -->
						</div>
						<div class="grid_3">
							<li><?php echo __('Joined','curriki'); ?>:<ul><li><?php echo date('M d, Y g:i a', strtotime($joined)); ?></li></ul></li>
							<li> <?php echo __('Last Activity','curriki'); ?>:
                                                            <ul>
                                                                <li>
                                                                    <?php                                                                             
                                                                        $q_lastlogin = "select max(logindate) from logins where userid = " . $bp->displayed_user->id . " and sitename = 'curriki'";
                                                                        $last_login = $wpdb->get_var($q_lastlogin);
                                                                        echo isset($last_login) && $last_login != '' ? date('M d, Y g:i a', strtotime($last_login)) : "--";                                                                             
                                                                    ?>
                                                                </li>
                                                            </ul>
                                                        </li>
						</div>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
     
    jQuery( document ).ready(function() {
    
       <?php
       if($current_language === "eng")
       {
       ?>
            jQuery("#lang_sel_list ul li.icl-en a").removeAttr("href");
            jQuery("#lang_sel_list ul li.icl-es a").removeAttr("href");                
            jQuery("#lang_sel_list ul li.icl-en a").attr("href","<?php echo $link ?>");

            <?php
                $link_arr = explode("/", $link);                            
                $lang_code = array("es");
                array_splice( $link_arr, 3, 0, $lang_code );            
                $link = implode("/", $link_arr);            
            ?>
            jQuery("#lang_sel_list ul li.icl-es a").attr("href","<?php echo $link ?>");
       <?php
       }  else {           
       ?>
               <?php
                    $$link_main = $link;
                    $link_arr = explode("/", $link);                            
                    $lang_code = array("en");
                    array_splice( $link_arr, 3, 0, $lang_code );
                  
                    unset($link_arr[4]);
                    $link_arr = array_values($link_arr);
                    
                    $link = implode("/", $link_arr);            
                ?>
                jQuery("#lang_sel_list ul li.icl-en a").attr("href","<?php echo $link ?>");

                <?php
                    
                    $link_arr = explode("/", $link);                            
                    unset($link_arr[3]);
                    $link_arr = array_values($link_arr);
                    $lang_code = array("es");
                    array_splice( $link_arr, 3, 0, $lang_code );            
                    
                    //unset($link_arr[4]);
                    //$link_arr = array_values($link_arr);
                    
                    $link = implode("/", $link_arr);            
                ?>
                jQuery("#lang_sel_list ul li.icl-es a").attr("href","<?php echo $link ?>");

       <?php
       }
       ?>
        
    });
    
</script>
 
<?php /* <div id="item-header-avatar">
	<a href="<?php bp_displayed_user_link(); ?>">
		<?php bp_displayed_user_avatar( 'type=full' ); ?>
	</a>
</div><!-- #item-header-avatar -->
<div id="item-header-content">
	<h2><a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_fullname(); ?></a></h2>

	<span class="user-nicename">@<?php bp_displayed_user_username(); ?></span>
	<span class="activity"><?php bp_last_activity( $userid ); ?></span>

	<?php do_action( 'bp_before_member_header_meta' ); ?>

	<div id="item-meta">
		<?php if ( bp_is_active( 'activity' ) ) : ?>
			<div id="latest-update">
				<?php bp_activity_latest_update( $userid ); ?>
			</div>
		<?php endif; ?>

		<div id="item-buttons">
			<?php do_action( 'bp_member_header_actions' ); ?>
		</div><!-- #item-buttons -->

		<?php do_action( 'bp_profile_header_meta' ); ?>
	</div><!-- #item-meta -->
</div><!-- #item-header-content --> */ ?>

<?php 

do_action( 'bp_after_member_header' );
do_action( 'template_notices' );