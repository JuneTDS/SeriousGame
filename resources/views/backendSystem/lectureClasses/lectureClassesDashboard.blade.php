@extends('layouts.backendSystem_layout')

@section('content')

<div class="container">
    <div class="header-row">
        <div class="left"><h3>Manage Lecture Class</h3></div>
        <div class="right" >
            <button type="button" id="create-popup-btn" class="btn btn-outline-dark">Create Class Managment</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Create Popup Form -->
    <div id="create-popup-form" class="popup-form">
        <h3 class="mb-4">Create Class Management</h3>
        <div class="mb-4">
            <label for="class" class="form-label">Class Name*</label>
            <input type="text" class="form-control" id="createClassName" required placeholder="Enter class name">
        </div>
        <div class="mb-4">
            <label for="year" class="form-label">Academic Year*</label>
            <select class="form-select" id="createAcademicYear" name="createAcademicYear">
                <option value="" disabled selected>Select Academic Year</option>
                @foreach($yearList as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="sem" class="form-label">Academic Semester*</label>
            <select class="form-select" id="createAcademicSemester" name="createAcademicSemester">
                <option value="" disabled selected>Select Academic Semester</option>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="lecturer" class="form-label">Lecturer*</label>
            <select class="form-select" id="createLecturerId" name="createLecturerId">
                <option value="" disabled selected>Select Lecturer Name</option>
                @foreach($lecturersData as $lecturerData)
                    <option value="{{ $lecturerData->user_id }}">{{ $lecturerData->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="subject" class="form-label">Subject*</label>
            <select class="form-select" id="createSubjectId" name="createSubjectId">
                <option value="" disabled selected>Select Subject Name</option>
                @foreach($subjectsList as $subject)
                    <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create Class Management</button>
    </div>

    <!-- Create_Success_popup -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check" ></i>
            </div>
            <p class="text-center" style="padding-top:50px">A new class management has been created.</p>
        </div>
    </div>

    <!-- Edit Popup Form -->
    <div id="edit-popup-form" class="popup-form">
        <h3 class="mb-4">Edit Class Management</h3>
        <input type="text" id="subjectClass_Update" style="display: none;">
        <div class="mb-4">
            <label for="class-update" class="form-label">Class Name*</label>
            <input type="text" class="form-control" id="class_Update" required placeholder="Enter class name">
        </div>
        <div class="mb-4">
            <label for="year-update" class="form-label">Academic Year*</label>
            <select class="form-select" id="year_Update" name="year_Update">
                @foreach($yearList as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="sem-update" class="form-label">Academic Semester*</label>
            <select class="form-select" id="sem_Update" name="sem_Update">
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="lecturer-update" class="form-label">Lecturer*</label>
            <select class="form-select" id="lecturer_Update" name="lecturer_Update">
                <option value="" disabled selected>Select Lecturer Name</option>
                @foreach($lecturersData as $lecturerData)
                    <option value="{{ $lecturerData->user_id }}">{{ $lecturerData->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="subject-update" class="form-label">Subject*</label>
            <select class="form-select" id="subject_Update" name="subject_Update">
                <option value="" disabled selected>Select Subject Name</option>
                @foreach($subjectsList as $subject)
                    <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" class="btn btn-dark" id="update-btn" style="width:526px">Save Changes</button>
    </div>

    <!-- Popup Form -->
    <div id="update-success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon col-1 ">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div>

    <!-- Popup Form -->
    <div id="delete-popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to revoke this managment?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Revoke management</button>
        </div>
    </div>

    <!--  //row star -->
    <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
        <form href="/admin/lectureClassesDashboard" id="filter-form">
            <div class="row">
                <div class="col-4" style="float: left;padding-top:41px">
                    <input type="text" class="form-control input-field" id="classname" name="classname" placeholder="Search by class name, subject name or lecturer name" value="{{ $searchKeyword }}">
                </div>

                <div class="col-2"></div>

                <div class="col-2" style="text-align: right; padding-top: 42px;">
                    <p>Filter By</p>
                </div>

                <div class="col-2">
                    <p>Academic Year</p>
                    <select class="form-select dropdown" id="academicYear" name="academicYear">
                        <option value="All">All</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year }}" {{ $year === $selectedAcademicYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <p>Academic Semester</p>
                    <select class="form-select dropdown" id="academicSemester" name="academicSemester">
                        <option value="All">All</option>
                        @foreach ($academicSemesters as $semester)
                            <option value="{{ $semester }}" {{ $semester === $selectedAcademicSemester ? 'selected' : '' }}>{{ $semester }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" id="sortBy" name="sortBy" value="">
                <input type="hidden" id="sortColumn" name="sortColumn" value="">
            </div>
        </form>
    </div>
    <!-- //row end -->

    <!-- start table -->
    <div class="table-container">
        <table class="table tableLeft">
            <thead style="background-color: #CFDDE4;color:#45494C">
                <tr>
                    <th>S/N</th>
                    <th class="sortable" data-column="class_name">Class Name</th>
                    <th class="sortable" data-column="academic_year" style="text-align:center">Academic Year</th>
                    <th class="sortable" data-column="academic_semester" style="text-align:center">Academic Semester</th>
                    <th>Subject Name</th>
                    <th>Lecture Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color: #Neutral/50;">
                @foreach ($lectureClasses as $index => $lectureClass)
                <tr style="color:#737B7F" data-subject-id="1">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $lectureClass->class_name }}</td>
                    <td style="text-align:center">{{ $lectureClass->academic_year }}</td>
                    <td style="text-align:center">{{ $lectureClass->academic_semester }}</td>    
                    <td>{{ $lectureClass->subject_name }}</td>
                    <td>{{ $lectureClass->username }}</td>
                    <td>
                        <div class="icon-container">
                            <a href="{{ url('/admin/lectureClassInfo/' . $lectureClass->subject_class_id) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                            <!-- <i class="fa fa-edit edit-popup-btn"></i> -->
                            <i class="fa fa-edit edit-popup-btn"
                                data-class-name="{{ $lectureClass->class_name }}"
                                data-academic-year="{{ $lectureClass->academic_year }}"
                                data-academic-semester="{{ $lectureClass->academic_semester }}"
                                data-lecturer-id="{{ $lectureClass->lecturer_in_charge_id_fk }}"
                                data-subject-id="{{ $lectureClass->subject_id_fk }}"
                                data-subject-class-id="{{ $lectureClass->subject_class_id }}">
                            </i>
                            <i class="fa fa-trash delete-popup-btn" data-id="{{ $lectureClass->subject_class_id }}"></i>
                        </div>
                    </td>
                    <td>
                        <a href="{{ url('/admin/manageStudentDashboard/' . $lectureClass->subject_class_id) }}">    
                            <button class="btn btn-outline-dark custom-btn-outline-dark">Manage Students</button>
                        </a>
                    </td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/backendSystem_LectureClassPopup.js') }}"></script>

<script>
    $(document).ready(function() {

        if (getUrlParameter("sortBy") !== false) {
            $("#sortBy").val(getUrlParameter("sortBy"));
        } else {
            $("#sortBy").val("asc");
        }

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

        $(document).on("click", ".sortable", function(e) {
            e.preventDefault();
            var attrSort = $(this).attr("data-column");
            
            if ($("#sortBy").val() === "asc") {
                $("#sortBy").val("desc");
            } else {
                $("#sortBy").val("asc");
            }

            // Set sortColumn to the clicked column
            $("#sortColumn").val(attrSort);

            $('form#filter-form').submit();
        });
        // Javascript to call function immediately when filter change (End)
    });
</script>

@endsection