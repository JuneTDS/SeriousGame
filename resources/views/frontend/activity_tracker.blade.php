@extends('layouts.layout')

@section('content')

<div class="page classes-page">
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

    <div class="white-form activity-form">
        <div class="activity-title" style="display: none; padding-bottom: 50px">
            <h3>Activity Tracking for test_p01</h3>
            <p>Student Login Summary</p>
            <div class="flex-box">
                <label>Have Logged In: </label> <span class="login_count" style="margin-left: 50px;">0</span>
                <label style="margin-left: 90px;">Have Not Logged In: </label> <span class="not_login_count" style="margin-left: 50px;">0</span>
            </div>
            <button class="activity_export">Export Raw Data</button>
        </div>
        <p class="initial-message">Select a subject from the dropdown menu to begin.</p>
        <div>
            <table class="table activity_table" style="display: none">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Game Login Count</th>
                        <th>Latest Game Login</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <table class="table activity_export_table" style="display: none">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Subtopic Name</th>
                        <th>Pass Attempt Count</th>
                        <th>Last Past Attempt Date</th>
                        <th>Fail Attempt Count</th>
                        <th>Last Fail Attempt Date</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="/assets/js/tableToExcel.js"></script>
<script src="/assets/js/activity.js"></script>

@endsection