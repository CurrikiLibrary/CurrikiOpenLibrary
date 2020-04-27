/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function(){
    jQuery('.question_front_form').submit(function(e){
        var form = jQuery(this);
        e.preventDefault();
        var $selected_option = form.find('input[type=radio]:checked').val();
        var $question_num = form.find('[name=question_num]').val();
        var $questionid = form.find('[name=questionid]').val();
//        form.find('[name=question_frontend_submit]').css({display:'none'});
        form.append('<div class="loader"></div>');
        form.find('.status').remove();
        
        
        setTimeout(function(){
            jQuery('.loader').remove();
//                form.find('.frontend_question').css({display:'none'});
//                form.find('.frontend_option').css({display:'none'});
                var response = form.find('input[type=radio]:checked').parent().parent().next().text();
                form.append("<div class='status'>"+response+"</div>");
                var correct_option = form.find('.correct_option').text();
//                form.find('input[type=radio]:checked').parent().parent().next().css({display:'block'});
                if(correct_option == $selected_option){
                    form.append("<div class='status'><strong>Congratulations. You selected the correct answer.</strong></div>");
                } else {
                    form.append("<div class='status'><strong>Your answer was incorrect. Please try again.</strong></div>");
                }
        }, 1000);
        
    });
});