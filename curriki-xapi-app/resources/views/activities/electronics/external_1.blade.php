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
									<h4 class="font-size-18 d-inline-block align-middle">Deconstructing the Declaration</h4>
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
				<div class="data-body">
					<iframe src="https://scratch.mit.edu/projects/1846893/fullscreen/"></iframe>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/4/activity/3')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/4/activity/5')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('styles')
	<style>
		.data-body {
			overflow:hidden;
			height: 600px;
			max-height: 900px !important;
		}

		.data-body iframe{
			width:100%;
			height:755px;
			position:relative;
			top:-155px;
		}
	</style>
@endsection