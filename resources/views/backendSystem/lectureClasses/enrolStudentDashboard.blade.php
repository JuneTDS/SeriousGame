@extends('layouts.backendSystem_layout')

@section('content')

<a href="{{ url('/admin/manageStudentDashboard/' . $lectureClassId) }}">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Lecturer Class</p>
</a>

<div class="">
    <div class="header-row" style="display: none;">
        <div class="left"><h3>Enrol Student</h3></div>
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
                    <input type="hidden" name="lectureClassId" value="{{ $lectureClassId }}">
                    <div class="">
                        <input type="file" id="fileUpload" name="file" style="display: none;">
                        <label for="fileUpload" style="display: inline-block; padding: 10px 20px; cursor: pointer; background-color: #CFDDE4; border: none; border-radius: 5px; width: 200px; text-align: center;">
                            Upload File
                        </label>
                    </div>
                    <button type="submit" style="display: inline-block; padding: 10px 20px; cursor: pointer; background-color: #CFDDE4; border: none; border-radius: 5px; width: 250px; text-align: center;">Upload and Process CSV</button>
                </form>
            </div>
        </div>
        <div class="header-row"></div>
        <div class="row" style="padding-top:30px">
            <h3 style="font-weight:normal;padding-bottom:56px">Enrollment Results</h3>
            <table class="table" style="border: none;">
                <thead style="background-color: #CFDDE4; color: #45494C">
                    <th>S/N</th>
                    <th>Class</th>
                    <th>Student</th>
                </thead>
                <tbody>
                    @if (isset($csvData) && count($csvData) > 0)
                        @foreach ($csvData as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['class_name'] }}</td>
                                <td>{{ $data['username'] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No result found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="header-row">
        <div class="col-md-9">
            <form action="/admin/uploadEnrolStudent" method="post">
                @csrf
                <table class="table leftTable" style="border: none;">
                    <input type="hidden" id="lectureClassId" name="lectureClassId" value="{{ $lectureClassId }}">
                    
                    @if(isset($message))
                        <tr>
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        </tr>
                    @endif

                    @if(isset($error))
                        <tr>
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        </tr>
                    @endif

                    <tr>
                        <td style="font-weight:bold">Student</td>
                        <td>
                            <select class="form-select dropdown" id="student" name="student">
                                @foreach ($users as $key => $user)
                                    @if (!in_array($user->id, $aldEnrollUsers))
                                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Enrol</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_LectureClassPopup.js') }}"></script>

@endsection