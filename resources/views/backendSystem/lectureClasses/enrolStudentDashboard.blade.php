@extends('layouts.backendSystem_layout')

@section('content')

<a href="{{ url('/admin/manageStudentDashboard/' . $lectureClassId) }}" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Lecturer Class</p>
</a>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>Enrol Student</h3></div></div>
        <div class="row" style="margin-bottom:52px;margin-top:52px">
            <div class="col-4"> 
                <p>Step 1: Download the template</p>
            </div>
            <div class="col-5">
                <a href="/admin/enrolStudentTemplate">
                    <button class="btn" style="background-color:#CFDDE4;width:200px;padding: 10px 20px;">Download template</button>       
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom:52px;">
            <div class="col-4">        
                <p>Step 2: Upload the file</p>
            </div>        
            <div class="col-5">                       
                <form action="{{ url('/admin/uploadEnrolStudentFile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-5">
                        <input type="file" id="fileUpload" name="file" style="display: none;">
                        <label for="fileUpload" style="display: inline-block; padding: 10px 20px; cursor: pointer; background-color: #CFDDE4; border: none; border-radius: 5px; width: 200px; text-align: center;">
                            Upload File
                        </label>
                    </div>
                    <button type="submit" style="display: inline-block; padding: 10px 20px; cursor: pointer; background-color: #CFDDE4; border: none; border-radius: 5px; width: 200px; text-align: center;">Upload and Process CSV</button>
                </form>
            </div>
        </div>
        <div class="header-row"></div>
        <div class="row" style="padding-top:30px">
            <h3 style="font-weight:normal;padding-bottom:56px">Enrollment Results</h3>
            <table class="table" style=" border: none;">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <th>S/N</th>
                    <th>Class</th>
                    <th>Student</th>
                </thead>
                <tbody>
                    @if (count($enrolStudentsResult) === 0)
                        <tr>
                            <td colspan="3">No result found</td>
                        </tr>
                    @else
                        @foreach ($enrolStudentsResult as $enrolledStudent)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $enrolledStudent['class_name'] }}</td>
                                <td>{{ $enrolledStudent['student_full_name'] }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>        
        </div>
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_LectureClassPopup.js') }}"></script>

@endsection