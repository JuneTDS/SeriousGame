@extends('layouts.backendSystem_layout')

@section('content')

<div class="normal-text" style="margin-left: 5%;">
    <a href="/admin/classCodesDashboard" class="align-self-center col-3 normal-text" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Class Code</a>
</div>

<div class="container">
    <div class="header-row">
        <div class="left"><h3>{{ $classCodeData->class_code }}’s Profile</h3></div>
        <div class="right" >
            <div class="row">
                <div class="col-6">
                    <form method="GET" action="/admin/classCodeEdit/{{ $classCodeData->class_code_id }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark" style="width:200px">Update</button>
                    </form>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-danger delete-popup-btn" style="width:200px" data-id="{{ $classCodeData->class_code_id }}">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="delete-popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to delete {{ $classCodeData->class_code }}’s record?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete User</button>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <!-- Second column with User Information -->
            <div class="row" style="padding-left:20px">
                <!-- First sub-column -->
                <div class="col-md-9">
                    <table class="table leftTable" style="border: none;">
                        <tr>
                            <td style="font-weight:bold">Class Code</td>
                            <td>{{ $classCodeData->class_code }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Subject Name</td>
                            <td>{{ $classCodeData->subject_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Class Name</td>
                            <td>{{ $classCodeData->class_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Class Size</td>
                            <td>{{ $classCodeData->class_size }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Start Date</td>
                            <td>{{ date('M d, Y, h:i:s A', strtotime($classCodeData->start_date)) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">End Date</td>
                            <td>{{ date('M d, Y, h:i:s A', strtotime($classCodeData->end_date)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--  user info end -->
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for Popup -->
<script src="{{ asset('assets/js/backendSystem_ClassCodePopup.js') }}"></script>

@endsection