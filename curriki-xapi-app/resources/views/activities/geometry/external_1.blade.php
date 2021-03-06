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
				<div class="data-body">
					<iframe src="http://www.curriculumpathways.com/portal/Launch?id=6204"></iframe>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/2/activity/2')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/2/activity/4')}}">Next activity</a>
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
			height:725px;
			position:relative;
			top:-125px;
		}
	</style>
@endsection
@section('scripts')
	<script>
		$(function() {
		    
		});
	</script>
@endsection