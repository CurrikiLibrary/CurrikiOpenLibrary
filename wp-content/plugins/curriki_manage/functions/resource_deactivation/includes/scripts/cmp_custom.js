var app = angular.module('ResourceActivation', ['ui.bootstrap']);

app.controller('ResourceController', function ($scope, $http, $window) {
    $scope.HidePagination = true;
    $scope.HideExportButton = true;
    $scope.HideLoading = true;
    $scope.HideDeleteLoading = true;
    $scope.HideDeleteBulkLoading = true;
    $scope.HideDeleteBulkButton = true;
    $scope.loadingActiveInactiveComment = {};
    
    $scope.data = {};
    $scope.pageurl = '';
    $scope.ajaxurl = '';
    
    $scope.resource = {};
    $scope.filteredRes = []
            , $scope.currentPage = 1
            , $scope.numPerPage = 10
            , $scope.maxSize = 5;

    $scope.comment_errors = false;
    $scope.comment_msg = '';
    $scope.resource_active = 'T';
    $scope.FindActivation = function (data, $event) {
        $event.preventDefault();
        $scope.pageurl = data.pageurl;
        $scope.ajaxurl = data.ajaxurl;
        $scope.HideLoading = false;
        $scope.comment_errors = false;
        $scope.resource_updated = false;
        $http({
            method: "POST",
            url: data.ajaxurl,
            params: {action: 'resource_deactivation', pageurl: data.pageurl, security: data.security},
            headers: {
                'Content-type': 'application/json'
            }
        }).then(function mySuccess(response) {
            $scope.HideLoading = true;
            $scope.resource = response.data.resource;
            $scope.resource_active = response.data.resource.active;
            console.log($scope.resource_active);
//            console.log($scope.filteredRes);
        }, function myError(response) {
        });
//        $scope.HideLoading = true;
    };
    
    $scope.resource_message = '';
    $scope.resource_updated = false;
    
    
    $scope.resource_class = 'success';
    
    $scope.selected = {};
    $scope.ActiveInactiveComment = function (resource, data, todo, $event) {
        $event.preventDefault();
        if(confirm("Are You Sure?")){
            $scope.resource_updated = false;
            $scope.comment_errors = false;
            $scope.loadingActiveInactiveComment = resource;
            $scope.HideDeleteLoading = false;
            $http({
                method: "POST",
                url: data.ajaxurl,
                params: {action: 'active_inactive_resource', resourceid:resource.resourceid, todo:todo, security: data.security},
                headers: {
                    'Content-type': 'application/json'
                }
            }).then(function mySuccess(response) {
                $scope.loadingActiveInactiveComment = false;
                $scope.HideDeleteLoading = true;
                if(response.data.success){
                    $scope.resource_class = 'success';
                } else {
                    $scope.resource_class = 'danger';
                }
                if($scope.resource_active == 'T'){
                    $scope.resource_active = 'F';
                } else {
                    $scope.resource_active = 'T';
                }
                $scope.resource_message = response.data.msg;
                $scope.resource_updated = true;
            }, function myError(response) {
                $scope.loadingActiveInactiveComment = false;
                $scope.HideDeleteLoading = true;
            });
        }
    };
});


