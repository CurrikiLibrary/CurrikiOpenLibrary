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
					<div class="row justify-content-md-center py-5">
						<div class="col-md-11 text-center">
							<div class="text-primary font-size-md-26 mb-4">Great Job!</div>
							<i class="icon-v3 icon-v3-xl icon-atoms-blue"></i>
							<div class="success-summary font-size-18">
								<div class="text-primary py-3">1/3</div>
								<div class="text-complete">Lesson completed! +2XP</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right pt-4 mt-2">
				<a class="btn btn-blue" href="{{url('/lesson/1')}}">Return</a>
			</div>
		</div>
	</div>
@endsection