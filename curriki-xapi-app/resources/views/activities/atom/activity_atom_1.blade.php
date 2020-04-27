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
									<h4 class="font-size-18 d-inline-block align-middle">Elements and atoms</h4>
								</div>
								<div class="dropdown">
									<button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-ellipsis-v"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="#"><i class="fa fa-question-circle" aria-hidden="true"></i> Help</a>
										<a class="dropdown-item" href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a>
										<a class="dropdown-item" href="#"><i class="fa fa-flag" aria-hidden="true"></i> Report</a>
										<a class="dropdown-item" href="{{url('/lesson/1')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Exit</a>
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
										The smallest particle of an element that still retains the properties of that element is called a(n):
										<br>
										<a class="btn btn-lg rounded-circle text-gray-o quickFlipCta"><i class="fa fa-refresh"></i></a>
									</div>
						        </div>
						        <div>
									<div class="bg-gray rounded p-5 font-size-md-30 text-center mb-3">
										An atom.
										<br>
										<img class="img-fluid" src="{{asset('images/atom.jpg')}}" width="654" height="442" alt="atom">
									</div>
						        </div>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right pt-4 mt-2">
				<a class="btn btn-blue" href="{{url('/lesson/1/activity/4')}}">Next activity</a>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		$(function() {
		    $('.quickFlip').quickFlip();
		});
	</script>
@endsection