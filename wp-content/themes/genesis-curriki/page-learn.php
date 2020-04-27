<?php

/**
 * Template Name: Curriki Learn
 */

remove_action('genesis_loop', 'genesis_do_loop');

add_action('genesis_before', 'curriki_learn_page_scripts');

function curriki_learn_page_scripts() {
    wp_enqueue_style('learn-css', get_stylesheet_directory_uri() . '/css/learn.css');
}

add_action('genesis_loop', 'curriki_learn_page_content');

function curriki_learn_page_content() {
    ?>
    <div class="learning-content">
        <div class="wrap mb-50">
        <div class="grid_5">
            <img class="learning-helper" src="<?php echo get_stylesheet_directory_uri(); ?>/images/learn-page/ask_riki.png" width="291" height="326" alt="Ask Riki">
        </div>
        <div class="grid_7">
            <h1 class="page-title-v2">CurrikiLEARN</h1>
            <div class="learn-desc">
            <h2>What is CurrikiLEARN?</h2>
            <p>It is a place for discovery and exploration. With CurrikiLEARN you can:</p>
            <ul class="learn-list-icon-check">
                <li>Search our mini lessons and find the answers to your questions.</li>
                <li>Practice skills you want or need to master.</li>
                <li>Or sign up for an entire program and earn Curriki badges and certificates.</li>
            </ul>
            </div>
        </div>
        </div>
        <div class="wrap mb-50">
        <div class="grid_4">
            <div class="info-box">
            <i class="icon icon-activity"></i>
            <h4 class="info-title">ACTIVITIES</h4>
            <p>CurrikiLEARN is a collection of short learning activities.</p>
            </div>
        </div>
        <div class="grid_4">
            <div class="info-box">
            <i class="icon icon-program"></i>
            <h4 class="info-title">PROGRAMS</h4>
            <p>Want to build your learning portfolio? Sign up for a program - and complete
                all the playlusts that show you have mastered a subject!</p>
            </div>
        </div>
        <div class="grid_4">
            <div class="info-box">
            <i class="icon icon-playlist"></i>
            <h4 class="info-title">PLAYLISTS</h4>
            <p>Activites that help you understand a concept are grouped together into a
                playlist.</p>
            </div>
        </div>
        </div>
        <div class="wrap">
        <div class="panel-learn">
            <div class="panel-learn-header">
            <h4 class="panel-learn-title">WHAT SUBJECTS CAN I EXPLORE WITH CurrikiLEARN?</h4>
            </div>
        </div>
        </div>
    </div>
    <?php
}

genesis();
