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
					<div class="row justify-content-md-center">
						<h3>Drag the options and drop them in the atomic diagram</h3>
					</div>
					<div class="row justify-content-md-center py-5">
						<div class="col-md-11">
							<div class="drag-block mb-2">
								<div class="row">
									<div class="col-md-3">
										<div class="btn btn-outline btn-muted btn-block activity-drop-target nucleus-target"></div>
									</div>
									<div class="col-md-3">
										<img class="img-fluid" src="{{asset('images/atom-diagram-for-draggable.png')}}" width="274" height="330" alt="Empty atom diagram">
									</div>
									<div class="col-md-3">
										<div class="btn btn-outline btn-muted activity-drop-target proton-target"></div>
										<div class="btn btn-outline btn-muted activity-drop-target neutron-target"></div>
										<div class="btn btn-outline btn-muted activity-drop-target electron-target"></div>
									</div>
									<div class="col-md-3">
										<div class="pt-4 px-md-5 mx-md-5 options">
											<div class="btn btn-outline btn-muted activity-drag-node nucleus-node">NUCLEUS</div>
											<div class="btn btn-outline btn-muted activity-drag-node proton-node">PROTON</div>
											<div class="btn btn-outline btn-muted activity-drag-node neutron-node">NEUTRON</div>
											<div class="btn btn-outline btn-muted activity-drag-node electron-node">ELECTRON</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right pt-4 mt-2">
				<a class="btn btn-blue" href="{{url('/lesson/1/activity/6')}}">Next activity</a>
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

	.nucleus-target {
		position:relative;
		top:125px;
	}

	.proton-target {
		position:relative;
		top:75px;
	}

	.neutron-target {
		position:relative;
		top:90px;
	}

	.electron-target {
		position:relative;
		top:125px;	
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
		    $( ".nucleus-target" ).droppable({
		      accept: ".nucleus-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.nucleus-node').html())
		      	$('.nucleus-node').addClass('node-hide');
		      }
		    });
		    $( ".proton-target" ).droppable({
		      accept: ".proton-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.proton-node').html())
		      	$('.proton-node').addClass('node-hide');
		      }
		    });
		    $( ".neutron-target" ).droppable({
		      accept: ".neutron-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.neutron-node').html())
		      	$('.neutron-node').addClass('node-hide');
		      }
		    });
		    $( ".electron-target" ).droppable({
		      accept: ".electron-node",
		      drop: function( event, ui ) { 
		      	$( this ).addClass( "node-correct" );
		      	$( this ).html($('.electron-node').html())
		      	$('.electron-node').addClass('node-hide');
		      }
		    });

		});
	</script>
@endsection