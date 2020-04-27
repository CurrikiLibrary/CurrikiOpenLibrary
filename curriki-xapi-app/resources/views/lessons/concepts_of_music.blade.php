@extends('layouts.main_layout')
@section('content')
<div class="container mb-4 pb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb pb-4 mb-4">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
            <li class="breadcrumb-item">Electronics</li>
            <li class="breadcrumb-item active" aria-current="page">Electronic Tech.</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar mb-4 mb-md-0">
                <div class="side-image mb-2">
                    <img class="img-fluid img-radius-all" src="{{asset('images/being-american-thumb.png')}}" width="309" height="301" alt="Chemistry of life">
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                </div>
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center w-100 mb-2 font-size-18">
                    <a class="text-muted no-underline" href="#"><i class="fa fa-users"></i>&nbsp; 60K</a>
                    <a class="text-muted no-underline" href="#"><i class="fa fa-heart text-red"></i>&nbsp; 30K</a>
                </div>
                <h3 class="font-size-18 mb-3">Electronics Technology</h3>
                <p>
                    The Electronics Technology program prepares you for occupations in today’s booming industrial sectors – telecommunications, medical equipment, control systems, automotive systems, navigational systems, and consumer appliances – all require trained electronics technology professionals to build, maintain, and repair technical infrastructures
                </p>
                <p>
                    SOC Codes (Standard Occupational Classification) related to Electronic Technology careers: 17-3023.00 & 17- 3023.01
                </p>
                <div class="media mb-3">
                    <img class="img-thumb-publication rounded-circle mr-3" src="{{asset('images/ta_edison.jpg')}}" width="52" height="52" alt="Mr. Roberts">
                    <div class="media-body my-auto">
                        <div class="name font-weight-bold">Mr. T.A. Edison</div>
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
                        <a class="nav-link" href="#practice-tab" id="nav-practice-tab" data-toggle="tab" aria-controls="practice-tab" aria-selected="false"><i class="fa fa-ticket"></i> <span class="d-none d-md-inline">Progress Monitor</span></a>
                    </li>
                </ul>
                <div class="content-box">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="activities-tab" role="tabpanel" aria-labelledby="nav-activities-tab">
                            <div class="ibox mb-4">
                                <div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
                                    <h3 class="font-size-18 font-weight-semibold text-black">Electronic Control Devices</h3>
                                </div>
                                <div class="ibox-body">
                                    <div class="owl-carousel owl-theme owl-secondary">
                                        <div class="item">
                                            <div class="icon-box mb-2 justify-content-center align-items-center ">
                                                <i class="fa fa-check active"></i>
                                                <img class="lesson-icon" src="{{asset('images/electronic-control-devices.png')}}"alt="Image of a scroll">
                                            </div>
                                            <p><a href="#">Electronic Control Devices</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2 justify-content-center align-items-center ">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-play"></i>
                                            </div>
                                            <p><a href="{{ url('/lesson/4/activity/1') }}">Video: A Sample Control Device</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="{{ url('/lesson/4/activity/2') }}">Document: The Basics of Control Devices</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="{{ url('/lesson/4/activity/3') }}">Document: Kinds of Control Devices</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="{{ url('/lesson/4/activity/4') }}">Workshop: Apply Your Knowledge</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="{{ url('/lesson/4/activity/5') }}">Flipcards: Control Devices</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-file"></i>
                                            </div>
                                            <p><a href="{{ url('/lesson/4/activity/9') }}">Quiz</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox mb-4">
                                <div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
                                    <h3 class="font-size-18 font-weight-semibold text-black">Operational Amplifiers</h3>
                                </div>
                                <div class="ibox-body">
                                    <div class="owl-carousel owl-theme owl-secondary">
                                        <div class="item">
                                            <div class="icon-box mb-2 justify-content-center align-items-center ">
                                                <i class="fa fa-check active"></i>
                                                <img class="lesson-icon" src="{{asset('images/operational-amplifiers.png')}}"alt="Image of a scroll">
                                            </div>
                                            <p><a href="#">Operational Amplifiers</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2 justify-content-center align-items-center ">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-play"></i>
                                            </div>
                                            <p><a href="#">Operational Amplifiers</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="#">Lesson</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="">External Resource</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="#">Flipcard</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-file"></i>
                                            </div>
                                            <p><a href="#">Quiz</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox mb-4">
                                <div class="d-flex justify-content-between align-items-center w-100 font-size-18 pb-4">
                                    <h3 class="font-size-18 font-weight-semibold text-black">Zener Regulators</h3>
                                </div>
                                <div class="ibox-body">
                                    <div class="owl-carousel owl-theme owl-secondary">
                                        <div class="item">
                                            <div class="icon-box mb-2 justify-content-center align-items-center ">
                                                <i class="fa fa-check active"></i>
                                                <img class="lesson-icon" src="{{asset('images/zener-regulators.png')}}"alt="Image of a scroll">
                                            </div>
                                            <p><a href="#">Zener Regulators</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2 justify-content-center align-items-center ">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-play"></i>
                                            </div>
                                            <p><a href="#">Video</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="#">Lesson</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="">External Resource</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-atoms-blue"></i>
                                            </div>
                                            <p><a href="#">Flipcard</a></p>
                                        </div>
                                        <div class="item">
                                            <div class="icon-box mb-2">
                                                <i class="fa fa-check active"></i>
                                                <i class="icon-v3 icon-file"></i>
                                            </div>
                                            <p><a href="#">Quiz</a></p>
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