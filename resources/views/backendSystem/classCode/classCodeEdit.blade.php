@extends('layouts.backendSystem_layout')

@section('content')

<!-- Main Content Goes Here -->
<div class="normal-text">
    <a href="{{ url('/admin/classCodeInfo/' . $classCodeData->class_code_id) }}" class="align-self-center col-3 normal-text" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Class Code</a>
</div>

<div class="">
    <div class="header-row">
        <div class="left"><h3>{{ $classCodeData->class_code }}’s Profile</h3></div>
        <!-- <div class="right" >
            <div class="d-flex">
                <button type="button" id="update-btn" class="btn btn-dark" style="width:200px">Save</button>
                <a href="/admin/classCodeInfo/{{ $classCodeData->class_code_id }}">
                    <button type="button" class="btn btn-outline-dark"  style="width:200px">Cancel</button>
                </a>
            </div>
        </div> -->
        <div class="right" style="display: flex; justify-content: space-between;">
            <button type="button" id="update-btn" class="btn btn-dark" style="width:200px;margin-right:20px">Save</button>
            <a href="/admin/classCodeInfo/{{ $classCodeData->class_code_id }}">
                <button type="button" class="btn btn-outline-dark"  style="width:200px">Cancel</button>
            </a>
        </div>
    </div>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="row" style="padding-left:20px">
                <div class="col-md-9">
                    <form action="/admin/classCodeEditSave" method="post">
                        @csrf
                        <table class="table leftTable" style="border: none;">
                            <input type="hidden" id="editClassCodeId" value="{{ $classCodeData->class_code_id }}">
                            <tr>
                                <td style="font-weight:bold">Class Code*</td>
                                <td><input type="text"class="form-control" required placeholder="Enter class code" value="{{ $classCodeData->class_code }}" id="editClassCode"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Subject Name*</td>
                                <td>
                                    <select class="form-select" id="editSubject" required>
                                        <option value="" disabled>Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->subject_id }}" @if($subject->subject_id == $classCodeData->subject_id_fk) selected @endif>
                                                {{ $subject->subject_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Class Name*</td>
                                <td>
                                    <select class="form-select" id="editClass" required>
                                        <option value="" disabled>Select Class</option>
                                        @foreach($subjectClassData as $subjectClass)
                                            <option value="{{ $subjectClass->subject_class_id }}"
                                                    @if ($subjectClass->subject_class_id === $classCodeData->subject_class_id_fk)
                                                        selected
                                                    @endif>
                                                {{ $subjectClass->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Class Size*</td>
                                <td><input type="text" class="form-control"  placeholder="Enter class size" value="{{ $classCodeData->class_size }}" id="editClassSize"></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold">Start Date*</td>
                                <td><input type="date" class="form-control" placeholder="Select start date" value="{{ date('Y-m-d', strtotime($classCodeData->start_date)) }}" id="editStartDate"></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold">End Date*</td>
                                <td><input type="date" class="form-control" placeholder="Select start date" value="{{ date('Y-m-d', strtotime($classCodeData->end_date)) }}" id="editEndDate"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  user info end -->
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for Popup -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/backendSystem_ClassCodePopup.js') }}"></script>

@endsection