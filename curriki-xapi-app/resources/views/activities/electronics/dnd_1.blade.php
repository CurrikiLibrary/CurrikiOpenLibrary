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
									<h4 class="font-size-18 d-inline-block align-middle">Sections of The Declaration</h4>
								</div>
								<div class="dropdown">
									<button class="btn btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-ellipsis-v"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="#"><i class="fa fa-question-circle" aria-hidden="true"></i> Help</a>
										<a class="dropdown-item" href="#"><i class="fa fa-share-alt" aria-hidden="true"></i> Share</a>
										<a class="dropdown-item" href="#"><i class="fa fa-flag" aria-hidden="true"></i> Report</a>
										<a class="dropdown-item" href="{{url('/lesson/4')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Exit</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="data-body" style="height:50em;">
					<div class="row justify-content-md-center">
						<h3>Drag first words of each section and drop them in the right container</h3>
					</div>
					<div class="row justify-content-md-center py-5">
						<div class="col-md-6">
							<div class="btn btn-outline btn-muted activity-drop-target intro-target">Introduction</div>
							<div class="btn btn-outline btn-muted activity-drop-target pre-target">Preamble</div>
							<div class="btn btn-outline btn-muted activity-drop-target indi-target">Indictment</div>
							<div class="btn btn-outline btn-muted activity-drop-target denun-target">Denunciation</div>
							<div class="btn btn-outline btn-muted activity-drop-target conclu-target">Conclusion</div>
							<div class="btn btn-outline btn-muted activity-drop-target sig-target">Signatures</div>
						</div>
						<div class="col-md-6 options">
							<div class="btn btn-outline btn-muted activity-drag-node intro-node">
								When in the Course of human events, it becomes necessary for one people...
							</div>
							<div class="btn btn-outline btn-muted activity-drag-node pre-node">
								We hold these truths to be self-evident, that all men are created equal...
							</div>
							<div class="btn btn-outline btn-muted activity-drag-node indi-node">
								He has refused his Assent to Laws, the most wholesome and necessary...
							</div>
							<div class="btn btn-outline btn-muted activity-drag-node denun-node">
								Nor have We been wanting in attentions to our Brittish brethren...
							</div>
							<div class="btn btn-outline btn-muted activity-drag-node conclu-node">
								We, therefore, the Representatives of the united States of America...
							</div>
							<div class="btn btn-outline btn-muted activity-drag-node sig-node">
								Samuel Adams
								John Adams
								Elbridge Gerry
								John Hancock
								Robert Treat Paine 			
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row pt-4">
				<div class="col-md-12">
					<a class="btn btn-blue pull-left" href="{{url('/lesson/4/activity/8')}}">Previous activity</a>
					<a class="btn btn-blue pull-right" href="{{url('/lesson/4/activity/10')}}">Next activity</a>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('styles')
<style>
	.activity-drop-target{
		margin-left:2em;
		margin-top:1em;
		height: 50px;
		width: 200px;
		display:block;
	}

	.activity-drag-node{
		word-wrap: break-word;
		white-space: normal;
		width:20em;
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
		    $( ".intro-target" ).droppable({
		      accept: ".intro-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	//$( this ).html($('.right-node').html())
		      	$('.intro-node').addClass('node-hide');
		      }
		    });
		    $( ".pre-target" ).droppable({
		      accept: ".pre-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	//$( this ).html($('.obtuse-node').html())
		      	$('.pre-node').addClass('node-hide');
		      }
		    });
		    $( ".indi-target" ).droppable({
		      accept: ".indi-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	//$( this ).html($('.flat-node').html())
		      	$('.indi-node').addClass('node-hide');
		      }
		    });
		    $( ".denun-target" ).droppable({
		      accept: ".denun-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	//$( this ).html($('.acute-node').html())
		      	$('.denun-node').addClass('node-hide');
		      }
		    });
		    $( ".conclu-target" ).droppable({
		      accept: ".conclu-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	//$( this ).html($('.acute-node').html())
		      	$('.conclu-node').addClass('node-hide');
		      }
		    });
		    $( ".sig-target" ).droppable({
		      accept: ".sig-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	//$( this ).html($('.acute-node').html())
		      	$('.sig-node').addClass('node-hide');
		      }
		    });

		});
	</script>
@endsection