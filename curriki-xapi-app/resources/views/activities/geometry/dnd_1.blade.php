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
					<div class="row justify-content-md-center">
						<h3>Drag the angle names and drop them next to the proper image</h3>
					</div>
					<div class="row justify-content-md-center py-5">
						<div class="col-md-11">
							<div class="drag-block mb-2">
								<div class="row">
									<div class="col-md-3">
										<img class="img-fluid" src="{{asset('images/activities/dnd_1_1.png')}}" width="">
										<img class="img-fluid" src="{{asset('images/activities/dnd_1_2.png')}}">
									</div>
									<div class="col-md-2">
										<div class="btn btn-outline btn-muted activity-drop-target right-target"></div>
										<div class="btn btn-outline btn-muted activity-drop-target acute-target"></div>
									</div>
									<div class="col-md-2">
										<div class="btn btn-outline btn-muted activity-drop-target flat-target"></div>
										<div class="btn btn-outline btn-muted activity-drop-target obtuse-target"></div>
									</div>
									<div class="col-md-3">
										<img class="img-fluid" src="{{asset('images/activities/dnd_1_3.png')}}">
										<img class="img-fluid obtuse-image" src="{{asset('images/activities/dnd_1_4.png')}}">
									</div>
									<div class="col-md-2 options">
										<div class="btn btn-outline btn-muted activity-drag-node right-node">Right Angle</div>
										<div class="btn btn-outline btn-muted activity-drag-node acute-node">Acute Angle</div>
										<div class="btn btn-outline btn-muted activity-drag-node flat-node">Flat Angle</div>
										<div class="btn btn-outline btn-muted activity-drag-node obtuse-node">Obtuse Angle</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/2/activity/8')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/2/activity/11')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('styles')
<style>
	.activity-drop-target{
		height: 50px;
		width: 200px;
		display:block;
	}

	.activity-drag-node{
		height: 45px;
		width: 200px;
		display:block;
	}

	.right-target {
		position:relative;
		top:100px;
	}

	.acute-target {
		position:relative;
		top: 250px;
	}

	.flat-target {
		position:relative;
		top:100px;
	}

	.obtuse-target {
		position:relative;
		top: 250px;
	}

	.obtuse-image {
		position:relative;
		top:100px;
	}

	.node-correct {
		border-color:#28a745;
		border-width: thick;
	}

	.options div {
		margin-top:2em;
	}

	.node-hide {
		display:none;
	}
</style>
@endsection
@section('scripts')
	<script>
		$(function() {
		    $( ".activity-drag-node" ).draggable({revert:'invalid'});
		    $( ".right-target" ).droppable({
		      accept: ".right-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.right-node').html())
		      	$('.right-node').addClass('node-hide');
		      }
		    });
		    $( ".obtuse-target" ).droppable({
		      accept: ".obtuse-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.obtuse-node').html())
		      	$('.obtuse-node').addClass('node-hide');
		      }
		    });
		    $( ".flat-target" ).droppable({
		      accept: ".flat-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.flat-node').html())
		      	$('.flat-node').addClass('node-hide');
		      }
		    });
		    $( ".acute-target" ).droppable({
		      accept: ".acute-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.acute-node').html())
		      	$('.acute-node').addClass('node-hide');
		      }
		    });

		});
	</script>
@endsection