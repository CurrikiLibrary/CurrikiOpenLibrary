@extends('layouts.admin')

@section('content')
<div class="row mt-5 mb-2">
    <div class="col">
        <h2>Lesson Management - Activities</h2>
        <a href="{{url('/admin/lessons')}}" class="btn btn-primary float-right">Back</a>
    </div>
</div>
<div class="row mb-2">
    <div class="col">
        <div class="alert alert-primary" role="alert">
        	Create, edit and delete activities for this lesson.
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col">
		<div class="card">
		  <div class="card-body">
			<div class="row mb-2">
			    <div class="col-4">
					@if(empty($lesson->thumb))
						<img src="{{asset('/images/logo.png')}}">
					@else
						<img src="{{asset('storage/'.$lesson->thumb)}}">
					@endif
			    </div>
			    <div class="col-8">
			    	<h2>{{$lesson->title}}</h2>
			    	<p>{{$lesson->description}}</p>
			    	<p class="float-right">By: {{$lesson->author}}</p>
			    </div>
			</div>
			<div class="row mb-2">
			    <div class="col">
					<a class="btn btn-primary float-right" href='{{url("/lessons/{$lesson->id}")}}'>
						View Lesson
					</a>
			    </div>
			</div>
		  </div>
		</div>
    </div>
</div>
<div class="row mb-4">
    <div class="col">
        <a class="btn btn-primary float-right" href="{{url('/admin/activities/create').'?lesson_id='.$lesson->id}}">Add Activity</a>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Thumbnail</th>
						<th>Title</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse ($lesson->activities as $activity)
					<tr>
						<td>
							@if(empty($lesson->thumb))
								<img src="{{asset('/images/logo.png')}}">
							@else
								<img src="{{asset('storage/'.$activity->thumb)}}" class="thumb-image">
							@endif							
						</td>
						<td>{{ $activity->title }}</td>
						<td>LTI</td>
						<td>
							<a class="btn btn-primary d-block mb-1" href='{{url("/activities/{$activity->id}")}}'>View</a>
							<a class="btn btn-primary d-block mb-1" href='{{url("/admin/activities/edit/{$activity->id}")}}'>Edit</a>
							<a class="btn btn-danger d-block " href='{{url("/admin/activities/delete/{$activity->id}")}}'>Delete</a>
						</td>
					</tr>
				@empty
					<tr>
						<th colspan="4">No activities found for this lesson.</th>
					</tr>
				@endforelse
				</tbody>
			</table>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection