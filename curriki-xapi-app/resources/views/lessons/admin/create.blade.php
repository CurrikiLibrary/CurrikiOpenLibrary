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
        	@if(isset($lesson))
        		Edit Lesson Details
        	@else
            	Create new lesson.
        	@endif
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
        <form method="post" action='{{url("/admin/lessons/save")}}' enctype="multipart/form-data">
            {{ csrf_field() }}
        	@if(isset($lesson))
        		<input type="hidden" name="lesson_id" value="{{$lesson->id}}">
        	@endif
            <label for="title">Title</label>
            <input type="text" name="title" placeholder="Lesson Title" class="form-control" value="{{ (isset($lesson)) ? $lesson->title: '' }}">
            <label for="author" class="mt-4">Author</label>
            <input type="text" name="author" placeholder="Lesson Author" class="form-control" value="{{ (isset($lesson)) ? $lesson->author: '' }}">
            <label for="description" class="mt-4">Description</label>
            <textarea name="description" class="form-control">{{ (isset($lesson)) ? $lesson->description: '' }}</textarea>
            @if(isset($lesson) && !empty($lesson->thumb))
                <div class="row mt-4">
                    <div class="col">
                        <label class="mt-4">Thumbnail</label>
                        <input type="file" name="thumb" class="form-control">
                    </div>
                    <div class="col">
                        <div class="alert alert-primary" role="alert">
                            Current thumbnail.
                        </div>
                        <img src="{{asset('storage/'.$lesson->thumb)}}">
                    </div>
                </div>
            @else
                <label class="mt-4">Thumbnail</label>
                <input type="file" name="thumb" class="form-control">
            @endif

            <a class="btn btn-danger mt-4" href="{{url('/admin/lessons')}}">Cancel</a>
            <button type="submit" class="btn btn-primary mt-4 float-right">Save</button>
        </form>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection