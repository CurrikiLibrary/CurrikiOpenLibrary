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
									<h4 class="font-size-18 d-inline-block align-middle">Geometry - Rules for Angles</h4>
								</div>
								<div class="dropdown">
									<button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-ellipsis-v"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="#"><i class="fa fa-question-circle" aria-hidden="true"></i> Help</a>
										<a class="dropdown-item" href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a>
										<a class="dropdown-item" href="#"><i class="fa fa-flag" aria-hidden="true"></i> Report</a>
										<a class="dropdown-item" href="{{url('/lesson/2')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Exit</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="data-body" style="height:50em;">
					<div class="row justify-content-md-center">
						<h3>Use the protractor to measure the angle. Rotate with the arrow keys</h3>
					</div>
					<div class="row justify-content-md-center py-5">
						<div class="col-md-1"></div>
						<div class="col-md-6">
							<div class="drag-block mb-2 measure-container">
								<img class="angle" src="{{asset('images/activities/measure_1.png')}}">
								<img class="protractor" src="{{asset('images/activities/protractor.svg')}}">
							</div>
						</div>
						<div class="col-md-5">
							<div class="row">
								<div class="bg-gray rounded p-5 font-size-md-30 text-center mb-3">
									What is the angle in degrees?
								</div>								
							</div>
							<div class="row">
								<div class="form-group position-relative form-group-check answer-input">
									<input class="form-control form-control-secondary font-size-md-30" type="text">
									<i class="fa fa-check-circle"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/2/activity/12')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/2/activity/14')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('styles')
	<style>
		.measure-container{
			height:450px;
			width:600px;
		}
		.angle {
			width:347px;
			height:272px;
			position:absolute;
			top:40px;
			left:185px;
		}
		.protractor {
			width:347px;
			position:absolute;
			top:85px;
			left:50px;
		}
	</style>
@endsection
@section('scripts')
	<script>
		var degrees = 0;
		var rotation_speed = 10; //degrees
		$(function() {
			$(document).keydown(function(e) {
			    switch(e.which) {
			        case 37: // left
			        	degrees -= rotation_speed;
			        break;
			        case 39: // right
			        	degrees += rotation_speed;
			        break;
			        default: return; // exit this handler for other keys
			    }
			    rotate_protractor();
			    e.preventDefault(); // prevent the default action (scroll / move caret)
			});

			$('.answer-input input').on('keyup', function(){ 
				if($(this).val().toUpperCase() == '30') {
					$('.answer-input').addClass('done');
					$('.answer-input input').addClass('done');
					$('.answer-input i').addClass('done');
				} else {
					$('.answer-input').removeClass('done');
					$('.answer-input input').removeClass('done');
					$('.answer-input i').removeClass('done');
				}
			});
		});

		function rotate_protractor(){
			$('.protractor').css({'transform' : 'rotate('+ degrees +'deg)'});
		}
	</script>
@endsection