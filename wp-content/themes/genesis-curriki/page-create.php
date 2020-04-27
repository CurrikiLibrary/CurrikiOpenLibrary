<?php

/**
 * Template Name: Curriki Create
 */

remove_action('genesis_loop', 'genesis_do_loop');

add_action('genesis_before', 'curriki_create_page_scripts');

function curriki_create_page_scripts() {
    wp_enqueue_script('create-js', get_stylesheet_directory_uri() . '/js/create-page/create.js', array('jquery'), false, true);
    wp_localize_script('create-js', 'create_js_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        )
    );
    wp_enqueue_style('create-css', get_stylesheet_directory_uri() . '/css/create.css');
}

add_action('genesis_loop', 'curriki_create_page_content');

function curriki_create_page_content() {
    ?>
    <div class="resource-content clearfix">
        <div class="wrap container_12">
        <div class="pt-40">
            <h2 class="heading-v2 text-light-blue text-center">Curriki Is Here To Help</h2>
        </div>
        <div class="grid_6">
            <div class="info-box">
            <h4><i class="icon icon-check"></i> OUR MISSION</h4>
            <p>Curriki has been in the process of building a comprehensive development
                and delivery environment to bring robust and easy-to-use content authoring
                tools and mobile native delivery available to the K12 learning ecosystem.</p>
            </div>
            <div class="info-box">
            <h4><i class="icon icon-check"></i> IN THIS TIME OF CRISIS</h4>
            <p>In response to the current and projected impact on our school environment 
                due to COVID - 19, we are significantly accelerating our release schedule to
                support you efforts to deliver engaging elearning experiences and and virtual instruction.</p>
            </div>
            <div class="info-box">
            <h4><i class="icon icon-check"></i> LET US HELP YOU</h4>
            <p>Please submit some preliminary information and we will contact you on setting up access to our
                toolkit and technology. Curriki is committed to redefining OPEN learning, in a FREE, accessible,
                and learner-focused platform. Let’s build together.</p>
            </div>
        </div>
        <div class="grid_6">
            <div class="form-contain">
            <form id="demoform" action="#" method="post">
                <p class="form-head-text">Fill in the form to get more info.</p>
                <div class="form-group">
                <input class="form-control" type="text" name="fname" placeholder="First Name *" required="">
                </div>
                <div class="form-group">
                <input class="form-control" type="text" name="lname" placeholder="Last Name *" required="">
                </div>
                <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Email *" required="">
                </div>
                <div class="form-group">
                <input class="form-control" type="text" name="phone" placeholder="Phone *" required="">
                </div>
                <div class="form-group">
                <input class="form-control" type="text" name="organization" placeholder="Organization Name">
                </div>
                <div class="form-group">
                <textarea name="description" class="form-control" rows="10" cols="10"
                    placeholder="Brief description of your program or course"></textarea>
                </div>
                <div class="form-group pt-2">
                <button class="btn btn-block btn-yellow" type="submit">CLICK TO LEARN MORE</button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal -->
	<div class="modal fade" id="spinnerModalCenter" tabindex="-1" role="dialog" aria-labelledby="spinnerModalCenter" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="loader"></div>
				</div>
			</div>
		</div>
    </div>

    <div class="modal fade modal-secondary" tabindex="-1" role="dialog" id="notsuremodalsuccess">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center">
					<div class="result-body">
						<p class="text-big font-weight-700 text-blue" id="message-heading"></p>
						<p id="message-text"></p>
						<div class="divider"></div>
						<p>
							We love to engage with our Curriki community <br>
							across our social channels. Jon the conversation on <br>
							your favorite social media channel!
						</p>
						<ul class="list-inline social-icons">
							<li><a class="link-facebook" href="#">Facebook</a></li>
							<li><a class="link-instagram"href="#">Instagram</a></li>
							<li><a class="link-youtube"href="#">Youtube</a></li>
							<li><a class="link-pinterest"href="#">Pinterest</a></li>
							<li><a class="link-twitter"href="#">Twitter</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php
}

genesis();
