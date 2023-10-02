@extends('layouts.layout')

@section('content')

<div class="container page classes-page">
    <a href="/frontend/classes"><img src="/assets/images/expand_more.svg" class="back_icon" style="vertical-align: bottom;margin-right: 20px;margin-bottom: 56px;" /><span style="margin-top: 3px;">Back to Classes</span></a>
    @csrf
    <div class="search-section">
        <input type="hidden" id="subject" value="{{ $data["subject"] }}">
        <div class="flex-box flex-column">
            <label>Class</label>
            <select name="class" id="class">
                @foreach($data["classes"] as $key => $class)
                    <option value="{{ $class->subject_class_id }}">{{ $class->class_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="primary" id="search">Search</button>
        </div>
    
        <!-- <div>
            <button type="button">Activity Tracker</button>
        </div>
        <div>
            <button type="button">Go to Indepth</button>
        </div> -->
    </div>

    <div class="white-form indepth-form">
        <div class="indepth-title" style="display: none; padding-bottom: 50px">
            <h3>Indepth Dashboard for test_p01</h3>
            <div>
                <div class="flex-box">
                    <div class="form-control" style="width: 20%">
                        <div>
                            <label>Complete</label>
                        </div>
                        <select name="complete" id="complete">
                            <option value="" disabled selected>Select an option</option>
                            <option value="0">false</option>
                            <option value="1">true</option>
                        </select>
                    </div>
                    <div class="form-control" style="width: 20%;margin-left: 30px;">
                        <div>
                            <label>Student Name</label>
                        </div>
                        <input name="student" id="student" placeholder="Enter student's name" />
                    </div>
                </div>
                <div class="flex-box" style="margin-top: 60px;">
                    <div class="range" style="width: 20%;">
                        <div>
                            <label>Topics Cleared</label>
                        </div>
                        <input type="range" min="0" max="50" value="0" id="topic_range" class="range2 range-input" />
                        <div class="topic_range">
                            <label class="range_value topic_value">0</label>
                            <label class="topic_max"></label>
                        </div>

                    </div>
                    <div class="range" style="width: 20%;margin-left: 30px;">
                        <div>
                            <label>Score</label>
                        </div>
                        <input type="range" min="0" max="50" value="0" id="score_range" class="range2 range-input" />
                        <div class="score_range">
                            <label class="range_value score_value">0</label>
                            <label class="score_max"></label>
                        </div>

                    </div>
                    <div class="range" style="width: 20%;margin-left: 30px;">
                        <div>
                            <label>logs</label>
                        </div>
                        <input type="range" min="0" max="50" value="0" id="log_range" class="range2 range-input" />
                        <div class="log_range">
                            <label class="range_value log_value">0</label>
                            <label class="log_max"></label>
                        </div>

                    </div>
                </div>
            </div>
            <button class="indepth_export">Export Raw Data</button>
        </div>
        <p class="initial-message">Select a class from the dropdown menu to begin.</p>
        <div class="indepth-data-section" style="width: 100%;display:none;">
            <div class="flex-box" style="width: 100%;justify-content: space-between">
                <div style="width: 33%;padding-top:59px;">
                    <h4 style="text-align:center;margin-bottom:45px">Number of Login Per Student</h4>
                    <table class="table indepth_table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Log</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div class="graph-section" style="width: 65%;">
                    <div id="bar"></div>
                    <div id="pie"></div>
                </div>
            </div>
            <div style="width: 100%;margin-top: 100px;">
                <h4 style="text-align:center;">Student Statistics</h4>
                <table class="table statstic_table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Gameplay Time (Hr : Min : Sec)</th>
                            <th>Topics Cleared</th>
                            <th>Total Topics</th>
                            <th>Score</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>

                <table class="table export_table" style="display: none;">
                    <thead>
                        <tr>	
                            <th>userId</th>
                            <th>admin_no</th>
                            <th>first_name</th>
                            <th>subject_class_id</th>
                            <th>class_name</th>
                            <th>score</th>
                            <th>time_taken</th>
                            <th>topicsCleared</th>
                            <th>totalTopics</th>
                            <th>num_of_attempts</th>
                            <th>log</th>
                            <th>completionStatus</th>
                            <th>last_attempt_time</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/range_slider.js"></script>
<script src="/assets/js/tableToExcel.js"></script>
<script src="/assets/js/indepth.js"></script>

@endsection