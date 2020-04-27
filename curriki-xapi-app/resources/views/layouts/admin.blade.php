<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Curriki DEMO Admin</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind+Siliguri:400,600,700&display=swap">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style type="text/css">
	    .thumb-image {
			object-fit: none;
			width: 100%;
			max-height: 200px;
			max-width: 300px;
	    }
	</style>
	@yield('styles')
</head>
<body>
	<div class="container">
		<div class="row ">
			<div class="col">
				<a class="navbar-brand" href="{{url('/')}}"><img class="img-fluid" src="{{asset('/images/logo.png')}}" width="222" height="51" alt="CurrikiLEARN"></a>
			</div>
		</div>
	    @if ($errors->any())
	      <div class="container">
	        <div class="row justify-content-center mt-5">
	          <div class="col-md-12">
	            <div class="alert alert-danger">
	              <ul>
	                @foreach ($errors->all() as $error)
	                  <li>{{ $error }}</li>
	                @endforeach
	              </ul>
	            </div>
	          </div>
	        </div>
	      </div>
	    @endif
	    @if(Session::has('msg'))
	      <div class="container">
	        <div class="row justify-content-center mt-5">
	          <div class="col-md-12">
	            <div class="alert alert-info">
	                {{Session::get('msg')}}
	            </div>
	          </div>
	        </div>
	      </div>
	    @endif
		<div class="row mt-4">
			<div class="col">
				@yield('content')
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	@yield('scripts')
</body>
</html>
