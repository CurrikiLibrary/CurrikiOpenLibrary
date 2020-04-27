<?php

/*
 * Question format
 */

/*
<div class="question_wrapper">
    <form class="question_front_form mceNonEditable">
        <input name="question_num" type="hidden" value="1">
        <div class="question_frontend_statement">
            <div class="question_title">
                <div class="question">
                    <p>test</p>
                </div>
                <span class="display_none edit_question_link" style="text-decoration: underline; cursor: pointer;">Edit</span>
            </div>
        </div>
        <div class="question_frontend_options">
            <div class="frontend_option">
                <div class="frontend-label">
                    <input id="option1" name="option" type="radio" value="1">
                    <div class="option_val">
                        <p>adsf</p>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div id="response_option1" class="frontend_response" style="display: none;">
                    <p>asdf</p>
                </div>
            </div>
            <div class="frontend_option">
                <div class="frontend-label">
                    <input id="option2" name="option" type="radio" value="2">
                    <div class="option_val">
                        <p>asdf</p>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div id="response_option2" class="frontend_response" style="display: none;">
                    <p>asdf</p>
                </div>
            </div>
        </div>
        <div class="correct_option" style="display: none;">2</div>
        <div class="question_type" style="display: none;">mcq</div>
        <input class="questionid" name="questionid" type="hidden" value="76">
        <div class="question_frontend_submit_wrapper">
            <input name="question_frontend_submit" type="submit" value="Check Answer">
        </div>
    </form>
    <p>&nbsp;</p>
</div>
<p>&nbsp;</p>

 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '8192M');

require_once "inc/autoload.php";


$env = isset($_GET['env']) ? $_GET['env'] : 'local';


$sync = new Sync($env);

$sync->changeStructureQuestions();
