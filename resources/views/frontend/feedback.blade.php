@extends('layouts.layout')

@section('content')

<div class="page classes-page">
    <h3 class="page-header">Classes</h3>
    <form action="{{ route('search.class') }}" method="get">
        @csrf
        <div class="search-section">
            <div class="flex-box flex-column">
                <label>Feedback</label>
                <select name="subject" id="subject">
                    <option value="" disabled="disabled" selected="true">Select a subject</option>
                    @foreach($data["classes"] as $key => $class)
                        <option value="{{ $class->subject_id }}">{{ $class->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-box flex-column" style="margin-left: 18px;">
                <label>Class</label>
                <select name="class" id="class">
                    <option value="" disabled="disabled" selected="true">Select a class</option>
                </select>
            </div>
            <div>
                <button type="button" class="primary" id="search">Search</button>
            </div>
        </div>
    </form>

    <div class="tab-wrap">

    <!-- active tab on page load gets checked attribute -->
        <input type="radio" id="tab1" name="tabGroup1" class="tab" checked>
        <label for="tab1">Topics</label>

        <input type="radio" id="tab2" name="tabGroup1" class="tab">
        <label for="tab2">General</label>

        
        <div class="tab__content tab1__content" style="display: flex;flex-direction: column;">
            <div class="search-section inside-form">
                <div class="flex-box flex-column">
                    <label>Topic</label>
                    <select name="topic" id="topic">
                        <option value="" disabled="disabled" selected="true">Select a topic</option>
                    </select>
                </div>
            </div>
            <div style="margin: auto 0;">
                <p class="no-data topic">No feedback available.</p>
                <div class="feedback-section topic">
                    <h4>Feedback Questions</h4>
                    <div class="feedback">
                        <div class="question">
                            <label>Question 1) Do you think that learning through a game is a better option? Why / Why not?</label>
                        </div>
                        <div class="answer">
                            <label>Graphs</label>
                            <label>Graphs</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab__content tab2__content flex-display" style="display: flex">
            <!-- <div class="search-section inside-form">
                <div class="flex-box flex-column">
                    <label>Topic</label>
                    <select name="topic" id="topic-two">
                        <option value="" disabled="disabled" selected="true">Select a topic</option>
                    </select>
                </div>
            </div> -->
            <div style="margin: auto 0;">
                <p class="no-data general">No feedback available.</p>
                <div class="feedback-section general">
                    <h4>Feedback Questions</h4>
                    <div class="feedback">
                        <div class="question">
                            <label>Question 1) Do you think that learning through a game is a better option? Why / Why not?</label>
                        </div>
                        <div class="answer">
                            <label>Graphs</label>
                            <label>Graphs</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/feedback.js?<?= env('JS_VERSION') ?>"></script>
@endsection