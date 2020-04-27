var app = angular.module('ResourceComments', ['ui.bootstrap']);

app.controller('CommentsController', function ($scope, $http, $window) {
    $scope.HidePagination = true;
    $scope.HideExportButton = true;
    $scope.HideLoading = true;
    $scope.HideDeleteLoading = true;
    $scope.HideDeleteBulkLoading = true;
    $scope.HideDeleteBulkButton = true;
    $scope.loadingDeleteComment = {};
    
    $scope.data = {};
    $scope.pageurl = '';
    $scope.ajaxurl = '';
    $scope.res = [];
    $scope.res.comments = [];
    $scope.res.resource = {};
    $scope.filteredRes = []
            , $scope.currentPage = 1
            , $scope.numPerPage = 10
            , $scope.maxSize = 5;

    $scope.comment_errors = false;
    $scope.comment_msg = '';
    
    $scope.FindComments = function (data, $event) {
        $event.preventDefault();
        $scope.pageurl = data.pageurl;
        $scope.ajaxurl = data.ajaxurl;
        $scope.HideLoading = false;
        $scope.comment_errors = false;
        $scope.comment_deleted = false;
        $http({
            method: "POST",
            url: data.ajaxurl,
            params: {action: 'resource_comments', pageurl: data.pageurl, security: data.security},
            headers: {
                'Content-type': 'application/json'
            }
        }).then(function mySuccess(response) {
            $scope.res.comments = [];
            $scope.res.resource = {};
            $scope.filteredRes = [];
            $scope.HideLoading = true;
            
            
            
            $scope.res = response.data;
            if($scope.res.comments.length > 0){
                for (var key in $scope.res.comments) {
                    $scope.res.comments[key]['checked'] = false;
                }

                if (response.data.comments.length > $scope.numPerPage) {
                    $scope.HidePagination = false;
                }
                else {
                    $scope.HidePagination = true;
                }
                var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                        , end = begin + $scope.numPerPage;

                $scope.filteredRes = $scope.res.comments.slice(begin, end);
            } else { // no comments found
                $scope.comment_errors = true;
                $scope.comment_class = 'danger';
                $scope.comment_msg = $scope.res.msg;
            }
            
//            console.log($scope.filteredRes);
        }, function myError(response) {
        });
//        $scope.HideLoading = true;
    };
    
    $scope.delete_msg = '';
    $scope.comment_deleted = false;
    
    $scope.delete_class = 'success';
    
    $scope.selected = {};
    $scope.DeleteComment = function (r, data, index, $event) {
        $event.preventDefault();
        if(confirm("Are You Sure?")){
            $scope.comment_deleted = false;
            $scope.comment_errors = false;
            $scope.loadingDeleteComment = r;
            $scope.HideDeleteLoading = false;
            $http({
                method: "POST",
                url: data.ajaxurl,
                params: {action: 'delete_comment', to_delete_comments:JSON.stringify([{resourceid: r.resourceid, userid:r.userid, commentdate:r.commentdate}]), security: data.security},
                headers: {
                    'Content-type': 'application/json'
                }
            }).then(function mySuccess(response) {
                $scope.loadingDeleteComment = false;
                $scope.HideDeleteLoading = true;
                if(response.data.success){
                    $scope.delete_class = 'success';
                } else {
                    $scope.delete_class = 'danger';
                }
                $scope.res.comments.splice(index, 1);
                $scope.filteredRes.splice(index, 1);
                $scope.delete_msg = response.data.msg;
                $scope.comment_deleted = true;
            }, function myError(response) {
                $scope.loadingDeleteComment = false;
                $scope.HideDeleteLoading = true;
            });
        }
    };
    
    
    $scope.DeleteBulkComments = function ( data, $event) {
        $event.preventDefault();
        if(confirm("Are You Sure?")){
            var to_delete_comments = $scope.showSelected($scope.filteredRes);
//            return false;
            $scope.comment_deleted = false;
            $scope.comment_errors = false;
            $scope.HideDeleteBulkLoading = false;
            
//            $scope.loadingDeleteComment = r;
            $http({
                method: "POST",
                url: data.ajaxurl,
                params: {action: 'delete_comment', to_delete_comments:JSON.stringify(to_delete_comments), security: data.security},
                headers: {
                    'Content-type': 'application/json'
                }
            }).then(function mySuccess(response) {
                $scope.HideDeleteBulkButton = true;
                $scope.HideDeleteBulkLoading = true;
                if(response.data.success){
                    $scope.delete_class = 'success';
                } else {
                    $scope.delete_class = 'danger';
                }
                
                $scope.filteredRes = $scope.filteredRes.filter(function(el) {
                    return el.checked !== true;
                });
                $scope.res.comments = $scope.res.comments.filter(function(el) {
                    return el.checked !== true;
                });
                
//                $scope.res.comments = $scope.filteredRes;
//                console.log($scope.res.comments);
//                $scope.filteredRes.splice(index, 1);
                $scope.delete_msg = response.data.msg;
                $scope.comment_deleted = true;
            }, function myError(response) {
                $scope.HideDeleteBulkLoading = true;
            });
        }
    };
    
    
    $scope.numPages = function () {
        return Math.ceil($scope.res.comments.length / $scope.numPerPage);
    };

    $scope.$watch('currentPage + numPerPage + res.comments', function () {
//        console.log($scope.res.comments);
        var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                , end = begin + $scope.numPerPage;
//        console.log(begin);
//        console.log(end);

        $scope.filteredRes = $scope.res.comments.slice(begin, end);
    });
    
    $scope.showSelected = function(data){
        var tempArray = [];
        for(var i=0; i<data.length; i++){
          if(data[i].checked) tempArray.push(data[i]);
        }
        return tempArray; //or maybe console.log(tempArray) or encode to JSON etc.
    }
    
    $scope.checkSelected = function(){
        var to_delete_comments = $scope.showSelected($scope.filteredRes);
        if(to_delete_comments.length > 0){
            $scope.HideDeleteBulkButton = false;
        } else {
            $scope.HideDeleteBulkButton = true;
        }
    }
    
    
    
    //new pagination
    //get another portions of data on page changed
    $scope.pageChanged = function() {
      getData();
    };
    
    function getData() {
        $http.get("https://api.spotify.com/v1/search?query=iron+&offset="+($scope.currentPage-1)*$scope.limit+"&limit=20&type=artist")
            .then(function(response) {
                $scope.totalItems = response.data.artists.total
                angular.copy(response.data.artists.items, $scope.tracks)
      });
    }
});


