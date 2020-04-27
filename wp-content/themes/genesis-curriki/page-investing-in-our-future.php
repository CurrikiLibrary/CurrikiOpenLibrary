<?php
/*
 * Template Name: Investing In Our Future
 */

if($_SERVER['HTTP_X_FORWARDED_PROTO'] != "https")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:addthis="https://www.addthis.com/help/api-spec">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Curriki | Inspiring Learning Everywhere</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/investing-in-our-future/landing-page.css">
	<script src="<?php echo includes_url(); ?>js/jquery/jquery.js?ver=1.12.4-wp"></script>
	<script src="<?php echo includes_url(); ?>js/jquery/jquery-migrate.min.js?ver=1.4.1"></script>
	<script src="//use.typekit.net/krr8zdy.js"></script>
	<script>
		try {
			Typekit.load();
		} catch (e) {}
	</script>
	<script src="https://js.stripe.com/v3/"></script>
</head>

<body class="landing-page" itemscope itemtype="https://schema.org/WebPage">
	<header class="site-header" itemscope itemtype="https://schema.org/WPHeader">
		<div class="container-fluid wrap">
			<div class="title-area">
				<p class="site-title" itemprop="headline"><a href="https://www.curriki.org/">Curriki</a></p>
			</div>
		</div>
	</header>
	<div class="container">
		<div class="content">
			<div class="row">
				<div class="col-md-7 text-center">
					<div class="bs-block">
						<h1 class="heading">Education Empowers Opportunity</h1>
						<p><a class="text-blue font-weight-700" data-toggle="modal" href="#donationmodal">Join Team
								Curriki</a></p>
						<p>Our Active Learning tools for teachers will revolutionize the way
							we learn and collaborate by making the best curriculum, lessons
							and on-demand content free, open and accessible to everyone.</p>
						<p class="text-blue">Together, we can ensure that every child will have access to a
							free, world class education.</p>
						<p><a class="btn btn-green font-size-24" data-toggle="modal" href="#donationmodal">I’M IN!</a></p>
						<p><a class="color-inherit underline" data-toggle="modal" href="#notsuremodal">I'm not sure
								yet.</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-secondary" tabindex="-1" role="dialog" id="donationmodal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Make a Donation that will change lives forever.</h4>
					<p>Fields marked with an <span class="color-red font-weight-500">*</span> are required</p>
					<div class="dialog_result_div">
						<div id="donation_result" class="dialog_result"></div>
					</div>
				</div>
				<div class="modal-body">
					<form id="donationform" class="form-modal" action="#" method="post">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Your Pledge $ <span class="color-red">*</span></label>
									<input id="amount" class="form-control" type="number" name="amount" min="1" step="1">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Your Name</label>
							<input class="form-control" type="text" name="fullname">
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Your Email</label>
									<input id="email" class="form-control" type="email" name="email">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Phone</label>
									<input class="form-control" type="text" name="phone">
								</div>
							</div>
						</div>
						<div class="form-group buttonpane">
							<button class="btn btn-green" type="submit">PAY FOR MY DONATION</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					Engaging students, empowering teachers.
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-secondary" tabindex="-1" role="dialog" id="notsuremodal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title mb-35">Not sure yet?</h4>
					<p class="text-blue font-weight-500">How about a demo?</p>
					<div class="dialog_result_div">
						<div id="demo_result" class="dialog_result"></div>
					</div>
				</div>
				<div class="modal-body">
					<form id="demoform" class="form-modal" action="#" method="post">
						<div class="form-group">
							<label>Your Name</label>
							<input class="form-control" type="text" name="name">
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Your Email</label>
									<input class="form-control" type="email" name="email">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Phone</label>
									<input class="form-control" type="text" name="phone">
								</div>
							</div>
						</div>
						<div class="form-group buttonpane">
							<button class="btn btn-green" type="submit">SHOW ME</button>
						</div>
					</form>
					<input type="hidden" name="please-wait-text-demo" id="please-wait-text-demo" value="<?php echo __('Please wait!', 'curriki'); ?>" />
				</div>
				<div class="modal-footer">
					Pushing the boundaries in curriculum and technology.
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

	<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js?ver=2.1.5'></script>
	<script type="text/javascript">
		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		var stripe = Stripe("<?php echo STRIPE_PUBLISHABLE_KEY; ?>");

		// Attach a submit handler to the form
		jQuery("#demoform").submit(function(event) {
			jQuery("#demo_result").empty().append(jQuery("#please-wait-text-demo").val());
			jQuery(".dialog_result_div").css('background-color', '#e2e3e5');
			// Stop form from submitting normally
			event.preventDefault();
			// Get some values from elements on the page:
			var $form = jQuery(this),
				name = $form.find("input[name='name']").val(),
				email = $form.find("input[name='email']").val();
			phone = $form.find("input[name='phone']").val();

			var url = ajaxurl + "?&t=" + new Date().getTime();

			// Send the data using post
			var posting = jQuery.post(url, {
				action: 'cur_ajax_curriki_demo',
				name: name,
				email: email,
				phone: phone,
				source: 'demo'
			});
			// Put the results in a div

			posting.done(function(data) {
				if (data.indexOf('demo-done') == -1) {
					jQuery("#demo_result").empty().append(data);
					jQuery(".dialog_result_div").css('background-color', '#e2e3e5');
				} else {
					jQuery('#notsuremodal').modal('toggle');
					jQuery('#message-heading').html('THANK YOU');
					jQuery('#message-text').html('We will be in touch soon to schedule a demo of our <br> Active Learning tools for teachers!');
					jQuery('#notsuremodalsuccess').modal('toggle');
				}
			});

			return false;
		});

		// Handle any errors returned from Checkout
		var handleResult = function(result) {
			if (result.error) {
				jQuery("#donation_result").empty().append(result.error.message);
				jQuery(".dialog_result_div").css('background-color', '#e2e3e5');
			}
		};

		// Attach a submit handler to the form
		jQuery("#donationform").submit(function(event) {
			var url = ajaxurl + "?&t=" + new Date().getTime();
			var amount = parseInt(jQuery("#amount").val());
			var email = jQuery("#email").val();
			var posting = jQuery.post(url, {
				action: 'cur_ajax_curriki_donation_checkout',
				amount: amount,
				email: email,
				url: '<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>'
			});

			posting.done(function(json) {
				data = JSON.parse(json);

				stripe
					.redirectToCheckout({
						sessionId: data.sessionId
					})
					.then(handleResult);
			});

			return false;
		});

		var urlParams = new URLSearchParams(window.location.search);
		var sessionId = urlParams.get("session_id")
		if (sessionId) {
			var url = ajaxurl + "?&t=" + new Date().getTime();
			var posting = jQuery.get(url, {
				action: 'cur_ajax_curriki_donation_checkout_session',
				sessionId: sessionId
			});

			posting.done(function(data) {
				jQuery('#message-heading').html('THANK YOU');
				jQuery('#message-text').text('Your payment was successful.');
				jQuery('#notsuremodalsuccess').modal('toggle');

				session = JSON.parse(data);

				var sessionJSON = JSON.stringify(session, null, 2);
				console.log('sessionJSON', sessionJSON);
			});
		}

		var urlParams = new URLSearchParams(window.location.search);
		var status = urlParams.get("status");
		if (status && status == 'cancel') {
			jQuery('#message-heading').html('SORRY');
			jQuery('#message-text').text('Your payment was canceled.');
			jQuery('#notsuremodalsuccess').modal('toggle');
		}
	</script>
</body>

</html>