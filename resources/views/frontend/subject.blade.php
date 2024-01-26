@extends('layouts.layout')

@section('content')

<div class="page classes-page">
    <h3 class="page-header">Classes</h3>
    
    @csrf
    <div class="search-section">
        <div class="flex-box flex-column">
            <label>Subject</label>
            <select name="subject" id="subject">
                <option value="">Select a subject</option>
                @foreach($data["subjects"] as $key => $class)
                    <option value="{{ $class->subject_id }}">{{ $class->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-box flex-column class-column" style="display: none;padding-left: 10px">
            <label>Class</label>
            <select name="class" id="class">
                <option value="">Select a class</option>
            </select>
        </div>
        <div>
            <button type="button" class="primary" id="search">Search</button>
        </div>
        <div class="indepth-btn" style="display: none;">
            <button type="button" class="primary" id="indepth">Go to Indepth</button>
        </div>
        <div class="student-btn" style="display: none;">
            <button type="button" class="primary" id="student">Student Details</button>
        </div>
    </div>

    <div class="white-form subject-form">
        <h3 class="graph-title">Summary Dashboard</h3>
        <p class="initial-message">Select a subject from the dropdown menu to begin.</p>
        <div class="graph-section">
            <div id="bar"></div>
            <div id="line"></div>
        </div>
    </div>

    <div class="white-form indepth-form class-form" style="display: none;">
        <div class="indepth-title" style="padding-bottom: 50px">
            <h3>Indepth Dashboard for test_p01</h3>
            <div>
                <div class="flex-box">
                    <div style="width: 20%">
                        <div>
                            <label>Topic Name</label>
                        </div>
                        <select class="form-control" name="topic" id="topic">
                            <option value="" disabled selected>Select an option</option>
                        </select>
                    </div>
                    <div style="width: 20%;margin-left: 30px;">
                        <div>
                            <label>Student Name</label>
                        </div>
                        <input class="form-control" name="student" id="student" placeholder="Enter student's name" />
                    </div>
                    <div>
                        <button class="secondary export float-right">Export Raw Data</button>
                    </div>
                    <!-- <div class="range" style="width: 20%;margin-left: 30px;">
                        <div>
                            <label>Score</label>
                        </div>
                        <input type="range" min="0" max="50" value="0" id="score_range" class="range2 range-input" />
                        <div class="score_range">
                            <label class="range_value score_value">0</label>
                            <label class="score_max"></label>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="indepth-data-section" style="width: 100%;">
            <!-- <div class="flex-box" style="width: 100%;justify-content: space-between">
                <div class="graph-section" style="width: 65%;">
                    <div id="avg_bar"></div>
                    <div id="score_bar"></div>
                    <div id="class_bar"></div>
                </div>
            </div> -->
            <div class="flex-box" style="width: 100%;margin-top: 20px;justify-content: space-between;">
                <div style="width: 47%;">
                    <h4>Topic</h4>
                    <table class="table topics_table">
                        <thead>
                            <tr>
                                <th style="width: 200px">Topic Name</th>
                                <th>Score / Topic</th>
                                <th>Time Taken / Topic</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

                <div style="width: 49%;">
                    <h4>Subtopic</h4>
                    <table class="table subtopics_table">
                        <thead>
                            <tr>
                                <th style="width: 200px">Subtopic Name</th>
                                <th>Score / SubTopic</th>
                                <th>Time Taken / SubTopic</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="white-form statstic-form class-form" style="display: none;">
        <div class="statstic-data-section" style="width: 100%;">
            <div style="width: 100%;margin-top: 20px;">
                <div>
                    <h4>Student Details</h4>
                    <table class="table statstic_table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Time Taken / Topic</th>
                                <th>Attempts / Topic</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/tableToExcel.js"></script>
<script src="/assets/js/subject.js?<?= env('JS_VERSION') ?>"></script>
@endsection