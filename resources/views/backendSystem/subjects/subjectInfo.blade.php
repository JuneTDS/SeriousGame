@extends('layouts.backendSystem_layout')

@section('content')

<div class="">
    <a href="/admin/subjectsDashboard"><img src="/assets/images/expand_more.svg" class="back_icon" style="vertical-align: bottom;margin-right: 20px;margin-bottom: 56px;" /><span style="margin-top: 3px;">Back to Manage Subjects</span></a>
    <div class="header-row">
        <div class="left"><h3>{{ $data["subject"][0]->subject_name }}</h3></div>
        <div class="right" >
            <input type="hidden" class="subject-id" value="{{ $data['subject'][0]->subject_id }}">
            <input type="hidden" class="subject-name" value="{{ $data['subject'][0]->subject_name }}">
            <button type="button" id="change_status" class="btn btn-dark" style="padding-left: 60px; padding-right: 60px;" data-id="{{ $data['subject'][0]->subject_id }}">{{ ($data["subject"][0]->published) ? "Unpublish" : "Publish" }}</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Create_Success_popup -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check" ></i>
            </div>
            <p class="text-center message" style="padding-top:50px">Subject has been published.</p>
        </div>
        <button type="button" class="btn btn-cancel" id="close_reload" style="width:100%; margin-top: 10px;">Close Window</button>
    </div>

    <!-- Popup Form -->
    <!-- <div id="update-success" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p>Changes have been saved successfully.</p>
        </div>
    </div> -->
    <div class="subject-info">
        <label class="table-header">Subtopics</label>
        <table>
            <thead>
                <tr>
                    <th style="padding-left: 60px;">Topic Name</th>
                    <th>Subtopics Created</th>
                    <th>Requirement Met?</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data["topic"]) > 0)
                    @foreach($data["topic"] as $key => $value)
                        <tr>
                            <td style="padding-left: 60px;">{{ $value->topic_name }}</td>
                            <td>{{ $value->subtopic }}</td>
                            @if ($value->subtopic > 0)
                            <td>
                                <img src="/assets/images/check.svg" />
                            </td>
                            @else
                            <td>
                                <img src="/assets/images/close.svg" />
                            </td>
                            @endif
                            
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <label class="table-header">Questions</label>
        <table>
            <thead>
                <tr>
                    <th style="padding-left: 60px;width: 300px;">Subtopic Name</th>
                    <th>No. of Easy<br/>Questions Required</th>
                    <th>No. of Easy<br/>Questions Created</th>
                    <th>No. of Difficult<br/>Questions Created</th>
                    <th>No. of Difficult<br/>Questions Created</th>
                    <th style="width: 250px;">Requirement Met?</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data["topic"]) > 0 && count($data["subtopic"]) > 0)
                    @foreach($data["topic"] as $key => $topicValue)
                        <tr class="highlight">
                            <td colSpan="6" style="padding-left: 60px;">{{ $topicValue->topic_name }}</td>
                        </tr>
                        @foreach($data["subtopic"] as $key => $value)
                            @if ($value->topic_id_fk == $topicValue->topic_id )
                                <tr>
                                    <td style="padding-left: 60px;">{{ $value->subtopic_name }}</td>
                                    <td>{{ $value->no_of_easy_questions }}</td>
                                    <td>{{ $value->questions_easy }}</td>
                                    <td>{{ $value->no_of_difficult_questions }}</td>
                                    <td>{{ $value->questions_hard }}</td>
                                    @if ($value->questions_easy > 0 && $value->questions_hard > 0)
                                    <td>
                                        <img src="/assets/images/check.svg" />
                                    </td>
                                    @else
                                    <td>
                                        <img src="/assets/images/close.svg" />
                                    </td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>
<script src="{{ asset('assets/js/backend_subject.js') }}"></script>

@endsection