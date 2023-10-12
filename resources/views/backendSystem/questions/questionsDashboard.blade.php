@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/subjectsDashboard" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Subtopics</p>
</a>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>Manage Questions</h3></div>
        <div class="right" >
            <button type="button" id="open-popup-btn" class="btn btn-outline-dark">Create New Question</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="popup-form" class="popup-form">
        <h3 class="mb-4">Create New Question</h3>
        <div class="mb-4">
            <label for="subtopic" class="form-label">Subtopic Name*</label>
            <input type="hidden" id="subtopic" value="{{ $subtopics[0]->subtopic_id }}">
            <input type="text" class="form-control" value="{{ $subtopics[0]->subtopic_name }}" readonly />
        </div>
        <div class="mb-4">
            <label for="difficulty" class="form-label">Question Difficulty*</label>
            <!-- <input type="text" class="form-control" id="subtopic" placeholder="Enter subtopic name" required /> -->
            <select id="difficulty" class="form-control">
                <option value="" selected disabled>Select a difficulty</option>
                <option value="easy">Easy</option>
                <option value="difficult">Difficult</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="type" class="form-label">Question Type*</label>
            <!-- <input type="text" class="form-control" id="subtopic" placeholder="Enter subtopic name" required /> -->
            <select id="type" class="form-control">
                <option value="" selected disabled>Select a question type</option>
                <option value="mcq">MCQ</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="name" class="form-label">Question Name*</label>
            <!-- <input type="text" class="form-control" id="url" placeholder="Enter URL" /> -->
            <textarea class="form-control" name="" id="name" cols="30" rows="4" placeholder="Enter question"></textarea>
        </div>
        <div class="mb-4">
            <label for="answer" class="form-label">Question Answer*</label>
            <!-- <input type="text" class="form-control" id="url" placeholder="Enter URL" /> -->
            <textarea class="form-control" name="" id="answer" cols="30" rows="4" placeholder="Enter answer"></textarea>
        </div>
        <div class="mb-4">
            <label for="hint" class="form-label">Hints*</label>
            <!-- <input type="text" class="form-control" id="url" placeholder="Enter URL" /> -->
            <textarea class="form-control" name="" id="hint" cols="30" rows="4" placeholder="Enter hint"></textarea>
        </div>
        <div class="mb-4">
            <label for="subject" class="form-label">Score (Integer Only)*</label>
            <input type="text" class="form-control" id="score" placeholder="Enter full score" />
        </div>
        <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create Question</button>
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
        <h3 class="mb-4">Edit Question</h3>
        <div class="mb-4">
            <label for="subtopic" class="form-label">Subtopic Name*</label>
            <input type="text" class="form-control" value="{{ $subtopics[0]->subtopic_name }}" readonly />
        </div>
        <div class="mb-4">
            <label for="update_difficulty" class="form-label">Question Difficulty*</label>
            <!-- <input type="text" class="form-control" id="subtopic" placeholder="Enter subtopic name" required /> -->
            <select id="update_difficulty" class="form-control">
                <option value="" selected disabled>Select a difficulty</option>
                <option value="easy">Easy</option>
                <option value="difficult">Difficult</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="update_type" class="form-label">Question Type*</label>
            <!-- <input type="text" class="form-control" id="subtopic" placeholder="Enter subtopic name" required /> -->
            <select id="update_type" class="form-control">
                <option value="" selected disabled>Select a question type</option>
                <option value="mcq">MCQ</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="update_name" class="form-label">Question Name*</label>
            <!-- <input type="text" class="form-control" id="url" placeholder="Enter URL" /> -->
            <textarea class="form-control" name="" id="update_name" cols="30" rows="4" placeholder="Enter question"></textarea>
        </div>
        <div class="mb-4">
            <label for="update_mcq_a" class="form-label">MCQ A</label>
            <input type="text" class="form-control" id="update_mcq_a" />
        </div>
        <div class="mb-4">
            <label for="update_mcq_b" class="form-label">MCQ B</label>
            <input type="text" class="form-control" id="update_mcq_b" />
        </div>
        <div class="mb-4">
            <label for="update_mcq_c" class="form-label">MCQ C</label>
            <input type="text" class="form-control" id="update_mcq_c" />
        </div>
        <div class="mb-4">
            <label for="update_mcq_d" class="form-label">MCQ D</label>
            <input type="text" class="form-control" id="update_mcq_d" />
        </div>
        <div class="mb-4">
            <label for="update_answer" class="form-label">Question Answer*</label>
            <!-- <input type="text" class="form-control" id="url" placeholder="Enter URL" /> -->
            <textarea class="form-control" name="" id="update_answer" cols="30" rows="4" placeholder="Enter answer"></textarea>
        </div>
        <div class="mb-4">
            <label for="update_hint" class="form-label">Hints*</label>
            <!-- <input type="text" class="form-control" id="url" placeholder="Enter URL" /> -->
            <textarea class="form-control" name="" id="update_hint" cols="30" rows="4" placeholder="Enter hint"></textarea>
        </div>
        <div class="mb-4">
            <label for="update_score" class="form-label">Score (Integer Only)*</label>
            <input type="text" class="form-control" id="update_score" placeholder="Enter full score" />
        </div>
        <input type="hidden" class="update-id" value="">
        <button type="button" class="btn btn-dark" id="update-btn" style="width: 526px">Save Changes</button>
    </div>

    <!-- Popup Form -->
    <div id="update-success" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon col-1 ">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
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

    <!--  //row star -->
    <form action="/admin/questionsDashboard/{{$urlId}}" id="filter-form">
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <select class="form-select dropdown" id="dropdown2" name="subtopic">
                    <option value="">All</option>
                    @if (count($subtopics) > 0)
                        @foreach ($subtopics as $key => $value)
                            <option value="{{ $value->subtopic_id }}" selected>{{ $value->subtopic_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-3" style="float: left;padding-top:41px">
                <input type="text" class="form-control input-field" id="question_name" name="name" value="{{ $name }}" placeholder="Search by topic name">
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
                                $userId = (int) $value->id;
                            @endphp
                            <option value="{{ $value->id }}" {{ $userId === (int) $updated_by ? "selected" : ""}}>{{ $value->username }}</option>
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
                    <th>Subtopic Name</th>
                    <th class="sortable" data-column="name" style="width: 400px;">Question</th>
                    <th>Type</th>
                    <th>Difficulty</th>
                    <th>Score</th>
                    <th>Updated On</th>
                    <th>Updated By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($questions as $index => $value)
                <tr style="color:#737B7F" data-subject-id="{{ $value->question_id }}">
                    <input type="hidden" class="row-difficulty-{{ $value->question_id }}" value="{{ $value->question_difficulty }}">
                    <input type="hidden" class="row-name-{{ $value->question_id }}" value="{{ $value->question_name }}">
                    <input type="hidden" class="row-type-{{ $value->question_id }}" value="{{ $value->question_type }}">
                    <input type="hidden" class="row-mcq-a-{{ $value->question_id }}" value="{{ $value->mcq_a }}">
                    <input type="hidden" class="row-mcq-b-{{ $value->question_id }}" value="{{ $value->mcq_b }}">
                    <input type="hidden" class="row-mcq-c-{{ $value->question_id }}" value="{{ $value->mcq_c }}">
                    <input type="hidden" class="row-mcq-d-{{ $value->question_id }}" value="{{ $value->mcq_d }}">
                    <input type="hidden" class="row-answer-{{ $value->question_id }}" value="{{ $value->question_answer }}">
                    <input type="hidden" class="row-hint-{{ $value->question_id }}" value="{{ $value->hints }}">
                    <input type="hidden" class="row-score-{{ $value->question_id }}" value="{{ $value->score }}">

                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value->subtopic_name }}</td>
                    <td>{{ $value->question_name }}</td>
                    <td>{{ $value->question_type }}</td>
                    <td>{{ $value->question_difficulty }}</td>
                    <td>{{ $value->score }}</td>
                    <td>{{ date('M d, Y, h:i:s A', strtotime($value->updated_at) ) }}</td>
                    <td>{{ $value->updated_by_username }}</td>
                    <td>
                        <div class="icon-container">
                            <i class="fa fa-pen edit-icon" onclick="showUpdateSubtopicPopup({{$value->question_id}})"></i>
                            <i class="fa fa-trash" onclick="confirmDelete({{$value->question_id}})"></i>
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
<script src="{{ asset('assets/js/backendSystem_question.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection