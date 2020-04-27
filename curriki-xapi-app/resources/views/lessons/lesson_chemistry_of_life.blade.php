@extends('layouts.main_layout')
@section('content')
	<div class="container mb-4 pb-2">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb pb-4 mb-4">
				<li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
				<li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
				<li class="breadcrumb-item">Science</li>
				<li class="breadcrumb-item">Chemistry</li>
				<li class="breadcrumb-item active" aria-current="page">The Chemistry of life</li>
			</ol>
		</nav>
		<div class="row">
			<div class="col-md-3">
				<div class="sidebar mb-4 mb-md-0">
					<div class="side-image mb-2">
						<img class="img-fluid img-radius-all" src="{{asset('images/chemistry-of-life-large.jpg')}}" width="309" height="301" alt="Chemistry of life">
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
					<h3 class="font-size-18 mb-3">Chemistry of life</h3>
					<p>Chemistry is the branch of science that studies the properties of matter and how matter interacts with energy. Chemistry is considered a physical science and is closely related to physics. Sometimes chemistry is called the "central</p>
					<div class="text-right mb-3">
						<a class="underline" href="#">Read more</a>
					</div>
					<div class="media mb-3">
						<img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/smith-thumbnail.jpg')}}" width="52" height="52" alt="Mr. Roberts">
						<div class="media-body my-auto">
							<div class="name font-weight-bold">Mr. Roberts</div>
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
										<h3 class="font-size-18 font-weight-semibold text-black">The atoms</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2 justify-content-center align-items-center ">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p><a href="{{ url('/lesson/1/activity/1') }}">Basic Particles</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p><a href="{{ url('/lesson/1/activity/2') }}">The Nucleus</a></p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p>Protons and Neutrons</p>
											</div>
											<div class="item">
												<a href="water-molecule.html">
													<div class="icon-box mb-2">
														<i class="fa fa-check active"></i>
														<i class="icon-v3 icon-structure"></i>
													</div>
													<p>Atomic Structure Lab</p>
												</a>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p>Elements and atoms</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p>The atoms</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-file"></i>
												</div>
												<p>Elements and atoms</p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">Molecules and compounds</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p>Molecules</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p>Molecules: chemical formulas</p>
											</div>
											<div class="item">
												<a href="argumented-reality.html">
													<div class="icon-box mb-2">
														<i class="fa fa-check active"></i>
														<i class="icon-v3 icon-molecules"></i>
													</div>
													<p>Molecules: Structural Models</p>
												</a>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check"></i>
													<i class="icon-v3 icon-files-muted-o"></i>
												</div>
												<p>Molecules: Structural formulas</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-play"></i>
												</div>
												<p>Molecules</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-atoms-blue"></i>
												</div>
												<p>Molecules: chemical formulas</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-check active"></i>
													<i class="icon-v3 icon-molecules"></i>
												</div>
												<p>Molecules: Structural Models</p>
											</div>
										</div>
									</div>
								</div>
								<div class="ibox mb-4">
									<div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
										<h3 class="font-size-18 font-weight-semibold text-black">Chemical links and reactions</h3>
									</div>
									<div class="ibox-body">
										<div class="owl-carousel owl-theme owl-secondary">
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-play-muted"></i>
												</div>
												<p>Ionic, covalent, and metallic bonds</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-play-muted"></i>
												</div>
												<p>Electronegativity</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-files-muted-o"></i>
												</div>
												<p>Chemical bonds</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-file-muted"></i>
												</div>
												<p>Chemical reactions</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-play-muted"></i>
												</div>
												<p>Ionic, covalent, and metallic bonds</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-play-muted"></i>
												</div>
												<p>Electronegativity</p>
											</div>
											<div class="item">
												<div class="icon-box mb-2">
													<i class="fa fa-lock"></i>
													<i class="icon-v3 icon-files-muted-o"></i>
												</div>
												<p>Chemical bonds</p>
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