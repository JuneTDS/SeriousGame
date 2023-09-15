@extends('layouts.layout')

@section('content')

<div class="container page classes-page">
    <h3 class="page-header">Classes</h3>
    <div class="search-section">
        <div class="flex-box flex-column">
            <label>Subject</label>
            <select name="subject" id="">
                @foreach($data["classes"] as $key => $class)
                    <option value="{{ $class->class_code_no }}">{{ $class->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="button" class="primary">Search</button>
        </div>
        <div>
            <button type="button">Activity Tracker</button>
        </div>
        <div>
            <button type="button">Go to Indepth</button>
        </div>
    </div>
</div>

@endsection