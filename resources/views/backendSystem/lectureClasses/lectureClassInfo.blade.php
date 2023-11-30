@extends('layouts.backendSystem_layout')

@section('content')

@php
    use Carbon\Carbon;
@endphp

<a href="/admin/lectureClassesDashboard">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Lecturer Class</p>
</a>

<div class="">
    <div class="header-row">
        <div class="left"><h3>View Class Management</h3></div>
        <div class="right" >
            <button type="button" class="btn btn-outline-dark edit-popup-btn" style="width:200px;margin-right: 20px;"
                data-class-name="{{ $lectureClassData->class_name }}"
                data-academic-year="{{ $lectureClassData->academic_year }}"
                data-academic-semester="{{ $lectureClassData->academic_semester }}"
                data-lecturer-id="{{ $lectureClassData->lecturer_in_charge_id_fk }}"
                data-subject-id="{{ $lectureClassData->subject_id_fk }}"
                data-subject-class-id="{{ $lectureClassData->subject_class_id }}">
                Update
            </button>
            <button type="button" class="btn btn-outline-danger delete-popup-btn" style="width:200px" data-id="{{ $lectureClassData->subject_class_id }}">Revoke</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Edit Popup Form -->
    <div id="edit-popup-form" class="popup-form">
        <h3 class="mb-4">Edit Class Management</h3>
        <input type="text" id="subjectClass_Update" style="display: none;">
        <div class="mb-4">
            <label for="class-update" class="form-label">Class Name*</label>
            <input type="text" class="form-control" id="class_Update" required placeholder="Enter class name">
        </div>
        <div class="mb-4">
            <label for="year-update" class="form-label">Academic Year*</label>
            <select class="form-select dropdown" id="year_Update" name="year_Update">
                @foreach($yearList as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="sem-update" class="form-label">Academic Semester*</label>
            <select class="form-select dropdown" id="sem_Update" name="sem_Update">
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="lecturer-update" class="form-label">Lecturer*</label>
            <select class="form-select dropdown" id="lecturer_Update" name="lecturer_Update">
                <option value="" disabled selected>Select Lecturer Name</option>
                @foreach($lecturersData as $lecturerData)
                    <option value="{{ $lecturerData->user_id }}">{{ $lecturerData->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="subject-update" class="form-label">Subject*</label>
            <select class="form-select dropdown" id="subject_Update" name="subject_Update">
                <option value="" disabled selected>Select Subject Name</option>
                @foreach($subjectsList as $subject)
                    <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" class="btn btn-dark" id="update-btn" style="width:526px">Save Changes</button>
    </div>

    <!-- Popup Form -->
    <div id="update-success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
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
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Cancel</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/backendSystem_LectureClassPopup.js') }}"></script>

@endsection