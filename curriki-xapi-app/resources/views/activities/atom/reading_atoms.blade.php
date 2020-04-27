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
				<div class="data-body">
					<div class="row justify-content-md-center py-4">
						<div class="col-md-11">
							<div class="row">
								<div class="col-md-6">
									<div class="font-size-16">
										<h3 class="font-size-18 mb-4 pb-3">The structure of the atom</h3>
										<p>An atom is the smallest unit of matter that retains all of 
										the chemical properties of an element. For example, a gold coin 
										is simply a very large number of gold atoms molded into the shape 
										of a coin, with small amounts of other, contamination 
										elements. Gold atoms cannot be broken down into anything smaller 
										white still retaining the properties of gold.</p>
										<h4 class="font-size-16 mb-4">An atom consists of two regions.</h4>
										<p>The first is the tiny atomic nucleus, which is in the center of the atom 
										and contains positively charged particles</p>
									</div>
								</div>
								<div class="col-md-6">
									<img class="img-fluid" src="{{asset('images/the-basic-structure-of-an-atom.jpg')}}" width="655" height="444" alt="The basic structure of an atom">
									<p>1.1 The basic structure of an atom</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right pt-4 mt-2">
				<a class="btn btn-blue" href="{{url('/lesson/1/activity/3')}}">Next activity</a>
			</div>
		</div>
	</div>
@endsection