@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/subjectsDashboard" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Subjects</p>
</a>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>Manage Topics</h3></div>
        <div class="right" >
            <button type="button" id="open-popup-btn" class="btn btn-outline-dark">Create New Topic</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="popup-form" class="popup-form">
        <h3 class="mb-4">Create New Topic</h3>
        <div class="mb-4">
            <label for="topic" class="form-label">Topic Name*</label>
            <input type="text" class="form-control" id="topic" required>
        </div>
        <div class="mb-4">
            <label for="subject" class="form-label">Subject Name*</label>
            <input type="text" class="form-control" id="subject" required>
        </div>
        <label class="form-label">Expected time to complete the topic</label>
        <div class="row mb-4">
            <div class="col">
                <select class="form-select" id="hour-dropdown">
                    <option value="00">0 Hour</option>
                    <option value="01">01 Hour</option>
                    <option value="02">02 Hour</option>
                    <option value="23">23 Hour</option>
                </select>
            </div>
            <div class="col">
                <select class="form-select" id="minute-dropdown">
                    <option value="00">0 Minute</option>
                    <option value="05">05 Minute</option>
                    <option value="10">10 Minute</option>
                    <option value="55">55 Minute</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create Topic</button>
    </div>

    <!-- Create_Success_popup -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check" ></i>
            </div>
            <p class="text-center" style="padding-top:50px">A new topic has been created.</p>
        </div>
    </div>

    <div id="popup-form-update" class="popup-form">
        <h3 class="mb-4">Edit Topic</h3>
        <div class="mb-4">
            <label for="topic-update" class="form-label">Topic Name*</label>
            <input type="text" class="form-control" id="topic-update" required>
        </div>
        <div class="mb-4">
            <label for="subject-update" class="form-label">Subject Name*</label>
            <input type="text" class="form-control" id="subject-update" required>
        </div>
        <label class="form-label">Expected time to complete the topic</label>
        <div class="row mb-4">
            <div class="col">
                <select class="form-select" id="hour-dropdown">
                    <option value="00">0 Hour</option>
                    <option value="01">01 Hour</option>
                    <option value="02">02 Hour</option>
                    <option value="23">23 Hour</option>
                </select>
            </div>
            <div class="col">
                <select class="form-select" id="minute-dropdown">
                    <option value="00">0 Minute</option>
                    <option value="05">05 Minute</option>
                    <option value="10">10 Minute</option>
                    <option value="55">55 Minute</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-dark" id="update-btn" style="width: 526px">Save Changes</button>
    </div>

    <!-- Popup Form -->
    <div id="update-success" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon col-1 ">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div> 

    <!--  //row star -->
    <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
        <div class="col-4" style="float: left;padding-top:41px">
            <select class="form-select dropdown" id="dropdown2" name="dropdown2">
                <option value="option1">Logic and Mathematics (LOMA)</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
            </select>
        </div>

        <div class="col-3" style="float: left;padding-top:41px">
            <input type="text" class="form-control input-field" id="subjectname" name="username" placeholder="Search by topic name">
        </div>

        <div class="col-1" style="text-align: right; padding-top: 42px;">
            <p>Filter By</p>
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
                    <th>SubjectName</th>
                    <th>Topic Name</th>
                    <th>Updated On</th>
                    <th>Updated By</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($topics as $index => $topicData)
                <tr style="color:#737B7F" data-subject-id="1">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $topicData->subject_name }}</td>>
                    <td>{{ $topicData->topic_name }}</td>
                    <td>{{ date('M d, Y, h:i:s A', $topicData->updated_at) }}</td>
                    <td>{{ $topicData->updated_by_username }}</td>>
                    <td>
                        <div class="icon-container">
                            <i class="fa fa-pen edit-icon" onclick="showUpdateSubjectPopup(${subjectId})"></i>
                            <i class="fa fa-trash"></i>
                        </div>
                    </td>
                    <td><button class="btn btn-outline-dark custom-btn-outline-dark">Manage Subtopics</button></td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Javascript to call function immediately when filter change (Start)
    $('.input-field, .dropdown').on('change', function () {
        $('form#filter-form').submit();
    });
    // Javascript to call function immediately when filter change (Start)
</script>

@endsection