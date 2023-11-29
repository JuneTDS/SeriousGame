@extends('layouts.backendSystem_layout')

@section('content')

<div class="">
    <div class="header-row">
        <div class="left"><h3>Class Code</h3></div>
            <div class="right" >
                <button type="button" id="create-popup-btn" class="btn btn-outline-dark">Create Class Code</button>
            </div>
        </div>
        
        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>

        <!-- Create Popup Form -->
        <div id="create-popup-form" class="popup-form">
            <h3 class="mb-4">Create New Class Code</h3>
            <div class="mb-3">
                <label for="classCode" class="form-label">Class Code*</label>
                <input type="text" class="form-control" id="createClassCode" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject*</label>
                <select class="form-select" id="createSubject" required>
                    <option value="" disabled selected>Select a subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Class*</label>
                <select class="form-select" id="createClass" required>
                    <option value="" disabled selected>Select a class</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="classSize" class="form-label">Class Size*</label>
                <input type="text" class="form-control" id="createClassSize" required>
            </div>
            <div class="mb-3">
                <label for="startDate" class="form-label">Start Date*</label>
                <input type="date" class="form-control" id="createStartDate" required>
            </div>
            <div class="mb-3">
                <label for="endDate" class="form-label">End Date*</label>
                <input type="date" class="form-control" id="createEndDate" required>
            </div>
            <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create</button>
        </div>

        <!-- Delete Popup Form -->
        <div id="delete-popup-form" class="popup-form">
            <div class="row justify-content-center align-items-center ">
                <div class="delete-warning-icon col-1 ">
                    <i class="fa fa-exclamation"></i>
                </div>
            </div>
            <div class="row justify-content-center align-items-center " style="padding-top:42px">
                <p class="text-center">Are you sure you want to delete [classCode]’s record?</p>
            </div>
            <div class="row justify-content-center align-items-center " style="padding-top:24px">
                <p class="text-center"><b>This action cannot be undone.</b></p>
            </div>
            <div class="row justify-content-center align-items-center " style="padding-top:42px">
                <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
                <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete Class Code</button>
            </div>
        </div>

        <div id="success-popup" class="popup-form">
            <div class="row justify-content-center align-items-center ">
                <div class="success-warning-icon">
                    <i class="fa fa-check" ></i>
                </div>
            </div>
            <p class="text-center" style="padding-top:50px">[classCode] has been created succesfully.</p>
        </div>

        <!--  //row start -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <form href="/admin/classCodesDashboard" id="filter-form">
                <div class="row">
                    <div class="col-4" style="float: left;padding-top:41px">
                        <input type="text" class="form-control input-field" id="classCode" name="classCode" placeholder="Search by class code and class name" value="{{ $searchKeyword }}">
                    </div>

                    <div class="col-2" style="text-align: right; padding-top: 42px;">
                        <p>Filter By</p>
                    </div>

                    <div class="col-2">
                        <p>Subject Name</p>
                        <select class="form-select dropdown" id="subjectName" name="subjectName">
                            <option value="All">All</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->subject_id }}" {{ ($selectedSubject == $subject->subject_id) ? "selected" : "" }}>{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-2">
                        <p>Start Date</p>
                        <input type="date" class="form-control input-field" id="startDate" name="startDate" value="{{ $startDate }}">
                    </div>

                    <div class="col-2">
                        <p>End Date</p>
                        <input type="date" class="form-control input-field" id="endDate" name="endDate" value="{{ $endDate }}">
                    </div>

                    <input type="hidden" id="sortBy" name="sortBy" value="">
                    <input type="hidden" id="sortColumn" name="sortColumn" value="">
                </div>
            </form>
        </div>
        <!-- //row end -->

        <!-- start table -->
        <div class="table-container">
            <table class="table leftTable">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>                        
                        <th>S/N</th>
                        <th class="sortable" data-column="class_code">Class Code</th>
                        <th class="sortable" data-column="subject_id_fk">Subject Name</th>
                        <th class="sortable" data-column="subject_class_id_fk">Class Name</th>
                        <th class="sortable" data-column="class_size">Class Size</th>
                        <th class="sortable" data-column="start_date">Start Date</th>
                        <th class="sortable" data-column="end_date">End Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody style="background-color: #Neutral/50;">
                    @foreach ($classCodes as $index => $classCodeData)
                    <tr style="color:#737B7F">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $classCodeData->class_code }}</td>
                        <td>{{ $classCodeData->subject_name }}</td>
                        <td>{{ $classCodeData->class_name }}</td>
                        <td>{{ $classCodeData->class_size }}</td>
                        <td>{{ date('M d, Y, h:i:s A', strtotime($classCodeData->start_date)) }}</td>
                        <td>{{ date('M d, Y, h:i:s A', strtotime($classCodeData->end_date)) }}</td>
                        <td>
                            <div class="icon-container">
                                <a href="{{ url('/admin/classCodeInfo/' . $classCodeData->class_code_id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ url('/admin/classCodeEdit/' . $classCodeData->class_code_id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <i class="fa fa-trash delete-popup-btn" data-id="{{ $classCodeData->class_code_id }}"></i>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- End table -->

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
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/backendSystem_ClassCodePopup.js') }}"></script>

<script>
    $(document).ready(function() {

        if (getUrlParameter("sortBy") !== false) {
            $("#sortBy").val(getUrlParameter("sortBy"));
        } else {
            $("#sortBy").val("asc");
        }

        let _token = $('meta[name="csrf-token"]').attr('content');
        console.log(_token);

        // Javascript to call function immediately when filter change (Start)
        $('.dropdown').on('change', function () {
            $('form#filter-form').submit();
        });

        $('.input-field').on('change', function () {
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
        // Javascript to call function immediately when filter change (Start)
    });
</script>

@endsection