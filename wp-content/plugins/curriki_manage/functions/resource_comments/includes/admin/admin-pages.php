<?php
/**
 * Display the Main tab
 * @return void
 */
function cmp_admin_page() {
?>

<h2 class="text-center text-uppercase"><?php _e( 'Resource Comments', CMP_CORE_TEXT_DOMAIN ); ?></h2>

<div class="resource_comments_container" ng-app="ResourceComments">
    <form ng-controller="CommentsController">
        <div class="small-12 columns padding-0">
            <div class="small-12 columns">
                <h2><label for="resource_pageurl" style="font-weight: bold;"><?php _e( 'Resource Page SLUG', CMP_CORE_TEXT_DOMAIN ); ?></label></h2>
            </div>
        </div>
        <div class="small-12 columns padding-0">
            <div class="small-4 columns">
                <input type="text" placeholder="<?php _e( 'Please Enter Resource Page SLUG', CMP_CORE_TEXT_DOMAIN ); ?>" class=" float-left" ng-model="data.pageurl" id="resource_pageurl" />
                <input type="hidden" ng-model="data.ajaxurl" ng-init="data.ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'" />
                <input type="hidden" ng-model="data.security" ng-init="data.security = '<?php echo wp_create_nonce("resource_comments"); ?>'" />
                <input type="hidden" ng-model="data.baseurl" ng-init="data.baseurl = '<?php echo get_site_url(); ?>'" />
                
            </div>
            <div class="small-1 columns">
                <a href="#" class="button postfix  float-left" ng-click="FindComments(data, $event)"><img src="<?php echo CMP_URL. '/images/loading.gif'; ?>" style="width:20px;" ng-hide="HideLoading" /><span ng-show="HideLoading"><?php _e( 'Go', CMP_CORE_TEXT_DOMAIN ); ?></span></a>
            </div>
            <div class="small-7 columns" ng-hide="HideDeleteBulkButton">
                <a href="#" class="button postfix  float-left" ng-click="DeleteBulkComments(data, $event)"><img src="<?php echo CMP_URL. '/images/loading.gif'; ?>" style="width:20px;" ng-hide="HideDeleteBulkLoading" /><span ng-show="HideDeleteBulkLoading"><?php _e( 'Delete Bulk', CMP_CORE_TEXT_DOMAIN ); ?></span></a>
            </div>
        </div>
<!--        <div class="small-12 columns padding-0">
            
        </div>-->
        <div class="small-12 columns padding-0" ng-show="comment_deleted">
            <div class="alert alert-{{delete_class}} is-dismissible">
                <p>{{delete_msg}}</p>
            </div>
        </div>
        <div class="small-12 columns padding-0" ng-show="comment_errors">
            <div class="alert alert-{{comment_class}} is-dismissible">
                <p>{{comment_msg}}</p>
            </div>
        </div>
        <table role="grid" class="hover">
            <thead>
                <tr>
                    <th width="50"><?php _e( 'Sr#', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th width="400"><?php _e( 'Comment', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th width="100"><?php _e( 'Userid', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th width="100"><?php _e( 'Rating', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th width="200"><?php _e( 'Date', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th><?php _e( 'Action', CMP_CORE_TEXT_DOMAIN ); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="r in filteredRes" id="country-{{$index}}">
                    <td>
                        <!--<input type="checkbox"   ng-model="selected[$index][r.resourceid][r.userid][r.commentdate]" />-->
<!--                        <input type="checkbox" ng-model="r.resourceid" />
                        <input type="checkbox" ng-model="r.userid" />
                        <input type="checkbox" ng-model="r.commentdate" />-->
                        
                        <!--<input type="checkbox" data-commentid="" ng-model="input" ng-change="r.resourceid=input;r.userid=input;r.commentdate=input; " />-->
                        <input type="checkbox" ng-model="r.checked" ng-change="checkSelected()" />
                    </td>
                    <td ng-bind="r.comment"></td>
                    <td ng-bind="r.userid"></td>
                    <td ng-bind="r.rating"></td>
                    <td ng-bind="r.commentdate"></td>
                    <td><a href="#" class="button postfix" ng-click="DeleteComment(r, data, $index, $event)"><?php _e( 'Delete', CMP_CORE_TEXT_DOMAIN ); ?><img src="<?php echo CMP_URL. '/images/loading.gif'; ?>" style="width:20px;" ng-show="loadingDeleteComment === r" /></a></td>
                </tr>
                <tr ng-show="!res.comments.length">
                    <td colspan="6" class="text-center"><?php _e( 'No Resource Comments', CMP_CORE_TEXT_DOMAIN ); ?></td>
                </tr>
            </tbody>
        </table>

        <div data-pagination="" data-num-pages="numPages()" 
             data-current-page="currentPage" data-max-size="maxSize"  
             data-boundary-links="true" ng-hide="HidePagination"></div>
    </form>
</div>
	<?php
}
