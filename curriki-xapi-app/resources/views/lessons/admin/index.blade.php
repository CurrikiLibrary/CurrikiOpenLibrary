@extends('layouts.admin')

@section('content')
<div class="row mt-5 mb-2">
    <div class="col">
        <h2>Lesson Management</h2>
    </div>
</div>
<div class="row mb-2">
    <div class="col">
        <div class="alert alert-primary" role="alert">
        	Create, edit and delete lessons
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <a class="btn btn-primary float-right" href="{{url('/admin/lessons/create')}}">Create Lesson</a>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col">Thumbnail</th>
						<th class="col">Title</th>
						<th class="col">Author</th>
						<th class="col">Activities</th>
						<th class="col">Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse ($lessons as $lesson)
					<tr>
						<td>
							@if(empty($lesson->thumb))
								<img src="{{asset('/images/logo.png')}}">
							@else
								<img src="{{asset('storage/'.$lesson->thumb)}}" class="thumb-image">
							@endif							
						</td>
						<td>{{ $lesson->title }}</td>
						<td>{{ $lesson->author }}</td>
						<td></td>
						<td>
							<a class="btn btn-primary d-block mb-1" href='{{url("/admin/lessons/view/{$lesson->id}")}}'>View Activities</a>
							<a class="btn btn-primary d-block mb-1" href='{{url("/admin/lessons/edit/{$lesson->id}")}}'>Edit Details</a>
							<a class="btn btn-danger d-block " href='{{url("/admin/lessons/delete/{$lesson->id}")}}'>Delete</a>
						</td>
					</tr>
				@empty
					<tr>
						<th colspan="5">No lessons found.</th>
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