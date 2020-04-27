<?php

$question = 'self assessment question';
$questionid = 118;
$question_num = 1;
$question_type = 'mcq';     //mcq, true_false


$options = [
    [
        'answer' => 'answer a',
        'response' => 'jdljf',
        
    ],
    [
        'answer' => 'answer b',
        'response' => 'sdf',
        
    ],
    [
        'answer' => 'answer c',
        'response' => 'sdf',
        'correct'=>'T',
    ],
//    [
//        'answer' => 'equation d	',
//        'response' => 'Nope. Each y value is the square of x with 3 added to it, not subtracted from it.',
//        
//    ],
];


?>

<div class="question_wrapper">
    <form class="question_front_form mceNonEditable">
        <input name="question_num" type="hidden" value="<?php echo $question_num; ?>">
        <div class="question_frontend_statement">
            <div class="question_title">
                <div class="question">
                    <p><?php echo $question; ?></p>
                </div>
                <span class="display_none edit_question_link" style="text-decoration: underline; cursor: pointer;">Edit</span>
            </div>
        </div>
        <div class="question_frontend_options">
            <?php $count = 1; ?>
            <?php foreach ($options as $option): ?>
                <?php 
                if(isset($option['correct']) && $option['correct'] == 'T'){
                    $correct_option = $count;
                }
                ?>
                <div class="frontend_option">
                    <div class="frontend-label">
                        <input id="option<?php echo $count; ?>" name="option" type="radio" value="<?php echo $count; ?>">
                        <div class="option_val">
                            <p><?php echo $option['answer']; ?></p>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                    </div>
                    <div id="response_option<?php echo $count; ?>" class="frontend_response" style="display: none;">
                        <p><?php echo $option['response']; ?></p>
                    </div>
                </div>
            <?php $count++; ?>
            <?php endforeach; ?>
        </div>
        <div class="correct_option" style="display: none;"><?php echo $correct_option; ?></div>
        <div class="question_type" style="display: none;"><?php echo $question_type; ?></div>
        <input class="questionid" name="questionid" type="hidden" value="<?php echo $questionid; ?>">
        <div class="question_frontend_submit_wrapper">
            <input name="question_frontend_submit" type="submit" value="Check Answer">
        </div>
    </form>
    <p>&nbsp;</p>
</div>
<p>&nbsp;</p>