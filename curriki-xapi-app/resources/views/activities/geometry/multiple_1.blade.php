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
					<div class="row justify-content-md-center py-5">
						<div class="col-md-11">
							<div class="row">
								<div class="col-md-6">
									<div class="bg-gray rounded p-5 font-size-md-30 text-center mb-3">
										Two lines in the same plane that do not intersect are called:
									</div>
								</div>
								<div class="col-md-6">
									<div class="px-md-4">
										<ol class="list-unstyled list-number">
											<li><span class="number">1</span>
												<button class="btn btn-outline btn-block font-size-md-26 wrong-option" type="button">Intersecting Lines</button>
											</li>
											<li><span class="number">2</span>
												<button class="btn btn-outline btn-block font-size-md-26 wrong-option" type="button">Line Segments</button>
											</li>
											<li><span class="number">3</span>
												<button class="btn btn-outline btn-block font-size-md-26 right-option" type="button">Parallel Lines</button>
											</li>										
											<li><span class="number">4</span>
												<button class="btn btn-outline btn-block font-size-md-26 wrong-option" type="button">Perpendicular Lines</button>
											</li>										
										</ol>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/2/activity/9')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/2/activity/12')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('styles')
	<style>
		.wrong-one {
			border-color:red;
			border-width: thick;
		}

		.right-one {
			border-color:#28a745;
			border-width: thick;
		}
	</style>
@endsection
@section('scripts')
	<script>
		$(function(){
			$('.right-option').on('click', function(){ $(this).addClass('right-one'); });
			$('.wrong-option').on('click', function(){ $(this).addClass('wrong-one'); });
		});
	</script>
@endsection