/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function(){
    jQuery('.question_frontend_options .frontend_option').click(function(){
        jQuery(this).find('input').prop('checked', 'checked');
        jQuery('.question_frontend_options .frontend_option').removeClass('selected_option');
        jQuery(this).addClass('selected_option');
    });
    jQuery('.question_front_form').submit(function(e){
        var form = jQuery(this);
        e.preventDefault();
        var $selected_option = form.find('input[type=radio]:checked').val();
        var $question_num = form.find('[name=question_num]').val();
        var $questionid = form.find('[name=questionid]').val();
        form.append('<div class="loader"></div>');
        form.find('.status').remove();
        
        
        
        
        
        jQuery.ajax({url: ajaxurl,
            dataType: "json",
            method: "POST",
            data: {action:'attempt_question', question_num:$question_num, selected_option: $selected_option, questionid: $questionid},
            success: function (result) {
                jQuery('.loader').remove();
                if(result.message){
                    form.append("<div class='status'>"+result.message+"</div>");
                }
                
            }
        });
    });
});