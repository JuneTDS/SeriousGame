@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/lectureClassesDashboard">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Manage Lecturer Class</p>
</a>

<div class="">
    <div class="header-row">
        <div class="left"><h3>Manage Students</h3></div>
        <div class="right" >
            @if ($classCode)
            <a href="{{ url('/admin/enrolStudentDashboard/' . $lectureClassId) }}">
                <button type="button" class="btn btn-outline-dark">Enrol Student</button>
            </a>
            @else
            <a href="{{ route('classCodesDashboard.all') }}">
                <button type="button" class="btn btn-outline-dark">Create Class Code</button>
            </a>
            @endif
        </div>
    </div>

    <!-- Filter (Start) -->
    <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
        <form href="{{ url('/admin/manageStudentDashboard/' . $lectureClassId) }}" id="filter-form">
            <div class="row">
                <div class="col-4" style="float: left;padding-top:41px">
                    <input type="text" class="form-control input-field" id="studentName" name="studentName" placeholder="Search by student name" value="{{ $searchKeyword }}">
                </div>

                <div class="col-4"></div>

                <div class="col-2" style="text-align: right; padding-top: 42px;">
                    <p>Filter By</p>
                </div>

                <div class="col-2">
                    <p>Updated On</p>
                    <input type="date" class="form-control input-field" id="updatedOn" name="updatedOn">
                </div>
            </div>
        </form>
    </div>
    <!-- Filter (End) -->

    <!-- start table -->
    <div class="table-container">
        <table class="table">
            <thead style="background-color: #CFDDE4;color:#45494C">
                <tr>
                    <th>S/N</th>
                    <th>Class Name</th>
                    <th>Subject Name</th>
                    <th>Full Name</th>
                    <th>Updated On</th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($manageStudentsData as $index => $manageStudentData)
                    <tr style="color:#737B7F">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $manageStudentData->class_name }}</td>
                        <td>{{ $manageStudentData->subject_name }}</td>
                        <td>{{ $manageStudentData->username }}</td>
                        <td>{{ date('M d, Y, h:i:s A', strtotime($manageStudentData->updated_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="icon-container">
                <div class="items-per-page-dropdown">
                    <div class="col">
                        <p style="font-size:14px">Item Per Page</p>
                        <select id="items-per-page">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25" selected>25</option> <!-- Set as default -->
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pagination-container" style="padding-top:110px">
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination">
                        <!-- Pagination links will be dynamically generated here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_LectureClassPopup.js') }}"></script>

<script>
    $(document).ready(function() {
        // Javascript to call function immediately when filter change (Start)
        $('.dropdown').on('change', function () {
            $('form#filter-form').submit();
        });

        $('.input-field').on('keydown', function () {
            $('form#filter-form').submit();
        });

        $('.input-field').on('keyup', function () {
            $('form#filter-form').submit();
        });
        // Javascript to call function immediately when filter change (Start)
    });
</script>

@endsection