<?php global $search; ?>
<div class="groups grid_12" style="padding: 0px;margin:0px;float: none;">
    <?php foreach ($search->response as $row) { ?>
        <div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group">
            <div class="card-header">
                <div><a href="<?php echo get_bloginfo('url') . '/' . $row['url']; ?>/"><img width="100" height="100" title="<?php echo $row['title']; ?>" alt="Group logo of <?php echo $row['title']; ?>" class="circle aligncenter group-2344-avatar avatar-100 photo" src="<?php echo $row['image']; ?>"></a></div>
                <span class="group-name name"><a href="<?php echo get_bloginfo('url') . '/' . $row['url']; ?>/"><?php echo $row['title']; ?></a></span>
                <br>
            </div>
            <div class="card-stats">
                <span class="stat"><span class="fa fa-users"></span><?php echo $row['groups_users_count']; ?></span>
                <?php if ($row['forum_id']) { ?>
                    <span class="stat"><span class="fa fa-comments"></span><?php echo $row['groups_comments_count']; ?></span>
                <?php } ?>
                <span class="stat"><span class="fa fa-book"></span><?php echo $row['groups_resources_count']; ?></span>
            </div>
            <div class="card-description"><p><?php echo $row['description']; ?></p></div>

            <div class="card-button action">				

                &nbsp;
            </div>
        </div>
    <?php } ?>
</div>