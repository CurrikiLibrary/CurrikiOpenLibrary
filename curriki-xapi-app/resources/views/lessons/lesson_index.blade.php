@extends('layouts.public')

@section('content')
<div class="container mb-4 pb-2">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb pb-4 mb-4">
			<li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{$lesson->title}}</li>
		</ol>
	</nav>
	<div class="row">
		<div class="col-md-3">
			<div class="sidebar mb-4 mb-md-0">
				<div class="side-image mb-2">
					<img class="img-fluid img-radius-all" src="{{asset('storage/'.$lesson->thumb)}}" width="309" height="301" alt="{{$lesson->title}}">
					<div class="rating">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star not-rated"></i>
					</div>
				</div>
				<div class="progress mb-2">
					<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="d-flex justify-content-between align-items-center w-100 mb-2 font-size-18">
					<a class="text-muted no-underline" href="#"><i class="fa fa-users"></i>&nbsp; 60K</a>
					<a class="text-muted no-underline" href="#"><i class="fa fa-heart text-red"></i>&nbsp; 30K</a>
				</div>
				<h3 class="font-size-18 mb-3">{{$lesson->title}}</h3>
				<p>{{$lesson->description}}</p>
				<div class="text-right mb-3">
					<a class="underline" href="#">Read more</a>
				</div>
				<div class="media mb-3">
					<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/smith-thumbnail.jpg')}}" width="52" height="52" alt="Mr. Roberts">
					<div class="media-body my-auto">
						<div class="name font-weight-bold">{{$lesson->author}}</div>
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
									<h3 class="font-size-18 font-weight-semibold text-black">{{$lesson->title}}</h3>
								</div>
								<div class="ibox-body">
									<div class="owl-carousel owl-theme owl-secondary">
										@foreach($lesson->activities as $activity)
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<img class="img-fluid img-radius-all" src="{{asset('storage/'.$activity->thumb)}}" width="50" height="50" alt="{{$lesson->title}}">
												</div>
												<p>
													<a href="{{ url('/activities/'.$activity->id) }}">
														{{$activity->title}}
													</a>
												</p>
											</div>
										@endforeach
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

@section('styles')
@endsection

@section('scripts')
@endsection