@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/subjectsDashboard">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Subjects</p>
</a>

<div class="">
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
            <input type="hidden" id="subject" value="{{ $subjects[0]->subject_id }}">
            <input type="text" class="form-control" value="{{ $subjects[0]->subject_name }}" required readonly>
        </div>
        <label class="form-label">Expected time to complete the topic</label>
        <div class="row mb-4">
            <div class="col">
                <select class="form-select" id="hour-dropdown">
                    @foreach($hours as $hour)
                        <option value="{{ $hour }}">{{ $hour }} Hour</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <select class="form-select" id="minute-dropdown">
                    @foreach($minutes as $minute)
                        <option value="{{ $minute }}">{{ $minute }} Minute</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create Topic</button>
    </div>

    <!-- Create_Success_popup -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon">
                <img src="/assets/images/check_circle.svg" />
            </div>
            <p class="text-center message" style="padding-top:50px">A new topic has been created.</p>
        </div>
        <button type="button" class="btn btn-cancel" id="close_reload" style="width:100%; margin-top: 10px;">Close Window</button>
    </div>

    <div id="popup-form-update" class="popup-form">
        <h3 class="mb-4">Edit Topic</h3>
        <div class="mb-4">
            <label for="topic-update" class="form-label">Topic Name*</label>
            <input type="hidden" class="update-id" value="">
            <input type="text" class="form-control update-name" id="topic-update" required>
        </div>
        <div class="mb-4">
            <label for="subject-update" class="form-label">Subject Name*</label>
            <input type="text" class="form-control" id="subject-update" value="{{ $subjects[0]->subject_name }}" required readonly>
        </div>
        <label class="form-label">Expected time to complete the topic</label>
        <div class="row mb-4">
            <div class="col">
                <select class="form-select hour-dropdown-update" id="hour-dropdown">
                    @foreach($hours as $hour)
                        <option value="{{ $hour }}">{{ $hour }} Hour</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <select class="form-select minute-dropdown-update" id="minute-dropdown">
                    @foreach($minutes as $minute)
                        <option value="{{ $minute }}">{{ $minute }} Minute</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-dark" id="update-btn" style="width: 526px">Save Changes</button>
    </div>

    <!-- Popup Form -->
    <div id="update-success" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div>

    <!-- delete_popup -->
    <div id="delete-popup" class="popup-form">
        <!-- <div class="row justify-content-center align-items-center ">
            <div class="warning-icon text-center">
                <img src="/assets/images/error.svg" />
            </div>
            <p class="text-center message" style="padding-top:50px">Are you sure want to delete.</p>
        </div>
        <input type="hidden" class="form-control delete-id">
        <button type="button" class="btn btn-dark" id="delete-btn" style="width: 526px">Delete</button>
        <button type="button" class="btn btn-cancel" id="close" style="width:100%; margin-top: 10px;">Cancel</button> -->

        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to delete?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <input type="hidden" class="form-control delete-id">
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="close" style="width:200px;margin-right:20px">Cancel</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete</button>
        </div>
    </div>

    <!--  //row star -->
    <form action="/admin/topicsDashboard/{{$urlId}}" id="filter-form">
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <select class="form-select dropdown" id="dropdown2" name="subject">
                    <option value="">All</option>
                    @if (count($subjects) > 0)
                        @foreach ($subjects as $key => $value)
                            @php
                                $subjectId = (int) $value->subject_id;
                            @endphp
                            <option value="{{ $value->subject_id }}" selected>{{ $value->subject_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-3" style="float: left;padding-top:41px">
                <input type="text" class="form-control input-field" id="topic_name" name="name" value="{{ $name }}" placeholder="Search by topic name">
            </div>

            <div class="col-1" style="text-align: right; padding-top: 42px;">
                <p>Filter By</p>
            </div>

            <div class="col-2">
                <p>Updated By</p>
                <select class="form-select dropdown" id="dropdown2" name="updated_by">
                    <option value="">All</option>
                    @if (count($users) > 0)
                        @foreach ($users as $key => $value)
                            @php
                                $userId = (int) $value->user_id;
                            @endphp
                            <option value="{{ $value->user_id }}" {{ $userId === (int) $updated_by ? "selected" : ""}}>{{ $value->username }}</option>
                        @endforeach
                    @endif
                    <!-- <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option> -->
                </select>
            </div>

            <div class="col-2">
                <p>Updated On</p>
                <input type="date" class="form-control input-field" id="datePicker" name="updated_at" value="{{ $updated_at }}">
            </div>

            <input type="hidden" name="name_sort" id="name_sort" value="{{ $name_sort }}">
        </div>
    </form>

    <div class="table-container">
        <table class="table">
            <thead style="background-color: #CFDDE4;color:#45494C">
                <tr>
                    <th>S/N</th>
                    <th>SubjectName</th>
                    <th class="sortable" data-column="name">Topic Name</th>
                    <th>Updated On</th>
                    <th>Updated By</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($topics as $index => $topicData)
                @php
                    $time_expected = $topicData->time_expected;
                @endphp
                <tr style="color:#737B7F" data-subject-id="{{ $topicData->topic_id }}">
                    <input type="hidden" class="row-name-{{ $topicData->topic_id }}" value="{{ $topicData->topic_name }}">
                    <input type="hidden" class="row-time-{{ $topicData->topic_id }}" value="{{ $topicData->time_expected }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $topicData->subject_name }}</td>
                    <td>{{ $topicData->topic_name }}</td>
                    <td>{{ date('M d, Y, h:i:s A', strtotime($topicData->updated_at) ) }}</td>
                    <td>{{ $topicData->updated_by_username }}</td>
                    <td>
                        <div class="icon-container">
                            <i class="fa fa-pen edit-icon" onclick="showUpdateTopicPopup({{$topicData->topic_id}})"></i>
                            <i class="fa fa-trash" onclick="confirmDelete({{$topicData->topic_id}})"></i>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('/admin/subtopicsDashboard/' . $topicData->topic_id) }}">
                            <button class="btn btn-outline-dark custom-btn-outline-dark">Manage Subtopics</button>
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
<script src="{{ asset('assets/js/backendSystem_topic.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection