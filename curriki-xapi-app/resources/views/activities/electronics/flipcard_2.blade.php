@extends('layouts.activity_layout')
@section('content')
	<div class="jumbotron px-0">
		<div class="container">
			<div class="data-box">
				<div class="data-head">
					<div class="row justify-content-md-center">
						<div class="col-md-11">
							<div class="d-flex justify-content-between align-items-center mx-auto">
								<div class="head-left">
									<h4 class="font-size-18 d-inline-block align-middle">Founding Principles</h4>
								</div>
								<div class="dropdown">
									<button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-ellipsis-v"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="#"><i class="fa fa-question-circle" aria-hidden="true"></i> Help</a>
										<a class="dropdown-item" href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a>
										<a class="dropdown-item" href="#"><i class="fa fa-flag" aria-hidden="true"></i> Report</a>
										<a class="dropdown-item" href="{{url('/lesson/4')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Exit</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="data-body" style="height:50em;">
					<div class="row justify-content-md-center py-4">
						<div class="col-md-6 text-center">
						    <div class="quickFlip">
						        <div>
									<div class="bg-gray rounded p-5 font-size-md-30 text-center mb-3">
										Equality
										<br>
										<a class="btn btn-lg rounded-circle text-gray-o quickFlipCta"><i class="fa fa-refresh"></i></a>
									</div>
						        </div>
						        <div>
									<div class="bg-gray rounded p-5 font-size-md-30 text-center mb-3">
										Every individual is equal to every other person in regards to natural rights and treatment before the law. 
										<br>
										<img class="img-fluid" src="{{asset('images/activities/civics_flipcard_2.jpg')}}" >
										<br>
										<a class="btn btn-lg rounded-circle text-gray-o quickFlipCta"><i class="fa fa-refresh"></i></a>
									</div>
						        </div>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/4/activity/5')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/4/activity/7')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('styles')
	<style type="text/css">
		.data-body img {
			margin: 1em;
		}
	</style>
@endsection
@section('scripts')
	<script>
		$(function() {
		    $('.quickFlip').quickFlip();
		});
	</script>
@endsection