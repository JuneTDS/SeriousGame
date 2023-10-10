@extends('layouts.backendSystem_layout')

@section('content')

@php
    use Carbon\Carbon;
@endphp

<a href="/admin/lectureClassesDashboard" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Lecturer Class</p>
</a>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>View Class Management</h3></div>
        <div class="right" >
            <button type="button" id="edit-popup-btn" class="btn btn-outline-dark" style="width:200px">Update</button>
            <button type="button" id="delete-popup-btn" class="btn btn-outline-danger" style="width:200px" data-id="{{ $lectureClassData->subject_class_id }}">Revoke</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Edit Popup Form -->
    <div id="edit-popup-form" class="popup-form">
        <h3 class="mb-4">Create Class Management</h3>
        <div class="mb-4">
            <label for="class-update" class="form-label">Class Name*</label>
            <input type="text" class="form-control" id="class-update" required placeholder="Enter class name">
        </div>
        <div class="mb-4">
            <label for="year-update" class="form-label">Academic Year*</label>
            <select class="form-select dropdown" id="year-update" name="year-update">
                <option value="option1">2023</option>
                <option value="option2">2024</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="sem-update" class="form-label">Academic Semester*</label>
            <select class="form-select dropdown" id="sem-update" name="sem-update">
                <option value="option1">1</option>
                <option value="option2">2</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="lecturer-update" class="form-label">Lecturer*</label>
            <select class="form-select dropdown" id="lecturer-update" name="lecturer-update">
                <option value="" disabled selected>Select Lecturer Name</option>
                <option value="option1">Lecturer 1</option>
                <option value="option2">Lecturer 2</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="subject-update" class="form-label">Subject*</label>
            <select class="form-select dropdown" id="subject-update" name="subject-update">
                <option value="" disabled selected>Select Subject Name</option>
                <option value="option1">Subject 1</option>
                <option value="option2">Subject 2</option>
            </select>
        </div>
        <button type="button" class="btn btn-dark" id="update-btn" style="width:526px">Save Changes</button>
    </div>

    <!-- Popup Form -->
    <div id="update-success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon col-1 ">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div>

    <!-- Popup Form -->
    <div id="delete-popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to revoke this managment?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Revoke management</button>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="row" style="padding-left:20px">
                <div class="col-md-10">
                    <table class="table leftTable" style=" border: none;">
                        <tr>
                            <td style="font-weight:bold">Subject Class ID</td>
                            <td>{{ $lectureClassData->subject_class_id }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Class Name</td>
                            <td>{{ $lectureClassData->class_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Academic Year</td>
                            <td>{{ $lectureClassData->academic_year }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Academic Semester</td>
                            <td>{{ $lectureClassData->academic_semester }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Subject</td>
                            <td>{{ $lectureClassData->subject_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Tutor Name</td>
                            <td>{{ $lectureClassData->lecturer_username }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Updated On</td>
                            <td>{{ Carbon::parse($lectureClassData->updated_at)->format('M d, Y, h:i:s A') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Updated By</td>
                            <td>{{ $lectureClassData->updated_by_username }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Created On</td>
                            <td>{{ Carbon::parse($lectureClassData->created_at)->format('M d, Y, h:i:s A') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Created By</td>
                            <td>{{ $lectureClassData->created_by_username }}</td>
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

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_LectureClassPopup .js') }}"></script>

@endsection