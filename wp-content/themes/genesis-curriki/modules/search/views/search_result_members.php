<?php global $search; ?>
<div id="members-dir-list" class="members dir-list">
    <ul id="members-list" class="item-list" role="main">
        <?php foreach ($search->response as $row) { ?>
            <li>
                <div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow">
                    <div class="card-header">
                        <a href="javascript:void(0);">
                            <?php
                            if ($row['avatarfile'])
                            {
                                echo '<img width="100" height="100" alt="' . $row['fullname'] . '" class="border-grey user-123653-avatar avatar-100 photo" src="https://' . DBI_AWS_S3_CDN_URL . '//avatars/' . $row['avatarfile'] . '">';
                            }else{ 
                                $file_img = get_stylesheet_directory_uri()."/images/user-icon-sample.png";
                                echo '<img width="100" height="100" alt="' . $row['fullname'] . '" class="border-grey user-123653-avatar avatar-100 photo" src="'.$file_img.'">';
                            }                                
                            ?>
                        </a>
                        <span class="member-name name"><a href="javascript:void(0);"><?php echo $row['fullname']; ?></a></span>
                    </div>
                    <div class="card-stats">
                        <span class="stat"><span class="fa fa-users"></span><?php echo $row['members_groups_count']; ?></span>
                        <span class="stat"><span class="fa fa-user"></span><?php echo $row['members_followers_count']; ?></span>
                        <span class="stat"><span class="fa fa-comments"></span><?php echo $row['members_topics_count']; ?></span>
                        <span class="stat"><span class="fa fa-book"></span><?php echo $row['members_resources_count']; ?></span>
                    </div>
                    <div class="card-button action"></div>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>