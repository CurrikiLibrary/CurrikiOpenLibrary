@extends('layouts.main_layout')
@section('content')
	<div class="container mb-4 pb-2">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb pb-4 mb-4">
				<li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
				<li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
				<li class="breadcrumb-item">Civics</li>
				<li class="breadcrumb-item active" aria-current="page">Being An American</li>
			</ol>
		</nav>
		<div class="row">
			<div class="col-md-3">
				<div class="sidebar mb-4 mb-md-0">
					<div class="side-image mb-2">
						<img class="img-fluid img-radius-all" src="{{asset('images/being-american-thumb.png')}}" width="309" height="301" alt="Chemistry of life">
						<div class="rating">
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
						</div>
					</div>
					<div class="progress mb-2">
						<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<div class="d-flex justify-content-between align-items-center w-100 mb-2 font-size-18">
						<a class="text-muted no-underline" href="#"><i class="fa fa-users"></i>&nbsp; 60K</a>
						<a class="text-muted no-underline" href="#"><i class="fa fa-heart text-red"></i>&nbsp; 30K</a>
					</div>
					<h3 class="font-size-18 mb-3">Being An American</h3>
					<p>
						In this lesson, students will explore the structure, purpose, and significance of the Declaration of Independence. Students will analyze the concepts of inalienable or natural rights and government by consent to begin to understand the philosophical foundations of America’s constitutional government
					</p>
					<div class="media mb-3">
						<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/thomas_jefferson.png')}}" width="52" height="52" alt="Mr. Roberts">
						<div class="media-body my-auto">
							<div class="name font-weight-bold">Mr. T. Jefferson</div>
						</div>
					</div>
					<a class="btn btn-blue btn-block" href="#"><i class="fa fa-envelope-o mr-2"></i> Send a message</a>
				</div>
			</div>
			<div class="col-md-9">
				<div class="shadow-box">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" href="#activities-tab" id="nav-activities-tab" data-toggle="tab" aria-controls="activities-tab" aria-selected="true"><i class="fa fa-filter"></i> <span class="d-none d-md-inline">Activities</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#practice-tab" id="nav-practice-tab" data-toggle="tab" aria-controls="practice-tab" aria-selected="false"><i class="fa fa-ticket"></i> <span class="d-none d-md-inline">Practice</span></a>
						</li>
					</ul>
					<div class="content-box">
						<div class="tab-content">
							<div class="tab-pane fade show active" id="activities-tab" role="tabpanel" aria-labelledby="nav-activities-tab">
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">The Declaration of Independence</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="lesson-icon" src="{{asset('images/declaration-indy-thumb.png')}}"alt="Image of a scroll">
												</div>
												<p><a href="#">The Declaration of Independence</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="{{ url('/lesson/3/activity/1') }}">Video: Being an American</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="{{ url('/lesson/3/activity/2') }}">Document: The Declaration</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="{{ url('/lesson/3/activity/3') }}">Lesson: Structure of the Declaration</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="{{ url('/lesson/3/activity/4') }}">Lesson: Deconstructing the Declaration</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="{{ url('/lesson/3/activity/5') }}">Flipcards: Founding Principles</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p><a href="{{ url('/lesson/3/activity/9') }}">Quiz</a></p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">The United States Constitution</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="lesson-icon" src="{{asset('images/lessons/constitution_thumb.jpg')}}"alt="Image of a scroll">
												</div>
												<p><a href="#">The United States Constitution</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="#">The United States Constitution</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Lesson</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="">External Resource</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Flipcard</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p><a href="#">Quiz</a></p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">The Bill of Rights</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="lesson-icon" src="{{asset('images/lessons/bill_of_rights_thumb.jpg')}}"alt="Image of a scroll">
												</div>
												<p><a href="#">The Bill of Rights</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="#">Video</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Lesson</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="">External Resource</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Flipcard</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p><a href="#">Quiz</a></p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">America’s Civic Values</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="lesson-icon" src="{{asset('images/lessons/civic_values_thumb.jpg')}}"alt="Image of a scroll">
												</div>
												<p><a href="#">America’s Civic Values</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="#">Video</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Lesson</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="">External Resource</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Flipcard</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p><a href="#">Quiz</a></p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">American Heroes: Past and Present</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="lesson-icon" src="{{asset('images/lessons/heroes_thumb.jpg')}}"alt="Image of a scroll">
												</div>
												<p><a href="#">American Heroes: Past and Present</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="#">Video</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Lesson</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="">External Resource</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Flipcard</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p><a href="#">Quiz</a></p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">A Personal Response to American Citizenship</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="lesson-icon" src="{{asset('images/lessons/citizenship_thumb.jpg')}}"alt="Image of a scroll">
												</div>
												<p><a href="#">A Personal Response to American Citizenship</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="#">Video</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Lesson</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="">External Resource</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="#">Flipcard</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p><a href="#">Quiz</a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="practice-tab" role="tabpanel" aria-labelledby="nav-practice-tab">
								<p>Practice Tab</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
@endsection