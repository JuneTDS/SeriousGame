@extends('layouts.backendSystem_layout')

@section('content')

<div class="container custom-container">

    <div class="header-row">
        <div class="left"><h3>Manage Subjects</h3></div>
        <div class="right" >
            <button type="button" id="open-popup-btn" class="btn btn-outline-dark">Create New Subject</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="popup-form" class="popup-form">
        <h3 class="mb-4">Create New Subject</h3>
        <div class="mb-4">
            <label for="subject" class="form-label">Subject Name*</label>
            <input type="text" class="form-control" id="subject" required>
        </div>
        <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create Subject</button>
        <button type="button" class="btn btn-cancel" id="close" style="width:526px; margin-top: 10px;">Close</button>
    </div>

    <!-- Create_Success_popup -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon">
                <img src="/assets/images/check_circle.svg" />
            </div>
            <p class="text-center message" style="padding-top:50px">A new subject has been created.</p>
        </div>
        <button type="button" class="btn btn-cancel" id="close_reload" style="width:100%; margin-top: 10px;">Close Window</button>
    </div>

    <div id="popup-form-update" class="popup-form">
        <h3 class="mb-4">Edit Subject</h3>
        <div class="mb-4">
            <label for="subject-update" class="form-label">Subject Name*</label>
            <input type="text" class="form-control update-name" id="subject-update" required>
            <input type="hidden" class="form-control update-id">
        </div>
        <button type="button" class="btn btn-dark" id="update-btn" style="width: 526px">Save Changes</button>
    </div>

    <!-- delete_popup -->
    <div id="delete-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon text-center">
                <img src="/assets/images/error.svg" />
            </div>
            <p class="text-center message" style="padding-top:50px">Are you sure want to delete.</p>
        </div>
        <input type="hidden" class="form-control delete-id">
        <button type="button" class="btn btn-dark" id="delete-btn" style="width: 526px">Delete</button>
        <button type="button" class="btn btn-cancel" id="close" style="width:100%; margin-top: 10px;">Cancel</button>
    </div>

    <!-- Popup Form -->
    <!-- <div id="update-success" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon col-1 ">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div> -->

    <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
        <div class="col-4" style="float: left;padding-top:41px">
            <input type="text" class="form-control input-field" id="subjectname" name="username" placeholder="Search by subject name">
        </div>
        <div class="col-2" style="text-align: right; padding-top: 42px;">
            <p>Filter By</p>
        </div>
        <div class="col-2">
            <p>Published</p>
            <select class="form-select dropdown" id="dropdown1" name="dropdown1">
                <option value="option1">All</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
            </select>
        </div>
        <div class="col-2">
            <p>Updated By</p>
            <select class="form-select dropdown" id="dropdown2" name="dropdown2">
                <option value="option1">All</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
            </select>
        </div>
        <div class="col-2">
            <p>Updated On</p>
            <input type="date" class="form-control input-field" id="datePicker" name="datePicker">
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead style="background-color: #CFDDE4;color:#45494C">
                <tr>
                    <th>S/N</th>
                    <th>Subject Name</th>
                    <th>Published</th>
                    <th>Updated On</th>
                    <th>Updated By</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($subjects as $index => $subjectData)
                <tr style="color:#737B7F" data-subject-id="1">
                    <input type="hidden" name="" id="subject-id-{{$subjectData->subject_id}}" value="{{$subjectData->subject_name}}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subjectData->subject_name }}</td>
                    <td style="font-weight: bold">
                        <a href="#" class="status-toggle" data-id="{{ $subjectData->subject_id }}" data-status="{{ $subjectData->published }}" style="text-decoration: none;">
                            @php
                                $publishText = '';
                                $publishClass = ''; // Provide a default value
                                switch ($subjectData->published) {
                                    case '0':
                                        $publishText = 'Not Published';
                                        $publishClass = 'notPublished';
                                        break;
                                    case '1':
                                        $publishText = 'Published';
                                        $publishClass = 'Published';
                                        break;
                                    default:
                                        $publishText = 'Unknown';
                                        break;
                                }
                            @endphp
                            <span class="{{ $publishClass }}">{{ $publishText }}</span>
                        </a>
                    </td>
                    <td>{{ date('M d, Y, h:i:s A', strtotime($subjectData->updated_at)) }}</td>
                    <td>{{ $subjectData->updated_by_username }}</td>
                    <td>
                        <div class="icon-container">
                            <a href="{{ url('/admin/subjectInfo/' . $subjectData->subject_id) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                            <i class="fa fa-pen edit-icon" onclick="showUpdateSubjectPopup({{$subjectData->subject_id}})" style="cursor:pointer;"></i>
                            <i class="fa fa-trash" onclick="confirmDelete({{$subjectData->subject_id}})" style="cursor:pointer;"></i>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('/admin/topicsDashboard/' . $subjectData->subject_id) }}">
                            <button class="btn btn-outline-dark custom-btn-outline-dark">Manage Topics</button>
                        </a>
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
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>
<script src="{{ asset('assets/js/backend_subject.js') }}"></script>

@endsection