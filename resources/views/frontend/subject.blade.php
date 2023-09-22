@extends('layouts.layout')

@section('content')

<div class="container page classes-page">
    <h3 class="page-header">Classes</h3>
    
    @csrf
    <div class="search-section">
        <div class="flex-box flex-column">
            <label>Subject</label>
            <select name="subject" id="subject">
                <option value="">Select a subject</option>
                @foreach($data["classes"] as $key => $class)
                    <option value="{{ $class->subject_id }}">{{ $class->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="button" class="primary" id="search">Search</button>
        </div>
    </div>

    <div class="white-form">
        <h3 class="graph-title">Summary Dashboard</h3>
        <p class="initial-message">Select a subject from the dropdown menu to begin.</p>
        <div class="graph-section">
            <div id="bar"></div>
            <div id="line"></div>
        </div>
    </div>
</div>

<script src="/assets/js/subject.js"></script>
@endsection