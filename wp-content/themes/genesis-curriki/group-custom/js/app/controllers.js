/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var ngappmodal = angular.module('ngappmodal', []);
/*
ngappmodal.directive('droppable', function($compile) {
    return {
        link: function(scope, element, attrs) {            
            jq(element).droppable({
                
                drop: function( event, ui ) { 
                    
                    var target_classes_arr = jq(event.target).attr("class").split(" ");
                    
                    console.log("target_classes_arr = " , target_classes_arr);
                    
                    if( jq(event.target).attr("class").split(" ").length > 0 )
                    {
                        ui.draggable.draggable('option','revert',false);
                        
                        var dno_arr = target_classes_arr[1].split("-");
                        var dno_val = dno_arr[1];
                        
                        var rid_arr = target_classes_arr[2].split("-");
                        var rid_val = rid_arr[1];
                        
                        
                        var tree_level = target_classes_arr[3];
                        
                        scope.$apply(function () {
                            scope.setTreeLevel(tree_level);
                        }); 
                        
                        
                        var resource_id_val = "";
                        
                        if(jq("input[name='resourceid']").get().length > 0)
                        {
                            resource_id_val = jq("input[name='resourceid']").val().toString();
                        }else{
                            
                            resource_id_val = jq("#rid_mdl").val().toString();
                        }
                        
                        
                        if(rid_val.toString() != resource_id_val && rid_val.toString().length > 0)
                        {
                            

                                scope.$apply(function () {
                                    scope.setCurrentResource(rid_val,dno_val);                            
                                }); 

                                var resources_of_current_collection = scope.resources_of_current_collection;                        
                                var dno_required = parseInt(dno_val)+1;
                                
                                //console.log(dno_val , dno_required);


                                //var resource_id_current = jq("input[name='resourceid']").val();
                                var resource_id_current = resource_id_val;
                                var new_item = {"RID":resource_id_current, "Resource": jq("#resource-title-modal").text() , isNew:true}

                                if(tree_level === "tree-level-group-resource")
                                {
                                    new_item.type = "resource";
                                    new_item.Collection = jq("#resource-title-modal").text();
                                    new_item.title = jq("#resource-title-modal").text();
                                    new_item.groupid = scope.selected_group;
                                    
                                    
                                    scope.$apply(function () {
                                        scope.set_new_group_resource(new_item);
                                    }); 
                                }
                                
                                //===== get index deleting existing dropped resource =======
                                var dl_cntr = 0;
                                var is_resource_exist = false;
                                
                                //scope.libraryTopTree = ["My Collections","My Groups"];
                                //scope.libraryTopTree = "My Collections","My Groups"];
                                
                                jq(scope.resources_of_current_collection).each(function(i,obj){                                    
                                    if(obj.hasOwnProperty("RID") && obj.RID.toString() === new_item.RID.toString() )
                                    {                                                                
                                        dl_cntr = i;
                                        is_resource_exist = true;                                                                                                    
                                    }
                                });     
                                

                                if(is_resource_exist)
                                {                            
                                    scope.$apply(function () {
                                        scope.resources_of_current_collection.splice(dl_cntr, 1);                            
                                    });                                                        
                                }                                

                                //===== Insert dropped resource as new ==== 
                                var target_classes_arr_d = jq(event.target).attr("class").split(" ");
                                var rid_arr_d = target_classes_arr_d[2].split("-");
                                var rid_val_d = rid_arr_d[1];                                                                
                                var insert_cntr = 0;
                                jq(scope.resources_of_current_collection).each(function(i,obj){
                                    if(obj.hasOwnProperty("RID") && obj.RID.toString() === rid_val_d.toString())
                                    {
                                        insert_cntr = (i+1);
                                    }
                                });
                                
                                        //=== Delete 'No Found Resource' ====                                        
                                        if( scope.resources_of_current_collection.length == 1)
                                        {
                                            if(scope.resources_of_current_collection[0].Resource == "No Resource Found!")
                                            {
                                                insert_cntr = 0;
                                                scope.$apply(function () {
                                                    scope.resources_of_current_collection[0] = new_item;
                                                });  
                                                
                                                //console.log( "==> " , scope.resources_of_current_collection );
                                            }
                                        }
                            
                                
                            
                                //===== [start] Insert of drop ======                                                                   
                                
                                if(tree_level === "tree-level-resource")
                                {
                                    scope.$apply(function () {                                    
                                        scope.resources_of_current_collection.splice(insert_cntr, 0, new_item);                            
                                    });  
                                }
                                
                                
                                if(tree_level === "tree-level-group-resource")
                                {
                                   
                                    //=============[start] manage existing resource =========
                                    var dl_cntr_rs = 0;
                                    var is_resource_exist_rs = false;                                                                
                                    jq(scope.resourcesArr).each(function(i,obj){                                    
                                        if(obj.hasOwnProperty("RID") && obj.RID.toString() === new_item.RID.toString() )
                                        {                                                                
                                            dl_cntr_rs = i;
                                            is_resource_exist_rs = true;                                                                                                    
                                        }
                                    });
                                    
                                                //=== Delete 'No Found Resource' ====                                        
                                                if( scope.resourcesArr.length == 1)
                                                {
                                                    if(scope.resourcesArr[0].hasOwnProperty("title") && scope.resourcesArr[0].title === "No Record Found!")
                                                    {                                                        
                                                        dl_cntr_rs = 0;
                                                        scope.$apply(function () {
                                                            scope.resourcesArr[0] = new_item;
                                                        });  
                                                        //console.log( "==> " , scope.resources_of_current_collection );
                                                    }
                                                }
                                                
                                    if(is_resource_exist_rs)
                                    {                            
                                        scope.$apply(function () {
                                            scope.resourcesArr.splice(dl_cntr_rs, 1);                            
                                        });                                                        
                                    } 
                                                 
                                    //============= [start] setting order =============
                                    jq(scope.resourcesArr).each(function(i,obj){                                        
                                        if(obj.hasOwnProperty("RID") && obj.RID.toString() === rid_val_d.toString())
                                        {
                                            insert_cntr = (i+1);
                                        }
                                    });
                                    //============= [end] setting order =============
                                    
                                    
                                    
                                    
                                    console.log("resourcesArr B = " , scope.resourcesArr);
                                    scope.$apply(function (){
                                        scope.resourcesArr.splice(insert_cntr, 0, new_item);
                                    });
                                    console.log("resourcesArr A = " , scope.resourcesArr);
                                }
                                //===== [end] Insert ======         
                                
                                
                                jq('.isNew').each(function(i,obj){
                                    jq(obj).parent().addClass("new-droppable-resource");
                                });                                
                        }
                        else{
                            ui.draggable.draggable('option','revert',true);                                                                                    
                        }                        
                    }
                    
                    jq(event.target).parent().removeClass("droppable-target-underline");
                },
                over: function (event, ui) {                                        
                    jq(event.target).parent().addClass("droppable-target-underline");
                    
                    
                     scope.$apply(function (){
                        scope.set_resource_in_tree_height(jq(event.target).height());
                     });
                                                             
                     
                },
                out: function (event, ui) {                    
                    jq(event.target).parent().removeClass("droppable-target-underline");
                }
            });
          }
        }
    });
*/
ngappmodal.controller('libModalCtrl', ['$scope','$http',function($scope,$http){
        NProgress.configure({trickleRate: 0.01, trickleSpeed: 10});   
        
        $scope.resourcesArr = [];
        $scope.groupsArr = [];
        $scope.rid = 0;
        $scope.selected_collection = "";
        $scope.last_selected_collection = "";
        $scope.selected_resource = "";
        $scope.displayseqno_selected = "";
        $scope.resources_of_current_collection = [ {Resource:"Loading..."} ];
        $scope.resources_of_current_group = [ {Resource:"Loading..."} ];
        $scope.sort_by = "most_recent";        
        $scope.libraryTopTree = ["My Collections","My Groups"];
        $scope.libraryTopTreeSelectedValue = 'null';
        $scope.selected_group = 0;
        $scope.tree_level = "null";
        $scope.new_group_resource = null;
        $scope.selected_group_id = 0;
        
        $scope.resource_in_tree_height = 0;
        
        $scope.set_resource_in_tree_height = function(h){
            $scope.resource_in_tree_height = h;
        };
        
        $scope.set_new_group_resource = function(ngr)
        {            
            $scope.new_group_resource = ngr;            
        };
        
        $scope.setTreeLevel = function(tl)
        {            
            $scope.tree_level = tl;            
        };
        
        $scope.sortCollections = function(sort_by_val)
        {            
            $scope.sort_by = sort_by_val;
            $scope.resetVars();
            $scope.getCollections();
        };
        
        $scope.resetVars = function()
        {            
            $scope.resourcesArr = [];
            //$scope.rid = 0;
            $scope.selected_collection = "";            
            $scope.selected_resource = "";
            $scope.displayseqno_selected = "";
            $scope.resources_of_current_collection = [ {Resource:"Loading..."} ];      
            $scope.selected_group = 0;
        };
        
        $scope.onCancel = function()
        {            
            jq("#add-to-lib-dialog").hide();
            jq("#addtolibrary").show();  
            $scope.resetVars();
        };
        
        $scope.setCurrentCollection = function(rid_val)
        {
            $scope.resources_of_current_collection = [ {Resource:"Loading..."} ];
            $scope.selected_collection = rid_val;                        
            $scope.getCollectionResources();
        };
        
        $scope.setCurrentGroup = function(rid_val)
        {
            
            $scope.selected_collection = "";
            $scope.resourcesArr = [{Collection:"Loading..."}];
                   
            $scope.resources_of_current_collection = [ {Resource:"Loading..."} ];
            //$scope.selected_collection = rid_val;                        
            $scope.selected_group = rid_val;                        
            //$scope.getCollectionResources();
            $scope.getCollections();
        };
        
        $scope.setCurrentResource = function(rid_val , d_no)
        {
            $scope.selected_resource = rid_val;
            $scope.displayseqno_selected = d_no;
        };
        
        
        $scope.setTopTreeSelectedItem = function(item)
        {
            //console.log("item >> " , item);
            //jq(".items-wrapper").hide();
            $scope.libraryTopTreeSelectedValue = item;
            //jq("."+item.split(' ').join('-')+"-Items").show();                        
            if(item == "My Collections")
            {
               if(parseInt($scope.selected_group) > 0) 
               {
                   $scope.selected_group = 0;
                   $scope.resourcesArr = [];
               }
            }
            if(item == "My Groups")
            {
               if(parseInt($scope.selected_collection) > 0) 
               {
                   $scope.selected_collection = "";
                   $scope.resourcesArr = [];
               }
            }
            
            $scope.getCollections();
        };
        
        
      
        $scope.addToLibrary = function () {
           
            if($scope.selected_collection.toString().length > 0 || $scope.new_group_resource != null)
            {                
                NProgress.start();
                $http({
                  method: 'POST',
                  url: ajaxurl,
                  data: jq.param({'action': 'add_user_library_collection_resource', 'collectionid': $scope.selected_collection , 'resourceid':$scope.rid , 'selected_resource':$scope.selected_resource , 'displayseqno_selected':$scope.displayseqno_selected, 'new_group_resource':$scope.new_group_resource}),
                  headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}
                }).success(function (data) {                    
                    
                    //console.log("rtn = ",data);
                    
                    
                    $scope.last_selected_collection = $scope.selected_collection;                    
                    $scope.onCancel();
                    
                    jq("#add-to-lib-alert-box").show();
                    jq("#add-to-lib-alert-box").centerx();
                    
                   
                  NProgress.done();
                }).error(function (data) {
                  NProgress.done();            
                });
            }          
        }
        
        $scope.getCollectionResources = function () {
            
          $scope.resources_of_current_collection.length = 0;
          $scope.resources_of_current_collection = [ {Resource:"Loading..."} ];
          
          NProgress.start();          
          $http({
            method: 'POST',
            url: ajaxurl,
            data: jq.param({'action': 'get_user_library_collection_resources', 'rid': $scope.selected_collection,'selected_group':$scope.selected_group}), 
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}
          }).success(function (data) {
                $scope.resources_of_current_collection = [];
                if(data.length == 0)
                {
                    $scope.resources_of_current_collection = [ {Resource:"No Resource Found!",ColRid:0,RID:0,displayseqno:0} ];
                }else{
                    $scope.resources_of_current_collection = data;            
                }
                
                
            NProgress.done();
          }).error(function (data) {
            NProgress.done(); 
                $scope.resources_of_current_collection = [ {Resource:"Error Occured. Contact Administrator!"} ];
          });
        }
        
        $scope.getCollections = function () {
        
          $scope.resourcesArr = [];
          
          NProgress.start();          
          $http({
            method: 'POST',
            url: ajaxurl,
            data: jq.param({'action': 'get_user_library_collection', 'sort_by' : $scope.sort_by , 'libraryTopTreeSelectedValue':$scope.libraryTopTreeSelectedValue , 'selected_group':$scope.selected_group}), 
            headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}
          }).success(function (data) {              
            
            if($scope.libraryTopTreeSelectedValue === "My Collections")
            {
                $scope.resourcesArr = data;
                $scope.groupsArr = [];
            }
            else if(parseInt( $scope.selected_group.toString() ) === 0 && $scope.libraryTopTreeSelectedValue === "My Groups")
            {                
                $scope.groupsArr = data;
            }
            else if(parseInt( $scope.selected_group.toString() ) > 0 && $scope.libraryTopTreeSelectedValue === "My Groups")
            {                
                if( data.length == 0)
                {
                    $scope.resourcesArr = [{"RID":"0","Collection":"No Record Found!","title":"No Record Found!","displayseqno":"0","groupid":"0","type":"resource"}];
                }else{
                    $scope.resourcesArr = data;
                }                
            }
            
            NProgress.done();
          }).error(function (data) {
            NProgress.done();            
          });
        }
        
}]);
