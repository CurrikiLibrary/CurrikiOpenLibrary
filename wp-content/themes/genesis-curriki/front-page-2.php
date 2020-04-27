<?php
/*
* Template Name: Custom Front Page 2
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Curriki</title>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/coming-soon-2/style.css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/coming-soon-2/jquery.fancybox.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
		integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous">
	<script src="//use.typekit.net/krr8zdy.js"></script>
	<script>try { Typekit.load(); } catch (e) { }</script>
</head>

<body>
	<header class="header">
		<div class="top-bar">
			<div class="container">
				<a class="navbar-brand" href="#"><img src="/wp-content/themes/genesis-curriki/images/coming-soon-2/logo.png" width="144" height="67" alt="Curriki"></a>
				<div class="navbar-right">
					<ul class="nav">
						<li class="nav-item">
							<a class="nav-link" href="#">Join Now</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Sign In</a>
						</li>					
					</ul>
					<a class="brand-link" href="https://birdiesforeducation.com"><img src="/wp-content/themes/genesis-curriki/images/coming-soon-2/mavrick_mcnealy.png" width="321" height="63" alt="Maverick Mcnealy Birdies For Education"></a>
					<span class="support-text">
						Support the Effort <br>
						<a class="btn btn-primary" href="https://birdiesforeducation.com/#contribute"><i class="fa fa-dollar"></i> Donate Now</a>
					</span>
					<button class="navbar-toggler" type="button">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>
			</div>
		</div>
		<nav class="navbar">
			<div class="container">
				<div class="navbar-collapse">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="/">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/about-curriki-2">About Curriki</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="https://birdiesforeducation.com">Birdies for Education</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/browse-resource-library">Curriki Resource Library</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/blog">Blog</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/contact-us/">Contact Us</a>
						</li>
						<li class="nav-item hidden-lg">
							<a class="nav-link" href="#">Join Now</a>
						</li>
						<li class="nav-item hidden-lg">
							<a class="nav-link" href="#">Sign In</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>
	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-6">
					<div class="jumbotron-body">
						<h1 class="heading">Find your Lesson</h1>
						<p>A community for teaching and learning <br> 
						Share and explore High Quality K-12 content.</p>
						<form class="form-primary" action="/search" method="get">
							<div class="input-group mb-3">
								<input class="form-control" type="text" placeholder="What do you want to learn?" name="phrase" required>
								<div class="input-group-append">
									<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<main>
		<div class="container section-row">
			<div class="row">
				<div class="col-md-4">
					<div class="info-box text-center">
						<a class="image-box" href="/wp-content/themes/genesis-curriki/images/coming-soon-2/homepage_large.jpg" data-fancybox>
							<img class="img-fluid" src="/wp-content/themes/genesis-curriki/images/coming-soon-2/homepage.jpg" width="456" height="332" alt="Homepage">
						</a>
						<div class="info-text">
							<span class="counter">01</span>
							<p>New Improved <br> Homepage</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="info-box text-center">
						<div class="info-text">
							<span class="counter">02</span>
							<p>New Dashboard Puts <br> Everything at Your <br> Fingertips</p>
						</div>
						<a class="image-box" href="/wp-content/themes/genesis-curriki/images/coming-soon-2/dashboard_large.jpg" data-fancybox>
							<img class="img-fluid" src="/wp-content/themes/genesis-curriki/images/coming-soon-2/dashboard.jpg" width="456" height="332" alt="Dashboard">
						</a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="info-box text-center">
						<a class="image-box" href="/wp-content/themes/genesis-curriki/images/coming-soon-2/search_result_large.jpg" data-fancybox>
							<img class="img-fluid" src="/wp-content/themes/genesis-curriki/images/coming-soon-2/search_result.jpg" width="456" height="332" alt="Search Result">
						</a>
						<div class="info-text no-border">
							<span class="counter">03</span>
							<p>Search Results <br> Made Easy!</p>
						</div>
					</div>
				</div>				
			</div>
		</div>
		<div class="bg-gray">
			<div class="container py-88">
				<div class="row d-flex justify-content-center mb-5">
					<div class="col-md-8">
						<div class="text-center">
							<h2 class="heading-v2">New Student CTE Series on Electronics</h2>
							<p>Students will be able to enroll in this self-paced program studying 
							electronics. The program is perfect for students in a CTE program and for those 
							who want to explore electronics independently.</p>
						</div>
					</div>
				</div>
				<div class="text-center">
					<a class="image-box no-shadow" href="/wp-content/themes/genesis-curriki/images/coming-soon-2/resource_and_collection_large.jpg" data-fancybox>
						<img class="img-fluid hidden-sm" src="/wp-content/themes/genesis-curriki/images/coming-soon-2/resource_and_collection.jpg" width="1414" height="540" alt="CTE Series on Electronics">
						<img class="img-fluid hidden-md" src="/wp-content/themes/genesis-curriki/images/coming-soon-2/resource_and_collection_mobile.jpg" width="456" height="335" alt="CTE Series on Electronics">
					</a>
				</div>
			</div>
		</div>
	</main>
	<footer class="footer">
		<div class="footer-top">
			<div class="container">
				<ul class="social-icons social-icons-secondary text-center">
					<li><a class="facebook" href="https://www.facebook.com/CurrikiEducation"><i class="fa fa-facebook"></i></a></li>
					<li><a class="linkedin" href="https://twitter.com/curriki"><i class="fa fa-twitter"></i></a></li>
					<li><a class="pinterest" href="https://www.pinterest.com/curriki/"><i class="fa fa-pinterest"></i></a></li>
					<li><a class="youtube" href="https://www.youtube.com/channel/UCHj1RYy9PUVWG-sYNvd61kg"><i class="fa fa-youtube"></i></a></li>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="container text-center">
				<p class="privacy"><a href="/privacy-policy">Privacy Policy</a> <a href="/terms-of-service">Terms of Service</a></p>
			</div>
		</div>
	</footer>
	
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/jquery.fancybox.min.js"></script>
	<script>
		jQuery(document).ready(function(){
			jQuery('.navbar-toggler').on('click', function(){
				$('.navbar-collapse').toggleClass('show');
			});
		});
	</script>

	<script type="text/javascript">
		/* START WHAT THIS JS DOES: GET SUBSCRIBER'S EMAIL ADDRESS FROM THE EMAIL, PUT IT INTO EMAIL FIELD IN FORM */
		/* FOR THE CTA LINK IN THE ASSOCIATED EMAIL, USE THIS: http://www.customerurl.com?email=[email] */
		/* THAT MEANS THAT THIS FORM NEEDS TO BE HOSTED BY THE CUSTOMER TO GET THAT URL */

		/* REFER TO THE OPENING FORM TAG TO GET THE BELOW ID NAME */

		function preEmail() {
			var iCform = document.getElementById('ic_signupform');

			var query = window.location.search.substring(1);
			var query = query.split('&');
			var args = {};
			for (var i = 0; i < query.length; i++) {
				var name = query[i].split('=')[0];
				var value = query[i].split('=')[1];
				args[name] = decodeURIComponent(value);
			}
			if (args.email) {
				/* REFER TO THE OPENING FORM TAG TO GET THE BELOW NUMBER */
				iCform["data[email]"].value = args.email;
			}
			if (args.rurl) {
				/* REFER TO THE OPENING FORM TAG TO GET THE BELOW NUMBER */
				iCform["redirect"].value = args.email;
			}
		}
		window.onload = preEmail;

		/* END WHAT THIS JS DOES: GET SUBSCRIBER'S EMAIL ADDRESS FROM THE EMAIL,
		PUT IT INTO EMAIL FIELD IN FORM */
	</script>

	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coming-soon-2/jquery-3.4.1.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/coming-soon-2/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="https://app.icontact.com/icp/static/form/javascripts/validation-captcha.js"></script>
	<script type="text/javascript" src="https://app.icontact.com/icp/static/form/javascripts/tracking.js"></script>
</body>

</html>