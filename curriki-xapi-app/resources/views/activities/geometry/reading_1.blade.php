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
					<div class="row justify-content-md-center py-4">
						<div class="col-md-11">
							<div class="row">
								<div class="col-md-12">
									<div class="font-size-16">
										<h3 class="font-size-18 mb-4 pb-3">Angles and Lines</h3>
										<p>A line segment is a portion of a line with two endpoints. A ray is a portion of a line with one endpoint. Line segments are named by their endpoints and rays are named by their endpoint and another point. In each case, a segment or ray symbol is written above the points. Below, the line segment is AB¯¯¯¯¯¯¯¯ and the ray is CD−→−.</p>
										<h4 class="font-size-16 mb-4">An atom consists of two regions.</h4>
										<p>The first is the tiny atomic nucleus, which is in the center of the atom 
										and contains positively charged particles</p>
										<img class="img-fluid" src="{{asset('images/activities/reading_1.png')}}" >
										<p>When two rays meet at their endpoints, they form an angle. Depending on the situation, an angle can be named with an angle symbol and by its vertex or by three letters. If three letters are used, the middle letter should be the vertex. The angle below could be called ∠B or ∠ABC or ∠CBA. Use three letters to name an angle if using one letter would not make it clear what angle you are talking about.
										</p>
										<img class="img-fluid" src="{{asset('images/activities/reading_2.png')}}" >
										<p>Angles are measured in degrees. You can use a protractor or geometry software to measure angles. Remember that a full circle has 360∘.</p>
										<img class="img-fluid" src="{{asset('images/activities/reading_3.png')}}" >
										<p>An angle that is exactly 90∘ (one quarter of a circle) is called a right angle. A right angle is noted with a little square at its vertex. An angle that is more than 90∘ but less than 180∘ is called an obtuse angle. An angle that is less than 90∘  is called an acute angle. An angle that is exactly 180∘ (one half of a circle) is called a straight angle.</p>
										<img class="img-fluid" src="{{asset('images/activities/reading_4.png')}}" >
										<p>Two angles are complementary if the sum of their measures is 90∘. Two angles are supplementary if the sum of their measures is 180∘. Two angles that together form a straight angle will always be supplementary. When two lines intersect, many angles are formed, as shown below.</p>
										<img class="img-fluid" src="{{asset('images/activities/reading_5.png')}}" >
										<p>In the diagram above ∠AEC and ∠AED are adjacent angles because they are next to each other and share a ray. They are also supplementary because together they form a straight angle. ∠AEC and ∠DEB are called vertical angles. You can show that vertical angles will always have the same measure.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/2/activity/1')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/2/activity/3')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection