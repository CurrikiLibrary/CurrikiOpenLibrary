jQuery(document).ready(function($) {   
	$('input[type="radio"]').click(function(){
		if($(this).attr("value")=="default-skin"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="default-skin-preview" class="social1" style="display: inline-block;margin-top:px;"><a href="#" id="facebookskin1" class="facebook" title="Facebook" name="facebook"></a><a href="#" id="twitterskin1" class="twitter" title="Twitter" name="twitter"></a><a href="#" id="g-plusskin1" class="g-plus" title="Google+" name="g-plus"></a><a href="#" id="linkedinskin1" class="linkedin" title="LinkedIn" name="linkedin"></a><a href="#" id="pinterestskin1" title="Pinterest" class="pinterest" name="pinterest"></a></div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="social-wide"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="social-wide-preview" class="social1 " style="display: inline-block;"><a href="#" id="facebookskin2" class="facebook" title="Facebook" name="facebook"></a><a href="#" id="twitterskin2" class="twitter" title="Twitter" name="twitter"></a><a href="#" id="g-plusskin2" class="g-plus" title="Google+" name="g-plus"></a><a href="#" id="linkedinskin2" class="linkedin" title="LinkedIn" name="linkedin"></a><a href="#" id="pinterestskin2" class="pinterest" title="Pinterest" name="pinterest"></a></div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="bounce-up"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="bounce-up-preview" class="" style="display: inline-block;"><div class="social1"><a href="" id="facebookskin3" target="_blank" name="facebook"><div class="social--facebook"></div></a><a href="" id="twitterskin3" target="_blank" name="twitter"><div class="social--twitter"></div></a><a href="" id="g-plusskin3"  target="_blank" name="g-plus"> <div class="social--google"></div></a><a href="" id="linkedinskin3"  target="_blank" name="linkedin"><div class="social--linkedin"></div></a><a href=""  id="pinterestskin3" target="_blank" name="pinterest"><div class="social--pinterest"></div></a></div></div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="grind-in"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="grind-in-preview" class="" style="display: inline-block;"> <div class="social_icons"> <a class="btn_facebook" name="facebook"><i class="fa fa-facebook"></i><i class="fa fa-facebook"></i></a> <a class="btn_twitter" name="twitter"><i class="fa fa-twitter"></i><i class="fa fa-twitter"></i></a> <a class="btn_google-plus" name="google"><i class="fa fa-google-plus"></i><i class="fa fa-google-plus"></i></a> <a class="btn_linkedin" name="linkedin"><i class="fa fa-linkedin"></i><i class="fa fa-linkedin"></i></a> <a class="btn_pinterest" name="pinterest"><i class="fa fa-pinterest" ></i><i class="fa fa-pinterest"></i></a> </div></div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			console.log(selectedSocialNetworkArray);
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="paper-fold"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="paper-fold-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme5" class=""> <nav> <ul> <li id="facebookskin5" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme5-facebook hvr-wobble-vertical"></span> </a> </div> </li> <li id="twitterskin5" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme5-twitter hvr-wobble-vertical"></span> </a> </div> </li> <li id="g-plusskin5" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme5-gplus hvr-wobble-vertical"></span> </a> </div> </li> <li id="linkedinskin5" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme5-linkedin hvr-wobble-vertical"></span> </a> </div> </li> <li id="pinterestskin5" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme5-pinterest hvr-wobble-vertical"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="branded"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML =' <div id="branded-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme6" class=""> <nav> <ul> <li id="facebookskin6" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme6-facebook hvr-outline-infacebook"></span> </a> </div> </li> <li id="twitterskin6" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme6-twitter hvr-outline-intwitter"></span> </a> </div> </li> <li id="g-plusskin6" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme6-gplus hvr-outline-ingplus"></span> </a> </div> </li> <li id="linkedinskin6" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme6-linkedin hvr-outline-inlinkedin"></span> </a> </div> </li> <li id="pinterestskin6" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme6-pinterest hvr-outline-inpinterest"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="radiused"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="radiused-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme7" class=""> <nav> <ul> <li id="facebookskin7" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme7-facebook rotatefacebook"></span> </a> </div> </li> <li id="twitterskin7" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme7-twitter rotatetwitter"></span> </a> </div> </li> <li id="g-plusskin7" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme7-gplus rotategplus"></span> </a> </div> </li> <li id="linkedinskin7" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme7-linkedin rotatelinkedin"></span> </a> </div> </li> <li id="pinterestskin7" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme7-pinterest rotatepinterest"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="octagon"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="octagon-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme8" class=""> <nav> <ul> <li id="facebookskin8" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme8-facebook hvr-float-shadow"></span> </a> </div> </li> <li id="twitterskin8" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme8-twitter hvr-float-shadow"></span> </a> </div> </li> <li id="g-plusskin8" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme8-gplus hvr-float-shadow"></span> </a> </div> </li> <li id="linkedinskin8" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme8-linkedin hvr-float-shadow"></span> </a> </div> </li> <li id="pinterestskin8" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme8-pinterest hvr-float-shadow"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="hanging"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="hanging-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme9" class=""> <nav> <ul> <li id="facebookskin9" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme9-facebook hvr-wobble-bottom"></span> </a> </div> </li> <li id="twitterskin9" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme9-twitter hvr-wobble-bottom"></span> </a> </div> </li> <li id="g-plusskin9" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme9-gplus hvr-wobble-bottom"></span> </a> </div> </li> <li id="linkedinskin9" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme9-linkedin hvr-wobble-bottom"></span> </a> </div> </li> <li id="pinterestskin9" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme9-pinterest hvr-wobble-bottom"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="tricon"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="tricon-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme10" class=""> <nav> <ul> <li id="facebookskin10" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme10-facebook hvr-buzz-out"></span> </a> </div> </li> <li id="twitterskin10" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme10-twitter hvr-buzz-out"></span> </a> </div> </li> <li id="g-plusskin10" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme10-gplus hvr-buzz-out"></span> </a> </div> </li> <li id="linkedinskin10" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme10-linkedin hvr-buzz-out"></span> </a> </div> </li> <li id="pinterestskin10" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme10-pinterest hvr-buzz-out"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="hollow"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="hollow-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme11" class=""> <nav> <ul> <li id="facebookskin11" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme11-facebook rotatefacebook"></span> </a> </div> </li> <li id="twitterskin11" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme11-twitter rotatetwitter"></span> </a> </div> </li> <li id="g-plusskin11" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme11-gplus rotategplus"></span> </a> </div> </li> <li id="linkedinskin11" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme11-linkedin rotatelinkedin"></span> </a> </div> </li> <li id="pinterestskin11" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme11-pinterest rotatepinterest"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}

		}
		if($(this).attr("value")=="sociallambs"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="sociallambs-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme12" class=""> <nav> <ul> <li id="facebookskin12" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme12-facebook hvr-pop"></span> </a> </div> </li> <li id="twitterskin12" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme12-twitter hvr-pop"></span> </a> </div> </li> <li id="g-plusskin12" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme12-gplus hvr-pop"></span> </a> </div> </li> <li id="linkedinskin12" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme12-linkedin hvr-pop"></span> </a> </div> </li> <li id="pinterestskin12" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme12-pinterest hvr-pop"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="3dicons"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="threedicons-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme17" class=""> <nav> <ul> <li id="facebookskin13" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme17-facebook hvr-grow-rotate"></span> </a> </div> </li> <li id="twitterskin13" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme17-twitter hvr-grow-rotate"></span> </a> </div> </li> <li id="g-plusskin13" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme17-gplus hvr-grow-rotate"></span> </a> </div> </li> <li id="linkedinskin13" name="linkedin"> <div> <a href="#"> <span class="wpsocialarrow-theme17-linkedin hvr-grow-rotate"></span> </a> </div> </li> <li id="pinterestskin13" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme17-pinterest hvr-grow-rotate"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				$("#threedicons-preview").find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				$("#threedicons-preview").find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				$("#threedicons-preview").find('[name=google]').hide();
				$("#threedicons-preview").find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				$("#threedicons-preview").find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				$("#threedicons-preview").find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="whitestitchedborder"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML =' <div id="whitestitchedborder-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme14" class=""> <nav> <ul> <li id="facebookskin14" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme14-facebook hvr-wobble-skew"></span> </a> </div> </li> <li id="twitterskin14" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme14-twitter hvr-wobble-skew"></span> </a> </div> </li> <li id="g-plusskin14" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme14-gplus hvr-wobble-skew"></span> </a> </div> </li> <li id="linkedinskin14" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme14-linkedin hvr-wobble-skew"></span> </a> </div> </li> <li id="pinterestskin14" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme14-pinterest hvr-wobble-skew"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}
		if($(this).attr("value")=="whiterounded"){
			document.getElementById("wpsocialarrow-selected-skin").value =this.id;
			document.getElementById("preview-parent").innerHTML ='<div id="whiterounded-preview" class="" style="display: inline-block;"> <div id="wpsocialarrow-theme15" class=""> <nav> <ul> <li id="facebookskin15" name="facebook"> <div> <a href="#"> <span class="wpsocialarrow-theme15-facebook hvr-bounce-out"></span> </a> </div> </li> <li id="twitterskin15" name="twitter"> <div > <a href="#"> <span class="wpsocialarrow-theme15-twitter hvr-bounce-out"></span> </a> </div> </li> <li id="g-plusskin15" name="g-plus"> <div > <a href="#"> <span class="wpsocialarrow-theme15-gplus hvr-bounce-out"></span> </a> </div> </li> <li id="linkedinskin15" name="linkedin"> <div > <a href="#"> <span class="wpsocialarrow-theme15-linkedin hvr-bounce-out"></span> </a> </div> </li> <li id="pinterestskin15" name="pinterest"> <div > <a href="#"> <span class="wpsocialarrow-theme15-pinterest hvr-bounce-out"></span> </a> </div> </li> </ul> </nav> </div> </div>';
			var selectedSocialNetworkArray = new Array();
			$("input[name=wpsocialarrow-selcetion-network]").each(function() {
				selectedSocialNetworkArray.push($(this).val());
			});
			if ( selectedSocialNetworkArray[0] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=facebook]').hide();
			}
			if ( selectedSocialNetworkArray[1] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=twitter]').hide();
			}
			if ( selectedSocialNetworkArray[2] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=google]').hide();
				$("#"+selectedskin).find('[name=g-plus]').hide();
			}
			if ( selectedSocialNetworkArray[3] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=linkedin]').hide();
			}
			if ( selectedSocialNetworkArray[4] == "" ) {
				var selectedskin = document.getElementById("wpsocialarrow-selected-skin").value+"-preview";
				$("#"+selectedskin).find('[name=pinterest]').hide();
			}
		}

	});
});