@extends('layouts.admin')

@section('content')
<div class="row mt-2 mb-2">
    <div class="col">
        <h2>Lesson Management - Activities</h2>
    </div>
</div>
<div class="row mb-2">
    <div class="col">
        <div class="alert alert-primary" role="alert">
        	@if(isset($activity))
        		Edit lesson activity
        	@else
            	Create new lesson activity
        	@endif
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col">
        <form method="post" action='{{url("/admin/activities/save")}}' enctype="multipart/form-data">
            {{ csrf_field() }}
        	<input type="hidden" name="lesson_id" value="{{$lesson->id}}">
            <input type="hidden" name="lti_clientid" value="{{$lti_data['lti_clientid']}}">
            <input type="hidden" name="lti_publickey" value="{{$lti_data['lti_publickey']}}">
            <input type="hidden" name="lti_initiatelogin" value="{{$lti_data['lti_initiatelogin']}}">
            <input type="hidden" name="lti_redirectionuris" value="{{$lti_data['lti_redirectionuris']}}">
            @if(isset($activity))
                <input type="hidden" name="activity_id" value="{{$activity->id}}">
                <input type="hidden" name="submit_lti_tool" value="Update">
            @else
                <input type="hidden" name="submit_lti_tool" value="Add">
            @endif

            <div class="row">
                <div class="col">
                    <label for="title" class="mt-2">Type</label>
                    <select name="type" class="form-control" id="activity-type">
                        <option value="1">LTI</option>
                    </select>
                    <label for="title" class="mt-2">Title</label>
                    <input type="text" name="title" placeholder="Lesson Title" class="form-control" value="{{(isset($activity)) ? $activity->title:''}}">
                    @if(isset($activity))
                        <div class="alert alert-primary mt-2" role="alert">
                            Current thumbnail.
                        </div>
                        <img src="{{asset('storage/'.$activity->thumb)}}" class="d-block">
                    @endif
                    <label class="mt-2">Thumbnail</label>
                    <input type="file" name="thumb" class="form-control">
                    <label for="lti_ltiversion" class="mt-2">LTI Version</label>
                    <select name="lti_ltiversion" class="form-control">
                        <option value="LTI-1p0" {{($lti_data['lti_ltiversion'] == 'LTI-1p0') ? 'selected':'' }}>LTI 1.0/1.1</option>
                        <option value="1.3.0" {{($lti_data['lti_ltiversion'] == '1.3.0') ? 'selected':'' }}>LTI 1.3</option>
                    </select>
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input" name="lti_contentitem" {{($lti_data['lti_contentitem'] != '0') ? 'checked':'' }}>
                        <label class="form-check-label" for="lti_contentitem">Content Item Message</label>
                    </div>
                </div>
                <div class="col">
                    <label for="lti_typename" class="mt-2">Tool Name</label>
                    <input type="text" name="lti_typename" class="form-control" value="{{$lti_data['lti_typename']}}">
                    <label for="lti_toolurl" class="mt-2">Tool URL</label>
                    <input type="text" name="lti_toolurl" class="form-control" value="{{$lti_data['lti_toolurl']}}">
                    <label for="lti_description" class="mt-2">Tool Description</label>
                    <textarea name="lti_description" class="form-control">{{$lti_data['lti_description']}}</textarea>

                    <label for="lti_resourcekey" class="mt-2">Consumer Key</label>
                    <input type="text" name="lti_resourcekey" class="form-control" value="{{$lti_data['lti_resourcekey']}}">
                    <label for="lti_password" class="mt-2">Shared Secret</label>
                    <input type="text" name="lti_password" class="form-control" value="{{$lti_data['lti_password']}}">
                    <label for="lti_icon" class="mt-2">Icon URL</label>
                    <input type="text" name="lti_icon" class="form-control" value="{{$lti_data['lti_icon']}}">
                    <label for="lti_secureicon" class="mt-2">Secure Icon URL</label>
                    <input type="text" name="lti_secureicon" class="form-control" value="{{$lti_data['lti_secureicon']}}">
                    <label for="lti_customparameters" class="mt-2">Custom Parameters</label>
                    <textarea class="form-control" name="lti_customparameters">{{$lti_data['lti_customparameters']}}</textarea>
                </div>
            </div>
            <a class="btn btn-danger mt-4" href="{{url('/admin/lessons/view/'.$lesson->id)}}">Cancel</a>
            <button type="submit" class="btn btn-primary mt-4 float-right">Save</button>
        </form>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection