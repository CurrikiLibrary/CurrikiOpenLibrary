<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>CurrikiLEARN</title>
	<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/style.css')}}">
	<link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
	<style type="text/css">
	    .thumb-image {
			object-fit: none;
			width: 100%;
			max-height: 200px;
	    }
	</style>
	@yield('styles')
</head>
<body class="home">
	<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="#"><img class="img-fluid" src="{{asset('images/logo.png')}}" width="222" height="51" alt="CurrikiLEARN"></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<form class="form-inline navbar-search mr-auto" method="get" action="#">
					<input class="form-control navbar-search-input mr-sm-2" type="text" placeholder="Search" aria-label="Search">
					<button class="btn navbar-search-btn" type="submit"><i class="fa fa-search"></i></button>
				</form>
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="#"><i class="fa fa-envelope-o"></i></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#"><i class="fa fa-bell-o"></i></a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-user-circle-o"></i>
							<div class="profile-name">Bonnie Brown</div>
							<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a class="dropdown-item" href="{{url('/admin/lessons')}}"><i class="fa fa-cog"></i> Admin</a></li>
							<li><a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a></li>
							<li class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="#"><i class="fa fa-power-off"></i> Logout</a></li>
						</ul>
					</li>					
				</ul>
			</div>
		</div>
	</nav>
	@yield('content')
	<footer class="footer">
		<div class="footer-top">
			<div class="container">
				<ul class="list-inline footer-menu font-weight-semibold font-size-md-18 w-md-87 d-md-flex justify-content-between">
					<li class="list-inline-item"><a class="no-underline" href="#"><i class="fa fa-globe"></i> &nbsp; <i class="fa fa-angle-down"></i></a></li>
					<li class="list-inline-item"><a href="#">About</a></li>
					<li class="list-inline-item"><a href="#">Courses</a></li>
					<li class="list-inline-item"><a href="#">Support</a></li>
					<li class="list-inline-item"><a href="#">Contact</a></li>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="container">
				<p>Copyright &copy; 2018 Currki. All rights reserved. <a class="ml-3 ml-md-80" href="#">Terms of Use</a> <a class="ml-3 ml-md-4" href="#">Privacy Policy</a></p>
			</div>
		</div>
	</footer>
	
	<a class="link-comment launch-help-modal" href="#"><i class="fa fa-commenting"></i></a>


	<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="helpModalLabel">Push To Talk</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			<div class="text-center">
				<iframe src="https://player.vimeo.com/video/253525648" width="640" height="360" frameborder="0" allowfullscreen></iframe>
			</div>	
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
	
	<script src="{{asset('js/jquery-3.3.1.slim.min.js')}}"></script>
	<script src="{{asset('js/popper.min.js')}}"></script>
	<script src="{{asset('js/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/owl.carousel.min.js')}}"></script>
	<script src="{{asset('js/jquery.easypiechart.min.js')}}"></script>
	<script src="{{asset('js/script.js')}}"></script>
	<script src="{{asset('js/help_modal.js')}}"></script>
	@yield('scripts')
</body>
</html>