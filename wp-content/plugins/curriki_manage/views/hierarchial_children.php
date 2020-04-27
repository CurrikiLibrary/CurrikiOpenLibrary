<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" integrity="sha256-xI/qyl9vpwWFOXz7+x/9WkG5j/SVnSw21viy8fWwbeE=" crossorigin="anonymous"></script>
<script src="<?php echo plugins_url('/../assets/bower_components/angular/angular.min.js', __FILE__); ?>"></script>
<script src="<?php echo plugins_url('/../assets/foundation/js/vendor/foundation.min.js', __FILE__); ?>"></script>
<script src="<?php echo plugins_url('/../js/scripts.js', __FILE__); ?>"></script>
<script data-require="angular-ui-bootstrap@0.3.0" data-semver="0.3.0" src="<?php echo plugins_url('/../assets/ui-bootstrap-tpls-0.3.0.min.js', __FILE__); ?>"></script>

<link rel="stylesheet" href="<?php echo plugins_url('/../assets/foundation/css/foundation.min.css', __FILE__); ?>" />
<link rel="stylesheet" href="<?php echo plugins_url('/../css/styles.css', __FILE__); ?>" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo plugins_url('/../assets/datepicker.css', __FILE__); ?>" />


<h2 class="text-center text-uppercase">Hierarchial Children</h2>

<div class="hierarchial_children_container" ng-app="HierarchialChildren">
    <form ng-controller="ChildrenController">
        <div class="small-12 columns padding-0">
            <div class="small-4 columns">
                <input type="text" placeholder="Please Enter Collection Page URL" class=" float-left" ng-model="data.pageurl" />
                <input type="hidden" ng-model="data.ajaxurl" ng-init="data.ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'" />
                <input type="hidden" ng-model="data.security" ng-init="data.security = '<?php echo wp_create_nonce("my-special-string"); ?>'" />
                <input type="hidden" ng-model="data.baseurl" ng-init="data.baseurl = '<?php echo get_site_url(); ?>'" />
                <input type="text" name="startdate" id="startdate" placeholder="Start Date" readonly="readonly" style="cursor: pointer;" title="Start Date" />
                <input type="text" name="enddate" id="enddate" placeholder="End Date" readonly="readonly" style="cursor: pointer;" title="End Date" />

                <script>
                            jQuery('#startdate, #enddate').datepicker({
                                dateFormat: 'yy-mm-dd',
                                changeMonth: true,
                                changeYear: true,
                                showOn: 'focus',
                                showButtonPanel: true,
                                closeText: 'Clear', // Text to show for "close" button
                                onClose: function () {
                                    var event = arguments.callee.caller.caller.arguments[0];
                                    // If "Clear" gets clicked, then really clear it
                                    if (jQuery(event.delegateTarget).hasClass('ui-datepicker-close')) {
                                        jQuery(this).val('');
                                    }
                                }
                            });
                            jQuery.datepicker._gotoToday = function (id) {
                                var target = jQuery(id);
                                var inst = this._getInst(target[0]);
                                if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
                                    inst.selectedDay = inst.currentDay;
                                    inst.drawMonth = inst.selectedMonth = inst.currentMonth;
                                    inst.drawYear = inst.selectedYear = inst.currentYear;
                                }
                                else {
                                    var date = new Date();
                                    inst.selectedDay = date.getDate();
                                    inst.drawMonth = inst.selectedMonth = date.getMonth();
                                    inst.drawYear = inst.selectedYear = date.getFullYear();
                                    // the below two lines are new
                                    this._setDateDatepicker(target, date);
                                    this._selectDate(id, this._getDateDatepicker(target));
                                }
                                this._notifyChange(inst);
                                this._adjustDate(target);
                            }

                </script>
            </div>
            <div class="small-8 columns">
                <a href="#" class="button postfix  float-left" ng-click="FindChildren(data, $event)"><img src="<?php echo plugins_url('/../images/loading.gif', __FILE__); ?>" style="width:20px;" ng-hide="HideLoading" /><span ng-show="HideLoading">Go</span></a>
                <?php if(isset($_REQUEST['testx'])){
                    ?>
                <a href="#" class="button postfix right" ng-click="ExportToExcelChildrenNLeafNodes(data, $event)">Export Children</a>
                <?php
                } ?>
                <a href="#" class="button postfix right" ng-click="ExportToExcel(data, $event)" ng-hide="HideExportButton">Export</a>
            </div>
        </div>
        <table role="grid" class="hover">
            <thead>
                <tr>
                    <th width="50">Sr#</th>
                    <th width="100">Parent Resource ID</th>
                    <th width="400">Parent Page URL</th>
                    <th>Children</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="r in filteredRes">
                    <td ng-bind="r.counter"></td>
                    <td ng-bind="r.parentresourceid"></td>
                    <td ng-bind="r.parentpageurl"></td>
                    <td>
                        <table>
                            <thead>
                                <tr>
                                    <th>Child Resource ID</th>
                                    <th>Child Page URL</th>
                                    <th>Child Page Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="ch in r.children">
                                    <td width="200" ng-bind="ch.resourceid"></td>
                                    <td width="400" ng-bind="ch.childpageurl"></td>
                                    <td width="200" ng-bind="ch.pageviews"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <!--<td ng-bind="r.resourceid"></td>-->
<!--                    <td ng-bind="r.childpageurl"></td>
                    <td ng-bind="r.pageviews"></td>-->
                </tr>
                <tr ng-show="!res.length">
                    <td colspan="5" class="text-center">No Child Resource</td>
                </tr>
            </tbody>
        </table>

        <div data-pagination="" data-num-pages="numPages()" 
             data-current-page="currentPage" data-max-size="maxSize"  
             data-boundary-links="true" ng-hide="HidePagination"></div>
    </form>
</div>