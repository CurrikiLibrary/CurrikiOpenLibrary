var app = angular.module('HierarchialChildren', ['ui.bootstrap']);

app.controller('ChildrenController', function ($scope, $http, $window) {

    $scope.HidePagination = true;
    $scope.HideExportButton = true;
    $scope.HideLoading = true;
    $scope.data = {};
    $scope.res = [];
    $scope.filteredRes = []
            , $scope.currentPage = 1
            , $scope.numPerPage = 10
            , $scope.maxSize = 5;

    $scope.FindChildren = function (data, $event) {
        $event.preventDefault();
        $scope.HideLoading = false;
        $http({
            method: "POST",
            url: data.ajaxurl,
            params: {action: 'hierarchial_child', pageurl: data.pageurl, security: data.security, startdate:jQuery('#startdate').val(), enddate:jQuery('#enddate').val()},
            headers: {
                'Content-type': 'application/json'
            }
        }).then(function mySuccess(response) {
            $scope.HideLoading = true;

            $scope.res = response.data;
            if (response.data.length > 0) {
                $scope.HideExportButton = false;
                $scope.HidePagination = false;
            }
            else {
                $scope.HideExportButton = true;
                $scope.HidePagination = true;
            }
            var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                    , end = begin + $scope.numPerPage;

            $scope.filteredRes = $scope.res.slice(begin, end);
            console.log('Success');
            console.log(response);
        }, function myError(response) {
            $scope.HideLoading = true;
            console.log('Error');
            console.log(response);
        });
//        $scope.HideLoading = true;
    };

    $scope.ExportToExcel = function (data, $event) {
        $event.preventDefault();
        var loc = "";
        var redirectWindow = window.open(loc, '_blank');
        redirectWindow.document.write('Please wait.. Spreadsheet is being created...');
        var params = jQuery.param({
            action: 'export_excel',
            security: data.security,
            pageurl: data.pageurl,
            startdate:jQuery('#startdate').val(),
            enddate:jQuery('#enddate').val()
        });
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }

        $http.post(data.ajaxurl, params, config)
                .success(function (response, status, headers, config) {
                    redirectWindow.location.href = data.baseurl + '/wp-content/plugins/curriki_manage/PHPExcel/output_files/' + response.filename;
                    console.log('Success');
                    console.log(response);
                })
                .error(function (response, status, header, config) {
                    console.log('Error');
                    console.log(response);
                });
    };
    $scope.ExportToExcelChildrenNLeafNodes = function (data, $event) {
        $event.preventDefault();
        var loc = "";
        var redirectWindow = window.open(loc, '_blank');
        redirectWindow.document.write('Please wait.. Spreadsheet is being created...');
        var params = jQuery.param({
            action: 'export_excel_children_leafnodes',
            security: data.security,
            pageurl: data.pageurl,
            startdate:jQuery('#startdate').val(),
            enddate:jQuery('#enddate').val()
        });
        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }

        $http.post(data.ajaxurl, params, config)
                .success(function (response, status, headers, config) {
                    redirectWindow.location.href = data.baseurl + '/wp-content/plugins/curriki_manage/PHPExcel/output_files/' + response.filename;
                    console.log('Success');
                    console.log(response);
                })
                .error(function (response, status, header, config) {
                    console.log('Error');
                    console.log(response);
                });
    };

    $scope.numPages = function () {
        return Math.ceil($scope.res.length / $scope.numPerPage);
    };

    $scope.$watch('currentPage + numPerPage', function () {
        var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                , end = begin + $scope.numPerPage;
//        console.log(begin);
//        console.log(end);

        $scope.filteredRes = $scope.res.slice(begin, end);
    });
});


