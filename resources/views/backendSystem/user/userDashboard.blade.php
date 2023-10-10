@extends('layouts.backendSystem_layout')

@section('content')

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>Users</h3></div>
            <div class="right" >
                <button type="button" id="create-popup-btn" class="btn btn-outline-dark">Create New User</button>
            </div>
        </div>
        
        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>

        <!-- Create Popup Form -->
        <form action="/admin/createUser" method="post">
            @csrf
            <div id="create-popup-form" class="popup-form">
                <h3 class="mb-4">Create New User</h3>
                <div class="mb-3">
                    <label for="username" class="form-label">Username*</label>
                    <input type="text" class="form-control" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address*</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password*</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" required>
                        <option value="0">Blocked</option>
                        <option value="1">Active</option>
                        <option value="2" selected>Wait</option> <!-- Set "Wait" as the default selection -->
                        <option value="3">Deleted</option>
                    </select>
                </div>
                <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create</button>
            </div>
        </form>

        <!-- Delete Popup Form -->
        <div id="delete-popup-form" class="popup-form">
            <div class="row justify-content-center align-items-center ">
                <div class="delete-warning-icon col-1 ">
                    <i class="fa fa-exclamation"></i>
                </div>
            </div>
            <div class="row justify-content-center align-items-center " style="padding-top:42px">
                <p class="text-center">Are you sure you want to delete [username]’s record?</p>
            </div>
            <div class="row justify-content-center align-items-center " style="padding-top:24px">
                <p class="text-center"><b>This action cannot be undone.</b></p>
            </div>
            <div class="row justify-content-center align-items-center " style="padding-top:42px">
                <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
                <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete User</button>
            </div>
        </div>

        <div id="success-popup" class="popup-form">
            <div class="row justify-content-center align-items-center ">
                <div class="create-warning-icon">
                    <i class="fa fa-check" ></i>
                </div>
            </div>
            <p class="text-center" style="padding-top:50px">[username] has been created succesfully.</p>
        </div>

        <!--  //row start -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <form href="/admin/usersDashboard" id="filter-form">
                <div class="row">
                    <div class="col-4" style="float: left;padding-top:41px">
                        <input type="text" class="form-control input-field" id="username" name="username" placeholder="Search user by username or email address"  value="{{ $searchKeyword }}">
                    </div>

                    <div class="col-2" style="text-align: right; padding-top: 42px;">
                        <p>Filter By</p>
                    </div>

                    <div class="col-2">
                        <p>Status</p>
                        <select class="form-select dropdown" id="statusDropdown" name="statusDropdown">
                            <option value="All" {{ $selectedStatus === 'All' ? 'selected' : '' }}>All</option>
                            <option value="0" {{ $selectedStatus === '0' ? 'selected' : '' }}>Blocked</option>
                            <option value="1" {{ $selectedStatus === '1' ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $selectedStatus === '2' ? 'selected' : '' }}>Wait</option>
                            <option value="3" {{ $selectedStatus === '3' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <p>User Role Name</p>
                        <select class="form-select dropdown" id="roleName" name="roleName">
                            <option value="All">All</option>
                            @foreach ($roleDescriptions as $roleDescription)
                                <option value="{{ $roleDescription }}" {{ $roleDescription === $selectedRoleName ? 'selected' : '' }}>{{ $roleDescription }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-2">
                        <p>Last Visit</p>
                        <input type="date" class="form-control input-field" id="lastVisit" name="lastVisit">
                    </div>

                    <input type="hidden" id="sortBy" name="sortBy" value="">
                    <input type="hidden" id="sortColumn" name="sortColumn" value="">
                </div>
            </form>
        </div>
        <!-- //row end -->

        <!-- start table -->
        <div class="table-container">
            <table class="table middleTable">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>                        
                        <th>S/N</th>
                        <th class="sortable" data-column="username">Username</th>
                        <th class="sortable" data-column="email">Email Address</th>
                        <th class="sortable" data-column="status">Status</th>
                        <th class="sortable" data-column="description">User Role Name</th>
                        <th class="sortable" data-column="last_visit">Last Visit</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody style="background-color: #Neutral/50;">
                    @foreach ($user as $index => $userData)
                    <tr style="color: #737B7F">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <i class="fa fa-user" style="margin-right:5px;background-color:#737B7F;padding:3px;border-radius:50%;font-size:14px;color:white"></i>
                            {{ $userData->username }}
                        </td>
                        <td>{{ $userData->email }}</td>
                        <td style="font-weight: bold">
                            <a href="#" class="status-toggle" data-user-id="{{ $userData->id }}" data-status="{{ $userData->status }}" style="text-decoration: none;">
                                @php
                                    $statusText = '';
                                    $statusClass = ''; // Provide a default value
                                    switch ($userData->status) {
                                        case '0':
                                            $statusText = 'Blocked';
                                            $statusClass = 'statusBlocked';
                                            break;
                                        case '1':
                                            $statusText = 'Active';
                                            $statusClass = 'statusActive';
                                            break;
                                        case '2':
                                            $statusText = 'Wait';
                                            $statusClass = 'statusWait';
                                            break;
                                        case '3':
                                            $statusText = 'Deleted';
                                            $statusClass = 'statusDeleted';
                                            break;
                                        default:
                                            $statusText = 'Unknown';
                                            break;
                                    }
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </a>
                        </td>
                        <td>{{ $userData->description  }}</td>
                        <td>{{ date('M d, Y, h:i:s A', $userData->last_visit) }}</td>
                        <td>
                            <div class="icon-container">
                                <a href="{{ url('/admin/userInfo/' . $userData->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ url('/admin/userEdit/' . $userData->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <!-- <i class="fa fa-trash" id="delete-popup-btn"></i> -->
                                <i class="fa fa-trash delete-user-btn" data-user-id="{{ $userData->id }}"></i>

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
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>

<script>
    $(document).ready(function() {

        if (getUrlParameter("sortBy") !== false) {
            $("#sortBy").val(getUrlParameter("sortBy"));
        } else {
            $("#sortBy").val("asc");
        }

        let _token = $('meta[name="csrf-token"]').attr('content');
        console.log(_token);

        // Javascript to handle status change (Start)
        $('.status-toggle').on('click', function(e) {
            e.preventDefault();
            var userId = $(this).data('user-id');   //Get the value of 'user-id' under 'status-toggle' class
            
            // Send a POST request to update the user's status without expecting a response
            // $.post('/admin/usersDashboardStatus/' + userId, { }, function() {});
            let anchorEl = $(this);

            var formData = {
                "id": userId,
                _token: _token,
            };
            var type = "POST";
            var ajaxurl = '/admin/usersDashboardStatus/';
            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    if (data.data.result) {
                        if (anchorEl.find("span.statusBlocked").length > 0) {
                            anchorEl.html(`<span class="statusActive">Active</span>`);
                        } else
                        if (anchorEl.find("span.statusActive").length > 0) {
                            anchorEl.html(`<span class="statusBlocked">Blocked</span>`);
                        } else
                        if (anchorEl.find("span.statusDeleted").length > 0) {
                            anchorEl.html(`<span class="statusWait">Wait</span>`);
                        } else
                        if (anchorEl.find("span.statusWait").length > 0) {
                            anchorEl.html(`<span class="statusActive">Active</span>`);
                        }
                    } else {
                        console.log("Status change was fail.");
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });
        // Javascript to handle status change (End)

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
            if ($(this).attr("data-column") == "username") {
                if($("#sortBy").val() == "asc") {
                    $("#sortBy").val("desc");
                } else {
                    $("#sortBy").val("asc");
                }
            }

            $('form#filter-form').submit();
        });
        // Javascript to call function immediately when filter change (Start)
    });
</script>

@endsection