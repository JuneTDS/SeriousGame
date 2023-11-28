@extends('layouts.layout')

@section('content')

<div class="page classes-page">
    <h3 class="page-header">Classes</h3>
    @csrf
    <div class="search-section">
        <div class="flex-box flex-column">
            <label>Subject</label>
            <select name="subject" id="subject">
                <option value="" selected disabled>Select a subject</option>
                @foreach($data["classes"] as $key => $class)
                    <option value="{{ $class->subject_id }}">{{ $class->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="primary" id="search">Search</button>
        </div>
    
        <div>
            <a class="activity_link" href="/frontend/activity?subject=1"><button type="button">Activity Tracker</button></a>
        </div>
        
        <div>
            <a class="indepth_link" href="/frontend/indepth?subject=1"><button type="button">Go to Indepth</button></a>
        </div>
    </div>

    <div class="tab-wrap">

    <!-- active tab on page load gets checked attribute -->
        <input type="radio" id="tab1" name="tabGroup1" class="tab" checked>
        <label for="tab1">Summary</label>

        <input type="radio" id="tab2" name="tabGroup1" class="tab">
        <label for="tab2">Leaderboard</label>

        <input type="radio" id="tab3" name="tabGroup1" class="tab">
        <label for="tab3">Scatter</label>
        
        <div class="tab__content graph-form">
            <h3 class="graph-title">Summary Dashboard for Classes</h3>
            <p class="initial-message">Select a subject from the dropdown menu to begin.</p>
            <div class="graph-section">
                <div id="bar"></div>
                <div id="line"></div>
            </div>
        </div>

        <div class="tab__content">
            <h3>Leadership for Classes</h3>
            <table class="table leadership_table">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Student Name</th>
                        <th>Score</th>
                        <th>Number of Topics Cleared</th>
                        <th>Time Taken (Hr : Min : Sec)</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>

        <div class="tab__content scatter-form">
            <h3 class="scatter-form" style="display: none">Scatter for Classes</h3>
            <p class="initial-message">Not enough students have completed the subject.</p>
        </div>
    </div>
</div>

<script src="/assets/js/class.js"></script>

@endsection