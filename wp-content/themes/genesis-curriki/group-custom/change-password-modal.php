<?php
/*
 Author: Waqar Muneer
 */
?>

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() . '/css/nprogress.css'; ?>" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri() . '/js/nprogress.js'; ?>"></script>

<script type="text/javascript">
    
var pwdapp = angular.module('ngpwdapp', []);

pwdapp.controller('pwdModalCtrl', ['$scope','$http',function($scope,$http){
        console.log(NProgress);
    NProgress.configure({trickleRate: 0.01, trickleSpeed: 10});
    
    $scope.newpassword = "";
    $scope.confirmpassword = "";
    $scope.show_error = false;
    
    $scope.onClose = function(){
        $scope.newpassword = "";
        $scope.confirmpassword = "";
        $scope.show_error = false;
        jq("#change-password-modal").hide();
    }
    
    $scope.savepassword = function(){
        if( !($scope.newpassword.length > 0 && $scope.confirmpassword.length > 0 && $scope.newpassword == $scope.confirmpassword && $scope.newpassword.length >= 5) )
        {     
            jq(".message_para").text("Invalid Password Confirmation or Password length !");
            jq(".message_para").addClass("error_para");            
            $scope.show_error = true;
        }else
        {
            $scope.show_error = false;
            jq(".message_para").removeClass("error_para");            
            
            NProgress.start();
                $http({
                  method: 'POST',
                  url: ajaxurl,
                  data: jq.param({'action': 'profile_password_change', 'newpassword': $scope.newpassword , 'confirmpassword':$scope.confirmpassword}),
                  headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}
                }).success(function (data) {                                                                                
                    jq(".message_para").text(data.message);
                    $scope.show_error = true;
                    $scope.newpassword = "";
                    $scope.confirmpassword = "";
                    
                    var cntr = 5;
                    setInterval(function(){                         
                        jq(".message_para").text(data.message+" You will be redirected to login again after "+cntr+" seconds.");
                        if(cntr == 1)
                        {
                            window.location = "<?php echo site_url() ?>/?modal=login";
                        }
                        cntr--;
                    }, 1000);
                    
                    //window.location = "<?php //echo site_url() ?>";
                    
                  NProgress.done();
                }).error(function (data) {                    
                  NProgress.done();            
                });                
        }
    };
    
}]);

</script>

<script type="text/javascript">
  jq("#change-password-link").on("click",function(){
        jq("#change-password-modal").show();
        jq("#change-password-modal").centerx();
        jq("#change-password-modal").css("z-index", "5");
  });
  
  function change_password_modal(rid)
  {
  rid = rid || "";
          rid_param = 0;
          if (rid.length > 0)
  {
  rid_arr = rid.split("-");
          rid_param = parseInt(rid_arr[1]);
  }

  jq("#change-password-modal").show();
          jq("#change-password-modal").centerx();
          jq("#change-password-modal").css("z-index", "5");
    if (angular.element(jq("#app-container")).scope() == undefined)
  {
  angular.bootstrap(jq("#app-container"), ['pwdapp']);
  }

  var scope_app = angular.element(jq("#app-container")).scope();
          if (rid_param > 0)
  {
  scope_app.$apply(function () {
  scope_app.rid = rid_param;
  });
  }
  scope_app.getCollections();
  }


  //*******************************
  var add_to_lib_pre_call_end = false;
          jq(document).ready(function () {

  jq("#continue_adding_btn").on("click", function () {
  jq("#change-password-alert-box").hide();
          change_password_modal();
  });
          jq(".close-change-password-alert-box").on("click", function () {
  jq("#change-password-alert-box").hide();
  });
          jq("#go_to_lib_btn").on("click", function () {
  var lib_url = jq("#base_url").val() + "/my-library";
          window.location = lib_url;
  });
          jq(".change-password-close-btn").on("click", function () {
  jq("#change-password-modal").hide();
          jq("#changepassword").show();
  });
          jq("#changepassword").on("click", function () {
  add_to_lib_pre_call_end = true;
  });
          jq(document).on('click', '.change-passwordrary', function () {
  add_to_lib_pre_call_end = true;
  });
          jq(document).ajaxComplete(function () {
  if (add_to_lib_pre_call_end == true)
  {
  change_password_modal();
          add_to_lib_pre_call_end = false;
  }
  });
          jq.fn.centerx = function () {
          var h = jQuery(this).height();
                  var w = jQuery(this).width();
                  var wh = jQuery(window).height();
                  var ww = jQuery(window).width();
                  var wst = jQuery(window).scrollTop();
                  var wsl = jQuery(window).scrollLeft();
                  this.css("position", "absolute");
                  var $top = Math.round((wh - h) / 2 + wst);
                  var $left = Math.round((ww - w) / 2 + wsl);
                  this.css("top", $top + "px");
                  this.css("left", ($left - 30) + "px");
                  return this;
          }

  //*************************
  var element = jQuery('#change-password-modal'),
          originalY = element.offset().top;
          // Space between element and top of screen (when scrolling)
          var topMargin = 100;
          // Should probably be set in CSS; but here just for emphasis
          element.css('position', 'relative');
          jQuery(window).on('scroll', function (event) {
  var scrollTop = jQuery(window).scrollTop();
          element.stop(false, false).animate({
  top: scrollTop < originalY
          ? 0
          : scrollTop - originalY + topMargin
  }, 300);
  });
          //**************************
          jq("#go_to_collection_btn").on("click", function () {
  var cls = jq(this).attr("class").split(" ");
          var collid_cls = cls[cls.length - 1];
          var collid_arr = collid_cls.split("-");
          var collid = collid_arr[1];
          //console.log( collid_cls , collid );
          window.location = jq("#base_url").val().trim() + "/oer/?rid=" + collid;
  });
  });</script>

<style type="text/css">
  #change-password-alert-box{width: 680px !important;}
  #change-password-alert-box button{width: 150px !important;float: none !important;}

  .mark-blod{font-weight: bold !important;}
  .mark-unblod{font-weight: normal !important;}

  .selected-resource{background: none repeat scroll 0 0 #99c736 !important;color: #ffffff !important;}
  .selected-resource:hover{background: none repeat scroll 0 0 #99c736 !important;color: #ffffff !important;}

  .selected-resource .fa-li{color: gray !important;}
  .my-library-folders ul li{cursor: pointer;}
  .change-password-form-wrapper
  {      
      width: 458px;
  }
  .change-password-form-wrapper input.text-box
  {
      width: 455px !important;
  }
</style>

<div id="app-container" ng-app="ngpwdapp" ng-controller="pwdModalCtrl">

  <div id="change-password-modal" class="my-library-modal modal border-grey rounded-borders-full grid_8" style="display: none;">

    <h3 class="modal-title">Change Password</h3>
    
    <div class="grid_8 change-password-form-wrapper">
                    <div class="signup-form">
                        <form method="post" id="loginform">
                            <p class="message_para" ng-show="show_error">Invalid Password Confirmation or Password length !</p>
                            <input class="text-box" type="password" name="newpassword" ng-model="newpassword" placeholder="New Password (minimum 5 characters)" />
                            <input class="text-box" type="password" name="confirmpassword" ng-model="confirmpassword" placeholder="Confrim Password" />
                        </form>
                    </div>
      <div class="my-library-actions">
        <button class="button-cancel" ng-click="onClose()">Close</button>
        <button class="button-save" ng-class="{'btn-disabled': !(newpassword.length > 0 && confirmpassword.length > 0 && newpassword == confirmpassword && newpassword.length >= 5) }" ng-click="savepassword()">Save</button>
      </div>
    </div>
    <div class="close" ng-click="onClose()"><span class="fa fa-close change-password-close-btn"></span></div>    
  </div>

  <div id="change-password-alert-box" class="my-library-modal modal border-grey rounded-borders-full grid_6" style="display: none;">
    <h3 class="modal-title">Resource Added!</h3>
    <div class="grid_8 center">
      <div style="margin: 0 auto;">
        <p>
          The resource has been added to your collection
        </p>                
      </div>

      <div class="my-library-actions" style="margin: 0 auto;">
        <!--<button class="button-cancel">Don't Delete</button>-->
        <button class="button-save" id="continue_adding_btn">Continue Adding  >> </button>
        <button class="button-save collid-{{last_selected_collection}}" id="go_to_collection_btn" style="width: 180px !important;">Go to Selected Collection !</button>
        <button class="button-save" id="go_to_lib_btn">Go to Library !</button>                                    
        <button class="button-cancel close-change-password-alert-box">Close</button>
      </div>
    </div>
    <div class="close close-change-password-alert-box"><span class="fa fa-close"></span></div>
    <input type="hidden" name="base_url" id="base_url" value="<?php echo get_site_url(); ?>" />
  </div>
</div>