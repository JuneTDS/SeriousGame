@extends('layouts.backendSystem_layout')

@section('content')

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>Manage Subject Enrolments</h3></div>
        <div class="right" >
            <button type="button" id="create-popup-btn" class="btn btn-outline-dark">Create New Subject Enrolment</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Create Popup Form -->
    <div id="create-popup-form" class="popup-form">
        <h3 class="mb-4">Create Subject Enrolment</h3>
        <div class="mb-4">
            <label for="subject" class="form-label">Subject Name*</label>
            <select class="form-select dropdown" id="subjectName" name="subjectName">
                <option value="" disabled selected>Select Subject Name</option>
                <option value="option1">Subject Name 1</option>
                <option value="option2">Subject Name 2</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="user" class="form-label">User Name*</label>
            <select class="form-select dropdown" id="userName" name="userName">
                <option value="" disabled selected>Select User Name</option>
                <option value="option1">User 1</option>
                <option value="option2">User 2</option>
            </select>
        </div>
        <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Enrol</button>
    </div>

    <!-- Create_Success_popup -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon">
                <i class="fa fa-check" ></i>
            </div>
            <p class="text-center" style="padding-top:50px">A new subject enrolment has been created.</p>
        </div>
    </div>

    <!-- Delete Popup Form -->
    <div id="delete-popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to delete this enrolment?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete Enrolment</button>
        </div>
    </div>

    <!-- Show number of item in this module (Start) -->
    <!-- <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
        <div class="col-4" style="float: left;padding-top:41px">
            <input type="text" class="form-control input-field" id="subjectname" name="username" placeholder="Search by subject or tutor name">
        </div>
        <div class="row"><p style="padding-top:10px">Showing 1 - 6 of 6 items</p></div>
    </div> -->
    <!-- Show number of item in this module (End) -->

    <!-- start table -->
    <div class="table-container">
        <table class="table tableLeft">
            <thead style="background-color: #CFDDE4;color:#45494C">
                <tr>
                    <th>S/N</th>
                    <th>Tutor Name</th>
                    <th>Subject Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($subjectEnrollments as $index => $subjectEnrollmentData)
                <tr style="color:#737B7F" data-subject-id="1">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subjectEnrollmentData->username }}</td>
                    <td>{{ $subjectEnrollmentData->subject_name }}</td>
                    <td>
                        <div class="icon-container">
                            <i class="fa fa-trash" id="open-popup-btn-remove"></i>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="icon-container">
                <div class="items-per-page-dropdown">
                    <div class="col">
                        <p style="font-size:14px">Item Per Page</p>
                        <select id="items-per-page">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25" selected>25</option> <!-- Set as default -->
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pagination-container" style="padding-top:110px">
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination">
                        <!-- Pagination links will be dynamically generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_SubjectEnrollmentPopup.js') }}"></script>

@endsection