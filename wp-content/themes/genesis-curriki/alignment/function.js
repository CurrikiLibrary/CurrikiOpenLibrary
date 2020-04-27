// JavaScript Document
$(function() {
	
	  $( "#east ul" ).sortable({  revert: true });
	  $('.westdrag').draggable({
		  revert: "invalid", 
		  connectToSortable: "#east ul",
		  cursor: "move"
		});
		
$('#west').droppable({
		  accept: "#east li", 
		  drop: dropfunction
		});
	
	function dropfunction( event, ui ) {
            
              
            
                //console.log("===> Dropped back = ", $(ui.draggable[0]).attr("state") );
		vs = $(this).html();
		attr = ui.draggable.attr('parent');
		var state_attr = $(ui.draggable[0]).attr("state");
	  $(this).find('.'+attr).append($('<li parent="'+attr+'" state="'+state_attr+'" >' + ui.draggable.html() + '</li>').draggable({ revert: "invalid",  connectToSortable: "#east ul", cursor: "move",
		  //drag: function(event, ui) { return false; }
		})); 
               
             //var index = array.indexOf(5);
             var index = window.states_gobal_arr.indexOf(state_attr);
             if ( index > -1) 
             {
                 window.states_gobal_arr.splice(index, 1);
                 window.states_removed_existing_arr.push( state_attr );
             }
             //console.log( "global arr 22222 = " , window.states_gobal_arr );
                
	  ui.draggable.remove();
	}

/*	$('.green-button').click(function(){

		$.ajax({
			type: "POST",
			url:"ajax.php",
			success:function(result){
		$('#statements').html(result);
		
		$('#west ul').each(function(i, val) {  $(this).addClass('dlist'+i); });
	 	$('#west ul ul li').each(function(i, val) {  cl = $(this).parent('ul').attr('class'); $(this).attr('parent',cl);  });
		$('#west li').each(function(i, val) { if($(this).find('ul').length == 0) $(this).addClass('westdrag'); else $(this).wrapInner('<span></span>'); });
		$('.westdrag').draggable({
		  revert: "invalid", 
		  connectToSortable: "#east ul",
		  cursor: "move"
		});
	  }});

	})*/

})









