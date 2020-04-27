@extends('layouts.public')
@section('content')

@include('bannerdashboard')
<div class="container mb-4 pb-2">
	<div class="shadow-box">
		<ul class="nav nav-tabs nav-fill" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" href="#dashboard-tab" id="nav-dashboard-tab" data-toggle="tab" aria-controls="dashboard-tab" aria-selected="true"><i class="fa fa-dashboard"></i> <span class="d-none d-md-inline">Dashboard</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#missions-tab" id="nav-missions-tab" data-toggle="tab" aria-controls="missions-tab" aria-selected="false"><i class="fa fa-rocket"></i> <span class="d-none d-md-inline">Missions</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#laboratory-tab" id="nav-laboratory-tab" data-toggle="tab" aria-controls="laboratory-tab" aria-selected="false"><i class="fa fa-flask"></i> <span class="d-none d-md-inline">Laboratory</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#community-tab" id="nav-community-tab" data-toggle="tab" aria-controls="community-tab" aria-selected="false"><i class="fa fa-users"></i> <span class="d-none d-md-inline">Community</span></a>
			</li>				
		</ul>
		<div class="content-box">
			<div class="tab-content">
				<div class="tab-pane fade show active" id="dashboard-tab" role="tabpanel" aria-labelledby="nav-dashboard-tab">
					<form class="form-dashboard mb-5" method="get" action="#">
						<div class="input-addon">
							<i class="fa fa-calendar"></i>
							<input class="form-control" type="text" placeholder="Reminders: Chemistry Test Thu 9:00 AM - Biology Presentation Fri 10:00 AM - Math Test Fri 1:00 PM...">
							<i class="fa fa-angle-down"></i>
						</div>
					</form>
					<div class="ibox mb-4">
						<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
							<h3 class="font-size-18 font-weight-semibold text-black">My Programs</h3>
							<a class="underline" href="#">See All</a>
						</div>
						<div class="ibox-body">
							<div class="owl-carousel owl-theme">
								@foreach($lessons as $lesson)
									<div class="item">
										<div class="thumbnail mb-2">
											<a href="#">
												<img class="thumb-image img-radius-all" src="{{asset('storage/'.$lesson->thumb)}}" alt="{{$lesson->title}}">
											</a>
											<div class="thumbnail-overlay" onclick="location.href='{{ url('/lessons/'.$lesson->id) }}'" style="cursor:pointer;">
												<div class="overlay-icon">
													<a href="#"><i class="fa fa-heart"></i></a>
												</div>
												<div class="overlay-icon">
													<a href="#"><i class="fa fa-share-alt"></i></a>
												</div>
												<div class="d-flex justify-content-between align-items-center w-100">
													<a href="#"><i class="fa fa-users"></i>&nbsp; 100K</a>
													<a href="#"><i class="fa fa-star-o"></i>&nbsp; 5</a>
												</div>											
											</div>
										</div>
										<div class="caption">
											<div class="progress mb-2">
												<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											<h4 class="font-size-14 font-weight-normal"><a href="{{url('/lessons/'.$lesson->id)}}">{{$lesson->title}}</a></h4>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
					<div class="ibox mb-4">
						<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
							<h3 class="font-size-18 font-weight-semibold text-black">Recent Documents</h3>
							<a class="underline" href="#">See All</a>
						</div>
						<div class="ibox-body">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/atoms.jpg')}}" width="310" height="185" alt="Atoms">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2">P</i> Atoms elements ...</div>
										<div class="recent-time">Opened 2 hours ago</div>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/literary-analysis.jpg')}}" width="310" height="185" alt="Literary analysis">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2 icon-blue">W</i> Literary analysis</div>
										<div class="recent-time">Opened 1 day ago</div>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/declaration-indy-thumb.png')}}" width="310" height="185" alt="Declaration of Independence">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2">P</i> Declaration of Independence</div>
										<div class="recent-time">Opened 1 week ago</div>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/analytic-geometry-basic-concepts.jpg')}}" width="310" height="185" alt="Analytic Geometry Basic Concepts">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2">P</i> Analytic Geometry Basic Concepts</div>
										<div class="recent-time">Opened 2 months ago</div>
									</div>
								</div>								
							</div>
						</div>
					</div>
					<div class="ibox">
						<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
							<h3 class="font-size-18 font-weight-semibold text-black">Community Announcements</h3>
							<a class="underline" href="#">See All</a>
						</div>
						<div class="ibox-body">
							<div class="owl-carousel owl-theme owl-publication">
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.smith.png')}}" width="52" height="52" alt="Miss. Smith">
											<div class="media-body">
												<div class="name font-weight-bold">Miss Smith</div>
												<div class="date text-gray">8 May</div>
											</div>
										</div>
										<p>The Art and Flowers Festival will be on Monday, July 24th.</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/mr.roberts.png')}}" width="52" height="52" alt="Mr. Roberts">
											<div class="media-body">
												<div class="name font-weight-bold">Mr. Roberts</div>
												<div class="date text-gray">5 May</div>
											</div>
										</div>
										<p>Summer camping trip has been scheduled!</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/camping.jpg')}}" width="310" height="156" alt="camping">
								</div>
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/christopher.b.png')}}" width="52" height="52" alt="Christopher B.">
											<div class="media-body">
												<div class="name font-weight-bold">Christopher B.</div>
												<div class="date text-gray">25 April</div>
											</div>
										</div>
										<p>See my last project about how I built a home robot <br>
										<a href="#">#robots</a> <a href="#">#homerobots</a> <a href="#">#currikiprojects</a>
										</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.clemant.png')}}" width="52" height="52" alt="Mrs. Clemant">
											<div class="media-body">
												<div class="name font-weight-bold">Mrs. Clemant</div>
												<div class="date text-gray">13 April</div>
											</div>
										</div>
										<p>The 1st Music Festival will...</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/music-festival.jpg')}}" width="310" height="156" alt="music festival">
								</div>
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.smith.png')}}" width="52" height="52" alt="Miss. Smith">
											<div class="media-body">
												<div class="name font-weight-bold">Miss. Smith</div>
												<div class="date text-gray">8 May</div>
											</div>
										</div>
										<p>The Art and Flowers Festival will be on Monday, July 24th.</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/mr.roberts.png')}}" width="52" height="52" alt="Mr. Roberts">
											<div class="media-body">
												<div class="name font-weight-bold">Mr. Roberts</div>
												<div class="date text-gray">5 May</div>
											</div>
										</div>
										<p>Let's go camping next week!</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/camping.jpg')}}" width="310" height="156" alt="camping">
								</div>
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/christopher.b.png')}}" width="52" height="52" alt="Christopher B.">
											<div class="media-body">
												<div class="name font-weight-bold">Christopher B.</div>
												<div class="date text-gray">25 April</div>
											</div>
										</div>
										<p>See my last project about how I built a home robot <br>
										<a href="#">#robots</a> <a href="#">#homerobots</a> <a href="#">#currikiprojects</a>
										</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.clemant.png')}}" width="52" height="52" alt="Mrs. Clemant">
											<div class="media-body">
												<div class="name font-weight-bold">Mrs. Clemant</div>
												<div class="date text-gray">13 April</div>
											</div>
										</div>
										<p>The 1st Music Festival will...</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/music-festival.jpg')}}" width="310" height="156" alt="music festival">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="missions-tab" role="tabpanel" aria-labelledby="nav-missions-tab">
					<form class="form-dashboard mb-5" method="get" action="#">
						<div class="input-addon">
							<i class="fa fa-calendar"></i>
							<input class="form-control" type="text" placeholder="Reminders: Chemistry Test Thu 9:00 AM - Biology Presentation Fri 10:00 AM - Math Test Fri 1:00 PM...">
							<i class="fa fa-angle-down"></i>
						</div>
					</form>
					<div class="ibox mb-4">
						<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
							<h3 class="font-size-18 font-weight-semibold text-black">My Programs</h3>
							<a class="underline" href="#">See All</a>
						</div>
						<div class="ibox-body">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="thumbnail mb-2">
										<a href="#">
											<img class="img-fluid img-radius-all" src="{{asset('images/being-american-thumb.png')}}" width="310" height="211" alt="Chemistry of life">
										</a>
										<div class="thumbnail-overlay" onclick="location.href='{{ url('/lesson/3') }}'" style="cursor:pointer;">
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-heart"></i></a>
											</div>
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-share-alt"></i></a>
											</div>
											<div class="d-flex justify-content-between align-items-center w-100">
												<a href="#"><i class="fa fa-users"></i>&nbsp; 100K</a>
												<a href="#"><i class="fa fa-star-o"></i>&nbsp; 5</a>
											</div>											
										</div>
									</div>
									<div class="caption">
										<div class="progress mb-2">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<h4 class="font-size-14 font-weight-normal"><a href="{{url('/lesson/3')}}">Being An American</a></h4>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail mb-2">
										<a href="#">
											<img class="img-fluid img-radius-all" src="{{asset('images/chemistry-of-life.jpg')}}" width="310" height="211" alt="Chemistry of life">
										</a>
										<div class="thumbnail-overlay" onclick="location.href='{{ url('/lesson/1') }}'" style="cursor:pointer;">
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-heart"></i></a>
											</div>
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-share-alt"></i></a>
											</div>
											<div class="d-flex justify-content-between align-items-center w-100">
												<a href="#"><i class="fa fa-users"></i>&nbsp; 60K</a>
												<a href="#"><i class="fa fa-star-o"></i>&nbsp; 4</a>
											</div>											
										</div>
									</div>
									<div class="caption">
										<div class="progress mb-2">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<h4 class="font-size-14 font-weight-normal"><a href="{{ url('/lesson/1') }}">Chemistry of life</a></h4>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail mb-2">
										<a href="">
											<img class="img-fluid img-radius-all" src="{{asset('images/analytic-geometry.jpg')}}" width="310" height="211" alt="Analytic Geometry">
										</a>
										<div class="thumbnail-overlay" onclick="location.href='{{ url('/lesson/2') }}'" style="cursor:pointer;">
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-heart"></i></a>
											</div>
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-share-alt"></i></a>
											</div>
											<div class="d-flex justify-content-between align-items-center w-100">
												<a href="#"><i class="fa fa-users"></i>&nbsp; 60K</a>
												<a href="#"><i class="fa fa-star-o"></i>&nbsp; 4</a>
											</div>											
										</div>
									</div>
									<div class="caption">
										<div class="progress mb-2">
											<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
										<h4 class="font-size-14 font-weight-normal"><a href="{{ url('/lesson/2') }}">Analytic Geometry</a></h4>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail mb-2">
										<a  href="{{ url('/lesson/4') }}">
                                                                                    <img class="img-fluid img-radius-all" src="{{asset('images/electronics-tech.png')}}" alt="Electronic Tech">
										</a>
                                                                            <div class="thumbnail-overlay" onclick="location.href='{{ url('/lesson/4') }}'" style="cursor:pointer;">
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-heart"></i></a>
											</div>
											<div class="overlay-icon">
												<a href="#"><i class="fa fa-share-alt"></i></a>
											</div>
											<div class="d-flex justify-content-between align-items-center w-100">
												<a href="#"><i class="fa fa-users"></i>&nbsp; 60K</a>
												<a href="#"><i class="fa fa-star-o"></i>&nbsp; 4</a>
											</div>											
										</div>
									</div>
									<div class="caption">
										<div class="progress mb-2">
											<div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
                                                                                <h4 class="font-size-14 font-weight-normal"><a href="{{ url('/lesson/4') }}">Introduction to Electronics</a></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox mb-4">
						<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
							<h3 class="font-size-18 font-weight-semibold text-black">Recent Documents</h3>
							<a class="underline" href="#">See All</a>
						</div>
						<div class="ibox-body">
							<div class="owl-carousel owl-theme">
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/atoms.jpg')}}" width="310" height="185" alt="Atoms">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2">P</i> Atoms elements ...</div>
										<div class="recent-time">Opened 2 hours ago</div>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/literary-analysis.jpg')}}" width="310" height="185" alt="Literary analysis">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2 icon-blue">W</i> Literary analysis</div>
										<div class="recent-time">Opened 1 day ago</div>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/declaration-indy-thumb.png')}}" width="310" height="185" alt="Declaration of Independence">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2">P</i> Declaration of Independence</div>
										<div class="recent-time">Opened 1 week ago</div>
									</div>
								</div>
								<div class="item">
									<div class="thumbnail">
										<a href="#">
											<img class="img-fluid img-radius-top" src="{{asset('images/analytic-geometry-basic-concepts.jpg')}}" width="310" height="185" alt="Analytic Geometry Basic Concepts">
										</a>
									</div>
									<div class="recent-desc">
										<div class="recent-title"><i class="icon-v2">P</i> Analytic Geometry Basic Concepts</div>
										<div class="recent-time">Opened 2 months ago</div>
									</div>
								</div>								
							</div>
						</div>
					</div>
					<div class="ibox">
						<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
							<h3 class="font-size-18 font-weight-semibold text-black">Community Announcements</h3>
							<a class="underline" href="#">See All</a>
						</div>
						<div class="ibox-body">
							<div class="owl-carousel owl-theme owl-publication">
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.smith.png')}}" width="52" height="52" alt="Miss. Smith">
											<div class="media-body">
												<div class="name font-weight-bold">Miss Smith</div>
												<div class="date text-gray">8 May</div>
											</div>
										</div>
										<p>The Art and Flowers Festival will be on Monday, July 24th.</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/mr.roberts.png')}}" width="52" height="52" alt="Mr. Roberts">
											<div class="media-body">
												<div class="name font-weight-bold">Mr. Roberts</div>
												<div class="date text-gray">5 May</div>
											</div>
										</div>
										<p>Summer camping trip has been scheduled!</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/camping.jpg')}}" width="310" height="156" alt="camping">
								</div>
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/christopher.b.png')}}" width="52" height="52" alt="Christopher B.">
											<div class="media-body">
												<div class="name font-weight-bold">Christopher B.</div>
												<div class="date text-gray">25 April</div>
											</div>
										</div>
										<p>See my last project about how I built a home robot <br>
										<a href="#">#robots</a> <a href="#">#homerobots</a> <a href="#">#currikiprojects</a>
										</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.clemant.png')}}" width="52" height="52" alt="Mrs. Clemant">
											<div class="media-body">
												<div class="name font-weight-bold">Mrs. Clemant</div>
												<div class="date text-gray">13 April</div>
											</div>
										</div>
										<p>The 1st Music Festival will...</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/music-festival.jpg')}}" width="310" height="156" alt="music festival">
								</div>
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.smith.png')}}" width="52" height="52" alt="Miss. Smith">
											<div class="media-body">
												<div class="name font-weight-bold">Miss. Smith</div>
												<div class="date text-gray">8 May</div>
											</div>
										</div>
										<p>The Art and Flowers Festival will be on Monday, July 24th.</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/mr.roberts.png')}}" width="52" height="52" alt="Mr. Roberts">
											<div class="media-body">
												<div class="name font-weight-bold">Mr. Roberts</div>
												<div class="date text-gray">5 May</div>
											</div>
										</div>
										<p>Let's go camping next week!</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/camping.jpg')}}" width="310" height="156" alt="camping">
								</div>
								<div class="item">
									<div class="item-top">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/christopher.b.png')}}" width="52" height="52" alt="Christopher B.">
											<div class="media-body">
												<div class="name font-weight-bold">Christopher B.</div>
												<div class="date text-gray">25 April</div>
											</div>
										</div>
										<p>See my last project about how I built a home robot <br>
										<a href="#">#robots</a> <a href="#">#homerobots</a> <a href="#">#currikiprojects</a>
										</p>
										<div class="d-flex justify-content-between align-items-center w-100 font-size-18">
											<a class="text-muted" href="#"><i class="fa fa-share-alt"></i></a>
											<a class="text-muted" href="#"><i class="fa fa-heart"></i></a>
										</div>
									</div>
								</div>
								<div class="item">
									<div class="item-top pb-0">
										<div class="media mb-2">
											<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ms.clemant.png')}}" width="52" height="52" alt="Mrs. Clemant">
											<div class="media-body">
												<div class="name font-weight-bold">Mrs. Clemant</div>
												<div class="date text-gray">13 April</div>
											</div>
										</div>
										<p>The 1st Music Festival will...</p>
									</div>
									<img class="img-fluid img-radius-bottom" src="{{asset('images/music-festival.jpg')}}" width="310" height="156" alt="music festival">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="laboratory-tab" role="tabpanel" aria-labelledby="nav-laboratory-tab">
					<p>Laboratory Tab</p>
				</div>
				<div class="tab-pane fade" id="community-tab" role="tabpanel" aria-labelledby="nav-community-tab">
					<p>Community Tab</p>
				</div>
			</div>
		</div>
	</div>
</div>	
@endsection
