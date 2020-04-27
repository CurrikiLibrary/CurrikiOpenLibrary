<?php
/**
 * Display the Main tab
 * @return void
 */
function cmp_resource_page() {
?>

<h2 class="text-center text-uppercase"><?php _e( 'Resource Deactivation', CMP_CORE_TEXT_DOMAIN ); ?></h2>

<div class="resource_deactivation_container" ng-app="ResourceActivation">
    <form ng-controller="ResourceController">
        <div class="small-12 columns padding-0">
            <div class="small-12 columns">
                <h2><label for="resource_pageurl" style="font-weight: bold;"><?php _e( 'Resource Page SLUG', CMP_CORE_TEXT_DOMAIN ); ?></label></h2>
            </div>
        </div>
        <div class="small-12 columns padding-0">
            <div class="small-4 columns">
                <input type="text" placeholder="<?php _e( 'Please Enter Resource Page SLUG', CMP_CORE_TEXT_DOMAIN ); ?>" class=" float-left" ng-model="data.pageurl" id="resource_pageurl" />
                <input type="hidden" ng-model="data.ajaxurl" ng-init="data.ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'" />
                <input type="hidden" ng-model="data.security" ng-init="data.security = '<?php echo wp_create_nonce("resource_deactivation"); ?>'" />
                <input type="hidden" ng-model="data.baseurl" ng-init="data.baseurl = '<?php echo get_site_url(); ?>'" />
                
            </div>
            <div class="small-1 columns">
                <a href="#" class="button postfix  float-left" ng-click="FindActivation(data, $event)"><img src="<?php echo CMP_URL. '/images/loading.gif'; ?>" style="width:20px;" ng-hide="HideLoading" /><span ng-show="HideLoading"><?php _e( 'Go', CMP_CORE_TEXT_DOMAIN ); ?></span></a>
            </div>
            <div class="small-7 columns" ng-hide="HideDeleteBulkButton">
                <a href="#" class="button postfix  float-left" ng-click="DeleteBulkActivation(data, $event)"><img src="<?php echo CMP_URL. '/images/loading.gif'; ?>" style="width:20px;" ng-hide="HideDeleteBulkLoading" /><span ng-show="HideDeleteBulkLoading"><?php _e( 'Delete Bulk', CMP_CORE_TEXT_DOMAIN ); ?></span></a>
            </div>
        </div>
<!--        <div class="small-12 columns padding-0">
            
        </div>-->
        <div class="small-12 columns padding-0" ng-show="resource_updated">
            <div class="alert alert-{{resource_class}} is-dismissible">
                <p>{{resource_message}}</p>
            </div>
        </div>
        <table role="grid" class="hover">
            <thead>
                <tr>
                    <th width="400"><?php _e( 'Resource Id', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th width="400"><?php _e( 'Title', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th width="200"><?php _e( 'Contribution Date', CMP_CORE_TEXT_DOMAIN ); ?></th>
                    <th><?php _e( 'Action', CMP_CORE_TEXT_DOMAIN ); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="resource.resourceid">
                    <td ng-bind="resource.resourceid"></td>
                    <td ng-bind="resource.title"></td>
                    <td ng-bind="resource.contributiondate"></td>
                    <td ng-if="resource_active == 'T'"><a href="#" class="button postfix" ng-click="ActiveInactiveComment(resource, data, 'inactive', $event)"><?php _e( 'Deactivate', CMP_CORE_TEXT_DOMAIN ); ?><img src="<?php echo CMP_URL. '/images/loading.gif'; ?>" style="width:20px;" ng-show="loadingActiveInactiveComment === resource" /></a></td>
                    <td ng-if="resource_active == 'F'"><?php _e( 'Resource Inactive', CMP_CORE_TEXT_DOMAIN ); ?></td>
                </tr>
                <tr ng-show="!resource.resourceid">
                    <td colspan="6" class="text-center"><?php _e( 'No Resource', CMP_CORE_TEXT_DOMAIN ); ?></td>
                </tr>
            </tbody>
        </table>

    </form>
</div>
	<?php
}
