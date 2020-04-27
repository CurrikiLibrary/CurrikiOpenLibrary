/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var ngApp = angular.module('ngApp', ["ngSanitize"]);


ngApp.controller('searchCtrl', function ($scope, $http) {
    NProgress.configure({trickleRate: 0.01, trickleSpeed: 10});
    $scope.ajaxurl = '';
    $scope.baseurl = '';

    $scope.items = [];
    $scope.items_groups = [];
    $scope.items_members = [];

    $scope.hoversubjectid = '';
    $scope.jurisdictioncodeArr = [];
    $scope.standardtitlesArr = [];
    $scope.notationArr = [];
    $scope.jurisdictioncodeVal = '';
    $scope.standardtitlesVal = '';
    $scope.notationVal = '';
    $scope.search_type = '';
    $scope.searchTab = 'resource'

    $scope.inArray = function (needle, arr) {
        var length = arr.length;
        for (var i = 0; i < length; i++) {
            if (arr[i] == needle)
                return true;
        }
        return false;
    }
    $scope.roundNum = function (i) {
        return Math.round(i);
    }

    $scope.filterJuris = function (item) {
        if ($scope.jurisdictioncodeVal.indexOf(item.jurisdictioncode) != -1)
            return true;
        return false; // otherwise it won't be within the results
    };
    $scope.getNotation = function () {
        jQuery('#standards-accordion li:eq(2) .standards-tab-header').click();
        $scope.notationArr = [{'statementid': '0', 'description': ml_obj.loading_ml}];
        NProgress.start();
        $http({
            method: 'POST',
            url: $scope.ajaxurl,
            data: jQuery.param({'action': 'get_notation', 'selectedstandardid': $scope.standardtitlesVal}), // pass in data as strings
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {
            $scope.notationArr = data;
            NProgress.done();
        }).error(function (data) {
            NProgress.done();
            jQuery('#standards-accordion li:eq(1) .standards-tab-header').click();
        });
    }
    $scope.getDocumentTitle = function () {
        jQuery('#standards-accordion li:eq(1) .standards-tab-header').click();
    }

    $scope.subjectHover = function (subjectid) {
        $scope.hoversubjectid = subjectid;
    }
    $scope.isSubjectHover = function (subjectid) {
        return $scope.hoversubjectid == subjectid;
    }

    $scope.changeSearchType = function ($tab) {
    }

    $scope.btnClickSearch = function () {
        $scope.pagination.isPaginationCall = false;
        $scope.pagination.current = 1;
        $scope.makeSearch();
    }

    $scope.makeSearch = function () {
        NProgress.start();
        if (!$scope.pagination.isPaginationCall) {
            jQuery('#pageNumber').val(1);
        } else {
            jQuery('#pageNumber').val($scope.pagination.current);
        }
        $scope.pagination.isPaginationCall = false;
        advance('close');
        $http({
            method: 'POST',
            url: $scope.baseurl + 'wp-content/libs/cloud_search/search_handler' + $scope.search_type + '.php',
            data: jQuery("#search_form").serialize(), // pass in data as strings
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {

            $scope.resultCoun = 0;
            if (!$scope.hasOwnProperty('query') || $scope.query == '') {
                $scope.searched_query = ' ';
            } else if ($scope.query.indexOf(",") >= 0) {
                $scope.searched_query = $scope.query;
                $scope.suggested_query = $scope.query.replace(/\,/g, ' ');
            } else if ($scope.query.indexOf(" ") >= 0) {
                $scope.searched_query = $scope.query;
                $scope.suggested_query = $scope.query.replace(/\ /g, ',');
            } else {
                $scope.searched_query = $scope.query;
                $scope.suggested_query = '';
            }

            if (data.hasOwnProperty('hits')) {
                $scope.resultCoun = data.hits.found;
                //console.log(search_type); return;
                if ($scope.search_type == '') {
                    $scope.items = data.hits.hit;
                    $scope.items_groups = [];
                    $scope.items_members = [];
                    $scope.pagination.pageSize = 10;
                } else if ($scope.search_type == '_groups') {
                    $scope.items = [];
                    $scope.items_groups = data.hits.hit;
                    $scope.items_members = [];
                    $scope.pagination.pageSize = 12;
                } else if ($scope.search_type == '_members') {
                    $scope.items = [];
                    $scope.items_groups = [];
                    $scope.items_members = data.hits.hit;
                    $scope.pagination.pageSize = 15;
                }
            } else {
                $scope.items = [];
                $scope.items_groups = [];
                $scope.items_members = [];
            }
            $scope.makePages(data.hits.found);
            NProgress.done();
            if ($scope.search_type == '') {
                $scope.loadMoreInfo();
            }
        }).error(function (data) {
            NProgress.done();
        });
    }
    $scope.applySuggestedQuery = function () {
        jQuery('.search-field input').val($scope.suggested_query);
        $temp = $scope.query;
        $scope.query = $scope.suggested_query;
        $scope.suggested_query = $temp;
        $scope.makeSearch();
    }
    $scope.setLanguage = function () {
        if ($scope.searched_query != null && $scope.searched_query != '')
            $scope.makeSearch();
    }
    $scope.clearSearch = function () {
        NProgress.done();
        advance('close');
        $scope.items = []
        $scope.items_groups = [];
        $scope.items_members = [];
        $scope.hoversubjectid = '';
        $scope.notationArr = [];
        $scope.jurisdictioncodeVal = '';
        $scope.standardtitlesVal = '';
        $scope.notationVal = '';
        $scope.query = '';
        $scope.searched_query = '';
        $scope.suggested_query = '';
        $scope.pagination.isPaginationCall = false;
        $scope.resultCoun = 0;
        $scope.makePages(0);
        $scope.sort_by_model = '';
        $scope.language_model = '';
        jQuery('.advanced-slide input[type="checkbox"]').attr('checked', false);
        jQuery('html, body').animate({
            scrollTop: jQuery("#search_results_pointer").offset().top - 300
        }, 1000);
    }

    $scope.is_coll_more = function (item, type) {
        return $scope.coll_more_open == item.id + type
    }
    $scope.coll_more_func = function (item, type) {
        if (type == 'share' && $scope.is_coll_more(item, type))
            $scope.coll_more_open = '';
        else
            $scope.coll_more_open = item.id + type
    }
    $scope.loadMoreInfo = function () {
        var concIds = '';
        for (i = 0; i < $scope.items.length; i++) {
            if (concIds != '')
                concIds += ',';
            concIds += $scope.items[i].fields.id;
        }
        //console.log(concIds);
        $http({
            method: 'POST',
            url: $scope.baseurl + '?search=moreinfo&r_ids=' + concIds,
            data: '',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {
            angular.forEach($scope.items, function (value, key) {
                angular.forEach(data, function (value_moreinfo, key_moreinfo) {
                    //console.log(','+value.fields.id +'=='+ value_moreinfo.id);
                    if (value.fields.id == value_moreinfo.id) {
                        $scope.items[key].fields.license = value_moreinfo.fields.license;
                        $scope.items[key].fields.resourceviews = value_moreinfo.fields.resourceviews;
                        $scope.items[key].fields.collections = value_moreinfo.fields.collections;
                        $scope.items[key].fields.alignments = value_moreinfo.fields.alignments;
                        $scope.items[key].fields.resource_collections = value_moreinfo.fields.resource_collections;
                        $scope.items[key].fields.user_nicename = value_moreinfo.fields.user_nicename;
                        $scope.items[key].fields.subjects = value_moreinfo.fields.subjects;
                        //$scope.items[key].fields.grade_levels = value_moreinfo.fields.grade_levels;
                        //$scope.items[key].fields.type = value_moreinfo.fields.type;
                    }
                });
            });
        }).error(function (data) {

        });
    }

    $scope.add_to_my_library = function (id) {
        $http({
            method: 'POST',
            url: $scope.baseurl + '?add_to=my_library&r_id=' + id,
            data: '',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {
            load_add_to_lib_modal(id);
        }).error(function (data) {
        });
    }
    $scope.showLicense = function (license, type) {
        if (license && license.length) {
            $lic = license.split(",");
            if (type == 'link') {
                return $lic[1];
            } else if (type == 'image') {
                return $scope.baseurl + 'wp-content/themes/genesis-curriki/images/licenses/' + $lic[0].replace(" ", "-") + '.png';
            }
        }
    }
    $scope.currikiRateThis = function (id, title) {
        jQuery('#rate_resource-dialog').show();
        jQuery('#review-resource-id').val(id);
        jQuery('.curriki-review-title').html(title);
        setInterval(function () {
            jQuery('#rate_resource-dialog').center()
        }, 1);
    }
    $scope.memberName = function (fname, lname) {
        var $membername = '';
        if (fname) {
            $membername = fname;
        }
        if (lname) {
            $membername += ' ' + lname;
        }
        if ($membername == '') {
            $membername = 'N/A';
        }
        return $membername;
    }
    $scope.userGrades = function (education_level) {
        var ret = new Array();
        angular.forEach($scope.education_levels2, function (value, key) {
            angular.forEach(value['arlevels'], function (v, k) {
                angular.forEach(education_level, function (v_el, k_el) {
                    if (v == v_el) {
                        if (!$scope.inArray(value['title'], ret))
                            ret.push(value['title']);
                    }
                });
            });
        });
        e_level = '';
        angular.forEach(ret, function (value, key) {
            if (e_level != '')
                e_level += ', ';
            e_level += value;
        });
        return e_level;
    }

    //****************Pagination Part*******************/
    $scope.pagination = {current: 1, totalPages: 9, pageSize: 10, totalItems: 90, pageLinks: [], isPaginationCall: 0};
    $scope.pagination.pageLinks = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    $scope.setCurrentPage = function (pageNum) {
        if ($scope.pagination.current == pageNum || $scope.pagination.current == '-1')
            return false
        $scope.pagination.current = pageNum;
        $scope.pagination.isPaginationCall = true;
        $scope.makeSearch();
    }
    $scope.makePages = function ($found) {
        $scope.pagination.totalItems = $found;
        $scope.pagination.totalPages = Math.ceil($found / $scope.pagination.pageSize);

        $start = 1;
        $end = 1;
        $scope.pagination.pageLinks = [];

        if ($scope.pagination.current - 4 <= 0) {
            $start = 1;
            $end = 9;
        } else if ($scope.pagination.current + 4 >= $scope.pagination.totalPages) {
            $start = $scope.pagination.totalPages - 9;
            $end = $scope.pagination.totalPages;
        } else {
            $start = $scope.pagination.current - 4;
            $end = $scope.pagination.current + 4;
        }

        if ($start <= 0)
            $start = 1;
        if ($end >= $scope.pagination.totalPages)
            $end = $scope.pagination.totalPages;

        for (; $start <= $end; $start++)
            $scope.pagination.pageLinks.push($start);

        jQuery('html, body').animate({
            scrollTop: jQuery("#search_results_pointer").offset().top - 100
        }, 1000);
    }

    //*********Initializing Search things ****/
    $scope.init = function () {
        if ($scope.query.length || $scope.submitted)
            $scope.makeSearch();
    }

});


ngApp.controller('createResourceCtrl', function ($scope, $http, $timeout, $window) {
    NProgress.configure({trickleRate: 0.01, trickleSpeed: 10});
    $scope.statements = [{'statementid': '0', 'description': ml_obj.topic_area_ml}];
    $scope.aligntags = [{'statementid': '0', 'notation': '', 'description': ml_obj.alignment_tag_ml}];
    $scope.hoversubjectid = '';
    $scope.standardBoxs = [];
    $scope.statementid = 0;
    $scope.aligntagid = 0;
    $scope.pageurl = '';
    $scope.resourceid = 0;

    $scope.standardBox = {};
    //$scope.resourceid = 0;
    $scope.subject_hover = function (subjectid) {
        $scope.hoversubjectid = subjectid;
    }
    $scope.is_subject_hover = function (subjectid) {
        return $scope.hoversubjectid == subjectid;
    }

    $scope.populateStatements = function () {                
        
        if (!$scope.standardid || !$scope.levelid)
            return;
        $scope.statements = [{'statementid': '0', 'description': ml_obj.loading_ml}];
        $scope.statementid = 0;
        NProgress.start();
        $http({
            method: 'POST',
            url: ajaxurl,
            data: jQuery.param({'action': 'get_cr_statements', 'standardid': $scope.standardid, 'levelid': $scope.levelid}), // pass in data as strings
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {
            $scope.statements = [{'statementid': '0', 'description': ml_obj.topic_area_ml}];
            $scope.statements = $scope.statements.concat(data);
            $scope.statementid = '0';
            $scope.aligntagid = '0';
            $scope.standardBox = {};
            NProgress.done();
        }).error(function (data) {
            NProgress.done();
        });
    }

    $scope.populateAlignTag = function () {
        if (!$scope.statementid)
            return;
        $scope.aligntags = [{'statementid': '0', 'notation': '', 'description': ml_obj.loading_ml}];
        $scope.aligntagid = 0;
        NProgress.start();
        $http({
            method: 'POST',
            url: ajaxurl,
            data: jQuery.param({'action': 'get_cr_aligntag', 'statementid': $scope.statementid}), // pass in data as strings
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {
            $scope.aligntags = [{'statementid': '0', 'notation': '', 'description': ml_obj.alignment_tag_ml}];
            $scope.aligntags = $scope.aligntags.concat(data);
            $scope.aligntagid = '0';
            $scope.standardBox = {};
            NProgress.done();
        }).error(function (data) {
            NProgress.done();
        });
    }

    $scope.populateAlignBox = function () {
        if (!$scope.aligntagid || !$scope.statementid || !$scope.standardid || !$scope.levelid)
            return;
        NProgress.start();
        $http({
            method: 'POST',
            url: ajaxurl,
            data: jQuery.param({'action': 'get_cr_standard_box', 'standardid': $scope.standardid, 'levelid': $scope.levelid, 'statementid': $scope.statementid, 'aligntagid': $scope.aligntagid}), // pass in data as strings
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}  // set the headers so angular passing info as form data (not request payload)
        }).success(function (data) {
            $scope.standardBox = data;
            console.log($scope.standardBox);
            NProgress.done();
        }).error(function (data) {
            NProgress.done();
        });
    }

    $scope.addStandard = function () {
        $scope.standardBoxs.push($scope.standardBox);
        $scope.standardBox = {};
        $scope.aligntagid = '';
    }

    $scope.clearStandard = function () {
        $scope.standardBox = {};
        $scope.standardid = '';
        $scope.levelid = '';
        $scope.statementid = '';
        $scope.aligntagid = '';
    }

    $scope.removeStandard = function ($index) {
        $scope.standardBoxs.splice($index, 1);
    }

    $scope.createResource = function () {
        jQuery('#create_resource_form').find('[name=action]').val('create_resource');
        if (jQuery('#resource-title').val() == '')
        {
            jQuery('#resource-tabs').tabs( "option", "active", 0 );
            jQuery('#resource-title').focus();
            alert('Resource title cannot be empty!');
            return '';
        }
        if (jQuery('#description').val() == '')
        {
            jQuery('#resource-tabs').tabs( "option", "active", 0 );
            jQuery('#description').focus();
            alert('Resource description cannot be empty!');
            return '';
        }
        if (jQuery('#resource-title').val() == '' || (jQuery('#elm1').length && tinymce.get('elm1').getContent() == ''))
        {
            jQuery('#resource-tabs').tabs( "option", "active", 0 );
            tinymce.execCommand('mceFocus',false,'content');
            alert('Resource Content cannot be empty!');
            return '';
        }

        if (jQuery('#elm1').length)
            jQuery('#create_resource_form [name="content"]').val(tinyMCE.activeEditor.getContent());
        NProgress.start();
        
        var tm = Math.floor(Date.now());
        
        $http.post(ajaxurl+"?tm="+tm, new FormData(jQuery('#create_resource_form').get(0)), {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'X-Requested-With': 'XMLHttpRequest'}
        }).error(function () {
            NProgress.done();
        }).success(function (data, status, headers, config) {
            if(data.content != ''){
                tinyMCE.activeEditor.setContent(data.content);
                attachEditButtonClickEvent();
            }
            
            if (data.hasOwnProperty('resourceid'))
                $scope.resourceid = data.resourceid;
            if (data.hasOwnProperty('pageurl'))
                $scope.pageurl = data.pageurl;
            jQuery('#fancyBoxInline').click();
            NProgress.done();
        });
    }
    $scope.previewResource = function(){
        
        //Opening a new tab for preview
        var loc = $scope.baseurl+'oer-preview';
        var prid_exists = false;
        var prid_val;
        if(jQuery('[name=prid]').val()){
            prid_val = jQuery('[name=prid]').val();
            prid_exists = true;
        }
        jQuery('#create_resource_form').find('[name=action]').val('preview_resource');
        if (jQuery('#resource-title').val() == '')
        {
            jQuery('#resource-tabs').tabs( "option", "active", 0 );
            jQuery('#resource-title').focus();
            alert('Resource title cannot be empty!');
            return '';
        }
        if (jQuery('#description').val() == '')
        {
            jQuery('#resource-tabs').tabs( "option", "active", 0 );
            jQuery('#description').focus();
            alert('Resource description cannot be empty!');
            return '';
        }
        if (jQuery('#resource-title').val() == '' || (jQuery('#elm1').length && tinymce.get('elm1').getContent() == ''))
        {
            jQuery('#resource-tabs').tabs( "option", "active", 0 );
            tinymce.execCommand('mceFocus',false,'content');
            alert('Resource Content cannot be empty!');
            return '';
        }
        var redirectWindow = window.open(loc, '_blank');
        if (jQuery('#elm1').length)
            jQuery('#create_resource_form [name="content"]').val(tinyMCE.activeEditor.getContent());
        NProgress.start();
        $http.post(ajaxurl, new FormData(jQuery('#create_resource_form').get(0)), {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'X-Requested-With': 'XMLHttpRequest'}
        }).error(function () {
            NProgress.done();
        }).success(function (data, status, headers, config) {
//            if (data.hasOwnProperty('resourceid'))
//                $scope.resourceid = data.resourceid;
//            if (data.hasOwnProperty('pageurl'))
//                $scope.pageurl = data.pageurl;
            console.log(data);
            // Redirecting the new tab and appending resourceid to it
            jQuery('[name=preview_resource_id]').val(data.resourceid);
            if(prid_exists){
                redirectWindow.location.href = loc + '/?rid='+data.resourceid+'&mrid='+prid_val;
            }else{
                redirectWindow.location.href = loc + '/?rid='+data.resourceid+'&preview=true';
            }
            
            NProgress.done();
        });
    }

    $scope.viewResource = function () {
        window.location.href = $scope.baseurl + 'oer/' + $scope.pageurl;
    }

});



