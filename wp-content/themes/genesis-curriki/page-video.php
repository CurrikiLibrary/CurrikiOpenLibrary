<?php
/*
 * Template Name: Curriki Video
 */

if($_SERVER['HTTP_X_FORWARDED_PROTO'] != "https")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Curriki</title>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/video-page/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
		integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous">
	<script src="<?php echo includes_url(); ?>js/jquery/jquery.js?ver=1.12.4-wp"></script>
	<script src="<?php echo includes_url(); ?>js/jquery/jquery-migrate.min.js?ver=1.4.1"></script>
	<script src="//use.typekit.net/krr8zdy.js"></script>
	<script src="https://js.stripe.com/v3/"></script>
	<script>try { Typekit.load(); } catch (e) { }</script>
</head>

<body>
	<nav class="navbar">
		<div class="container">
			<a class="navbar-brand" href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/video-page/logo.jpg" width="162" height="73" alt="Curriki"></a>
			<div class="navbar-text">
				<span>Make a contribution today.</span>
				<a class="btn btn-primary" data-toggle="modal" href="#donationmodal"><i class="fa fa-dollar"></i> Donate Now</a>
			</div>
		</div>
	</nav>
	<main>
		<div class="jumbotron">
			<div class="container">
				<div class="row d-flex mb-3">
					<div class="col-md-8">
						<a class="video-block" href="#"></a>
					</div>
					<div class="col-md-4">
						<div class="form-contain">
							<form id="demoform" action="#" method="post">
								<p class="form-head-text">Fill in the form to get more info.</p>
								<div class="form-group">
									<input class="form-control" type="text" name="fname" placeholder="First Name *" required>
								</div>
								<div class="form-group">
									<input class="form-control" type="text" name="lname" placeholder="Last Name *" required>
								</div>
								<div class="form-group">
									<input class="form-control" type="email" name="email" placeholder="Email *" required>
								</div>
								<div class="form-group">
									<input class="form-control" type="text" name="phone" placeholder="Phone *" required>
								</div>
								<div class="form-group">
									<input class="form-control" type="text" name="organization" placeholder="Organization Name">
								</div>
								<div class="form-group pt-2">
									<button class="btn btn-block btn-yellow" type="submit">Submit Form</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row d-flex justify-content-center">
				<div class="col-md-10">
					<h2 class="heading text-center mb-5">Ready to learn more about Curriki’s Vision for a learning
						environment and content ecosystem built for today’s learners?</h2>
					<div class="row mb-4">
						<div class="col-md-4">
							<div class="icon-box">
								<i class="icon icon-check"></i>
								<p>Platform for authoring Dynamic Learning Content</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="icon-box">
								<i class="icon icon-check"></i>
								<p>Facilitates the integration of relevant, engaging, and active content
									into a mode that matches the way this generation thinks and learns.</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="icon-box">
								<i class="icon icon-check"></i>
								<p>Unified Achievement Portfolio for each student</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="call-to-action">
				<span class="cta-text">Make a contribution today.</span>
				<a class="btn btn-yellow" data-toggle="modal" href="#donationmodal"><i class="fa fa-dollar"></i> Donate Now</a>
			</div>
		</div>
	</main>
	<footer class="footer">
		<div class="container">
			<a href="#">www.Curriki.org</a>
			<a href="#">www.BirdiesforEducation.org</a>
		</div>
	</footer>

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
			// Stop form from submitting normally
			event.preventDefault();
			jQuery('#spinnerModalCenter').modal('show');

			// Get some values from elements on the page:
			var $form = jQuery(this),
				fname = $form.find("input[name='fname']").val(),
				lname = $form.find("input[name='lname']").val(),
				name = fname + ' ' + lname,
				email = $form.find("input[name='email']").val();
				phone = $form.find("input[name='phone']").val();
				organization = $form.find("input[name='organization']").val();

			var url = ajaxurl + "?&t=" + new Date().getTime();

			// Send the data using post
			var posting = jQuery.post(url, {
				action: 'cur_ajax_curriki_demo',
				name: name,
				email: email,
				phone: phone,
				organization: organization,
				source: 'video'
			});
			// Put the results in a div

			posting.done(function(data) {
				jQuery('#spinnerModalCenter').modal('toggle');
				if (data.indexOf('demo-done') == -1) {
					jQuery('#message-heading').html('SORRY');
					jQuery('#message-text').html(data);
					jQuery('#notsuremodalsuccess').modal('toggle');
				} else {
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