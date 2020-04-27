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
</style>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/ext-all-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-sortable.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/alignment/function.js"></script>

</head>
<body>

<div id="north" class="x-hide-display">
<?php
	$res = new CurrikiResources();
	//$result = $res->getStatements(100);
	//print_r($result);die;
	
	$jurisdictions = $res->getJurisdiction();
	
	$count = 0;
	echo "<br />&nbsp;&nbsp;&nbsp;<select name='selectStatements' id='selectStatements'><option value=''>Please Select Jurisdiction</option>";
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
	echo '&nbsp;&nbsp;<button class="green-button" onclick="display_Statements();">Submit</button>';
?>
</div>

<div id="west" class="x-hide-display">
<div class="tree well" id="statements"></div>
</div>

<div id="east" class="x-hide-display">
<div class="tree well">
<ul class="nested_with_switch vertical associatedContainer">
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
                        title: 'Jurisdictions',
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
                        title: 'Meta Data',
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
                        title: 'Statements',
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
                        title: 'Associated Statements',
                        autoScroll: true,
                        margins: '0 0 0 0'
                    }]
            });
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
			
			$.ajax({url:"/curriki/alignment/?showmeta=true&id="+id+"",success:function(result){
					$("#south").html(result);
				}});
		}
		
		function display_Statements() {
			
			var selectStatements = $("#selectStatements").val();
			
			if(selectStatements == '')
			{
				alert('Please select a Jurisdiction first.');
			}
			else
			{
				$("#statements").html('');
				$.ajax({url:"/alignment/?showstatements=true&statement="+selectStatements+"",success:function(result){
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
    </script>
</html>