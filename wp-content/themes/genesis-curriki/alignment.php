<?php
/*
* Template Name: Alignment
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Tahir Mustafa
* Url: http://curriki.com/
*/
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/alignment/ext-all.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/alignment/tree.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/alignment/lists.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/alignment/custom_style.css" />

<script type="text/javascript">
    var states_gobal_arr = [];        
    var states_removed_existing_arr = [];        
    var un_matched_elements_inner_arr = [];        
</script>

<style type="text/css">
	p {
		margin:5px;
	}
	.settings {
		background-image:url(alignment/images/folder_wrench.png);
	}
	.nav {
		background-image:url(alignment/images/folder_go.png);
	}
	.info {
		background-image:url(alignment/images/information.png);
	}
	.pointer { cursor: pointer; }
	.green-button {
		background-color: #a5c546;
		border: 1px solid #a5c546;
		color: #ffffff;
		border-radius: 8px;
		cursor: pointer;
		font-family: "proxima-nova",sans-serif;
		font-size: 1em;
		font-weight: 500;
		padding: 7px;
		text-align: center;
		text-transform: none;
		width: 180px;
		
	}
	.green-button:hover {
		background-color: #7da941;
		border: 1px solid #7da941;
	}
        #panel-1019 .x-panel-body
        {
            background-color: #B7B7BA !important;
        }
        #selectStatements
        {
            width: 270px !important;
        }
</style>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/ext-all-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-sortable.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/function.js"></script>


</head>
<body  class="cgcs">

<div id="north" class="x-hide-display">
<?php
	$res = new CurrikiResources();
	//$result = $res->getStatements(100);
	//print_r($result);die;
	
	$jurisdictions = $res->getJurisdiction();
	
	$count = 0;
	echo "<br />&nbsp;&nbsp;&nbsp;<select name='selectStatements' id='selectStatements'><option value=''>Choose a standard then select Show</option>";
	foreach($jurisdictions AS $name => $group)
	{
		if($count)
			echo "</optgroup><optgroup label='$name'>";
		else
			echo "<optgroup label='$name'>";
		
		foreach($group AS $id => $title)
			echo "<option value='$id'>$title</option>";
		
		$count++;
	}
	echo "</optgroup></select>";
	echo '&nbsp;&nbsp;<button class="green-button" onclick="display_Statements();">'.__('Show Standard','curriki').'</button>';
?>
    <button id="save_btn" class="green-button" onclick="save_Statements();" style="float: right;margin-right: 10px;"><?php echo __('Save','curriki'); ?></button>
</div>
<div class="clear"></div>
<div id="west" class="x-hide-display">
<div class="tree well" id="statements"></div>
</div>

<div id="east" class="x-hide-display">
<div class="tree well">
<ul class="nested_with_switch vertical associatedContainer" style="min-height:200px;">


<?php
	$associatedStatements = $res->getAssociatedStatements((int) $_GET['rid']);
	if($associatedStatements) {
		foreach($associatedStatements AS $statement) {
			echo '<li state="'.$statement['statementid'].'"><span class="pointer" onclick="display_Meta('.$statement['statementid'].');"><i class="icon-move"></i> '.$statement['description'].'</span></li>';
		}
	}
?>
</ul>
</div>
</div>
<div class="clear"></div>
<div id="south" class="x-hide-display"></div>

</body>
    <script>
        /************************** Panel ********************/
        Ext.require(['*']);

        Ext.onReady(function () {

            Ext.QuickTips.init();
            Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));

            var viewport = Ext.create('Ext.Viewport', {
                id: 'border-example',
                layout: 'border',
                items: [
                    {
                        // lazily created panel (xtype:'panel' is default)
                        region: 'north',
                        contentEl: 'north',
                        split: false,
                        height: 100,
                        minSize: 100,
                        maxSize: 100,
                        collapsible: true,
                        collapsed: false,
                        title: '<?php echo __('Standards','curriki');?>',
                        autoScroll: false,
                        margins: '0 0 0 0'
                    }, {
                        // lazily created panel (xtype:'panel' is default)
                        region: 'south',
                        contentEl: 'south',
                        split: true,
                        height: 250,
                        minSize: 100,
                        maxSize: 200,
                        collapsible: true,
                        collapsed: false,
                        title: '<?php echo __('Meta Data: Click on any Statement above to Show','curriki'); ?>',
                        autoScroll: true,
                        margins: '0 0 0 0'
                    }, {
                        // lazily created panel (xtype:'panel' is default)
                        region: 'west',
                        contentEl: 'west',
                        split: true,
                        height: 100,
                        width: 500,
                        minSize: 500,
                        maxSize: 500,
                        collapsible: true,
                        collapsed: false,
                        title: '<?php echo __('Statements','curriki'); ?>',
                        autoScroll: true,
                        margins: '0 0 0 0'
                    }, {
                        // lazily created panel (xtype:'panel' is default)
                        region: 'center',
                        contentEl: 'east',
                        split: true,
                        height: 0,
                        width: 0,
                        minSize: 0,
                        maxSize: 0,
                        collapsible: true,
                        collapsed: false,
                        title: '<?php echo __('Selected Statements','curriki'); ?>',
                        autoScroll: true,
                        margins: '0 0 0 0'
                    }]
            });
            
            //console.log( "==> " , viewport.items.items );            
            //$("#panel-1018").append('<p id="infomsg">Drag and Drop individual Standards between columns to Add or Remove</p>');
            $("#panel-1018").after('<p id="infomsg"><?php echo __('Drag and Drop Statements from Left to the Right Column','curriki'); ?></p>');
        });

        $(function () {
            treeLoading();
        });
		
		function treeLoading() {
			/************************** Tree ********************/
            $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
            $('.tree li.parent_li > span').on('click', function (e) {
                var children = $(this).parent('li.parent_li').find(' > ul > li');
                if (children.is(":visible")) {
                    children.hide('fast');
                    $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
                } else {
                    children.show('fast');
                    $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
                }
                e.stopPropagation();
            });
            /************************** Sortable********************/
            var oldContainer
            $("ul.nested_with_switch").sortable({
                group: 'nested',
                handle: 'i.icon-move',
                afterMove: function (placeholder, container) {
                    if (oldContainer != container) {
                        if (oldContainer)
                            oldContainer.el.removeClass("active")
                        container.el.addClass("active")

                        oldContainer = container
                    }
                },
                onDrop: function (item, container, _super) {
                    container.el.removeClass("active")
                    _super(item);
					
					if(container.target.hasClass("associatedContainer"))
						save_Statement(item.attr('state'));
					else
						remove_Statement(item.attr('state'));
                },
                start: function(event, ui) {
                    
                    var state_id =  $(ui.item[0]).attr("state");                    
                    if( $.inArray(state_id , window.states_gobal_arr ) > -1 )
                    {                        
                        //console.log("++++ event = " , event );
                        //console.log("++++ ui = " , ui );
                    }
                    
                },
                receive: function (event, ui) {
                    console.log( 'event', event );
                    console.log( 'ui' , $(ui.item[0]).attr("state") );
                    var state_id =  $(ui.item[0]).attr("state");
                    if( $.inArray(state_id , window.states_gobal_arr ) > -1 )
                    {
                        alert("Standard Already Selected!");
                        /*console.log("*** event = " , event );
                        console.log("*** event > source element > id = " , event.srcElement.id );
                        console.log("*** THIS = " , this );
                        $(this).sortable('cancel'); */
                        ui.item.remove();
                    }else{
                        window.states_gobal_arr.push(state_id);
                        
                        var index_rm = window.states_removed_existing_arr.indexOf(state_id);
                        if ( index_rm > -1) 
                        {
                            window.states_removed_existing_arr.splice(index_rm, 1);
                        }
                        
                        console.log( "global arr = " , window.states_gobal_arr );
                        console.log( "states_removed_existing_arr (receive)=> " , window.states_removed_existing_arr );
                        console.log( "states_gobal_arr_initial (receive)=> " , $("#states_gobal_arr_initial").val() );
                    }
                    /*
                    container.el.removeClass("active")
                    _super(item);
					
					if(container.target.hasClass("associatedContainer"))
						save_Statement(item.attr('state'));
					else
						remove_Statement(item.attr('state'));
                    */
                }
            });
		}
		
		
		function save_Statement(id) {
			$.get("/curriki/alignment/?savestatement=true&sid="+id+"&rid=<?php echo $_GET['rid']; ?>");
			/*$.get("/curriki2/save-statement/<?php echo $resourceid; ?>/<?php echo $standardid; ?>/"+id+"");*/
		}
		
		function remove_Statement(id) {
			$.get("/curriki/alignment/?removestatement=true&sid="+id+"&rid=<?php echo $_GET['rid']; ?>");
			/*$.get("/curriki2/remove-statement/<?php echo $resourceid; ?>/<?php echo $standardid; ?>/"+id+"");*/
		}
		
		function display_Meta(id) {
			
			$.ajax({url:"/curriki/alignment/?showmeta=true&id="+id+"&sid="+$("select#selectStatements").val(),success:function(result){
					$("#south").html(result);
				}});
		}
		
		function display_Statements() {
			
			var selectStatements = $("#selectStatements").val();
			
			if(selectStatements == '')
			{
				alert('Please Select Standard');
			}
			else
			{
				$("#statements").html('');
				$.ajax({url:"/curriki/alignment/?showstatements=true&statement="+selectStatements+"",success:function(result){
					$("#statements").html(result);
					treeLoading();
					
					$('#west ul').each(function(i, val) {  $(this).addClass('dlist'+i); });
					$('#west ul ul li').each(function(i, val) {  cl = $(this).parent('ul').attr('class'); $(this).attr('parent',cl);  });
					$('#west li').each(function(i, val) { if($(this).find('ul').length == 0) $(this).addClass('westdrag'); else $(this).wrapInner('<span></span>'); });
					$('.westdrag').draggable({
					  revert: "invalid", 
					  connectToSortable: "#east ul",
					  cursor: "move"
					});
					
				}});                                                                
			}
		}
                
                
                function save_Statements() {
                
                    var selectStatements = $("#selectStatements").val();

                    if(selectStatements == '')
                    {
                        alert('Please Select Standard');
                    }
                    else
                    {
                       $("#save_btn").css("opacity","0.5");
                       $("#save_btn").text("Saving ....");
                       var ajaxurl = "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>";                       
                       $.ajax({
                                url: ajaxurl,
                                method : "POST",
                                data : { action:'save_statements' , sate_ids : window.states_gobal_arr , rid : '<?php echo $_GET['rid']; ?>' }
                            }).done(function(data) {
                                //console.log( "data => " , data );
                                $("#save_btn").css("opacity","1");
                                $("#save_btn").text("Save");
                                $("#add-to-lib-alert").show();
                                                                                                
                                window.states_removed_existing_arr = [];                                                                                                                                
                                window.parent.un_matched_elements = [];
                                $("#states_gobal_arr_initial").val( JSON.stringify(window.states_gobal_arr) );                                
                                
                            });
                    }
                }
                
                
                $(document).ready(function(){
                    $(".nested_with_switch li").each(function(i,obj){
                        console.log( "state" , $(obj).attr('state') );
                        window.states_gobal_arr.push( $(obj).attr('state') );
                    });
                    
                    console.log( "states_gobal_arr = " , window.states_gobal_arr );
                    
                    $("#states_gobal_arr_initial").val( JSON.stringify(window.states_gobal_arr) );
                    console.log( "states_gobal_arr_initial => " , $("#states_gobal_arr_initial").val() );
                    
                    $(".close-add-to-lib-alert").on("click",function(){            
                        $("#add-to-lib-alert").hide();
                    });                    
                    
                    //console.log((window.parent.document));
                    //console.log("fancybox => ", $(window.parent.document).find(".fancybox"));
                  
                    $(document).on("click",".no-close-confirm-alert",function(){
                        $("#close-confirm-alert").hide();
                    });
                    
                    
                    $(document).on("click",".yes-close-confirm-alert",function(){
                        $("#close-confirm-alert").hide();
                    });
                });
                
    </script>
    <style type="text/css">
        #infomsg
        {
            margin-top: 100px !important;
            border: 0px solid red !important;
            color: #a5c546 !important;
            font-weight: bold !important;
        }
        #header-1034,#header-1038
        {
            margin-top: 25px !important;            
        }
        
        #add-to-lib-alert{
            width: 680px !important;
            border-radius: 8px !important;
            border: 1px solid #D1D1D1 !important;
            background-color: #fff !important;
            color: #333 !important;
            overflow-y: hidden !important; 
            overflow-x: hidden !important; 
            text-align: center !important;
            padding: 20px;
            
        }
        #add-to-lib-alert button{
            width: 150px !important;
            float: none !important;
        }
        
        #close-confirm-alert{
            width: 680px !important;
            border-radius: 8px !important;
            border: 1px solid #D1D1D1 !important;
            background-color: #fff !important;
            color: #333 !important;
            overflow-y: hidden !important; 
            overflow-x: hidden !important; 
            text-align: center !important;
            padding: 20px;
            
        }
        #close-confirm-alert button{
            width: 150px !important;
            float: none !important;
        }
        
        .modal-title {
            text-align: center !important;
            font-weight: 500 !important;
            font-family: 'Museo', sans-serif;
            color: #106F8E !important;
        }
        .button-cancel
        {
            background-color: #a5c546;
            border: 1px solid #a5c546;
            color: #ffffff;
            border-radius: 8px;
            cursor: pointer;
            font-family: "proxima-nova",sans-serif;
            font-size: 1em;
            font-weight: 500;
            padding: 7px;
            text-align: center;
            text-transform: none;
            width: 180px;
        }
        
        .modal{            
            top: 70% !important;
            left: 42% !important;
        }
        
        .my-library-actions
        {
            text-align: center !important;
        }
    </style>
    
    <input type="hidden" name="states_gobal_arr_initial" id="states_gobal_arr_initial" value="-1" />
    
    <div id="add-to-lib-alert" class="my-library-modal modal border-grey rounded-borders-full grid_6" style="display: none;">
        <h3 class="modal-title">Statements Saved!</h3>
            <div class="grid_8 center">
                <div style="margin: 0 auto;">
                    <p>
                        Selected statements has been saved
                    </p>                
                </div>
                <div class="my-library-actions" style="margin: 0 auto;">                    
                <!--  <button class="button-save" id="continue_adding_btn">Continue Adding  >> </button>
                    <button class="button-save collid-{{last_selected_collection}}" id="go_to_collection_btn">Go to Selected Collection !</button>
                    <button class="button-save" id="go_to_lib_btn">Go to Library !</button> -->
                    <button class="button-cancel close-add-to-lib-alert">Close</button>
                </div>
            </div>
        <div class="close close-add-to-lib-alert"><span class="fa fa-close"></span></div>
        <input type="hidden" name="base_url" id="base_url" value="<?php echo  get_site_url(); ?>" />
    </div>
    
    
    <div id="close-confirm-alert" class="my-library-modal modal border-grey rounded-borders-full grid_6" style="display: none;">
        <h3 class="modal-title">Do you want save changes!</h3>
            <div class="grid_8 center">
                <div style="margin: 0 auto;">
                    <p>                        
                    </p>                
                </div>
                <div class="my-library-actions" style="margin: 0 auto;">                    
                <!--  <button class="button-save" id="continue_adding_btn">Continue Adding  >> </button>
                    <button class="button-save collid-{{last_selected_collection}}" id="go_to_collection_btn">Go to Selected Collection !</button>
                    <button class="button-save" id="go_to_lib_btn">Go to Library !</button> -->
                    <button class="button-cancel yes-close-confirm-alert">Go to Save</button>
                    <button class="button-cancel no-close-confirm-alert">No</button>
                </div>
            </div>
        <div class="close close-add-to-lib-alert"><span class="fa fa-close"></span></div>
        <input type="hidden" name="base_url_cls" id="base_url" value="<?php echo  get_site_url(); ?>" />
    </div>
    
</html>
