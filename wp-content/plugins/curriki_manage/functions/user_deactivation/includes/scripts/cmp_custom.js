var app = angular.module('UserActivation', ['ui.bootstrap']);

app.controller('UserController', function ($scope, $http, $window) {
    $scope.HidePagination = true;
    $scope.HideExportButton = true;
    $scope.HideLoading = true;
    $scope.HideDeleteLoading = true;
    $scope.HideDeleteBulkLoading = true;
    $scope.HideDeleteBulkButton = true;
    $scope.loadingActiveInactiveComment = {};
    
    $scope.data = {};
    $scope.user_login = '';
    $scope.ajaxurl = '';
    
    $scope.user = {};
    $scope.filteredRes = []
            , $scope.currentPage = 1
            , $scope.numPerPage = 10
            , $scope.maxSize = 5;

    $scope.comment_errors = false;
    $scope.comment_msg = '';
    $scope.user_active = 'T';
    $scope.FindActivation = function (data, $event) {
        $event.preventDefault();
        $scope.user_login = data.user_login;
        $scope.ajaxurl = data.ajaxurl;
        $scope.HideLoading = false;
        $scope.comment_errors = false;
        $scope.user_updated = false;
        $http({
            method: "POST",
            url: data.ajaxurl,
            params: {action: 'user_deactivation', user_login: data.user_login, security: data.security},
            headers: {
                'Content-type': 'application/json'
            }
        }).then(function mySuccess(response) {
            $scope.HideLoading = true;
            $scope.user = response.data.user;
            $scope.user_active = response.data.user.active;
//            console.log($scope.filteredRes);
        }, function myError(response) {
        });
//        $scope.HideLoading = true;
    };
    
    $scope.user_message = '';
    $scope.user_updated = false;
    
    
    $scope.user_class = 'success';
    
    $scope.selected = {};
    $scope.ActiveInactiveComment = function (user, data, todo, $event) {
        $event.preventDefault();
        if(confirm("Are You Sure?")){
            $scope.user_updated = false;
            $scope.comment_errors = false;
            $scope.loadingActiveInactiveComment = user;
            $scope.HideDeleteLoading = false;
            $http({
                method: "POST",
                url: data.ajaxurl,
                params: {action: 'active_inactive_user', userid:user.userid, todo:todo, security: data.security},
                headers: {
                    'Content-type': 'application/json'
                }
            }).then(function mySuccess(response) {
                $scope.loadingActiveInactiveComment = false;
                $scope.HideDeleteLoading = true;
                if(response.data.success){
                    $scope.user_class = 'success';
                } else {
                    $scope.user_class = 'danger';
                }
                if($scope.user_active == 'T'){
                    $scope.user_active = 'F';
                } else {
                    $scope.user_active = 'T';
                }
                $scope.user_message = response.data.msg;
                $scope.user_updated = true;
            }, function myError(response) {
                $scope.loadingActiveInactiveComment = false;
                $scope.HideDeleteLoading = true;
            });
        }
    };
});


