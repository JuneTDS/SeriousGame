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

        <!-- Delete Popup Form -->
        <div id="delete-popup-form" class="popup-form">
            <div class="row justify-content-center align-items-center ">
                <div class="warning-icon col-1 ">
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
                <div class="warning-icon">
                    <i class="fa fa-check" ></i>
                </div>
            </div>
            <p class="text-center" style="padding-top:50px">[username] has been created succesfully.</p>
        </div>

        <!--  //row start -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <!-- First column containing the input field -->
            <div class="col-4" style="float: left;padding-top:41px">
                <input type="text" class="form-control input-field" id="username" name="username" placeholder="Search user by username or email address">
            </div>

            <!-- Second column containing the "Filter By" text -->
            <div class="col-2" style="text-align: right; padding-top: 42px;">
                <p>Filter By</p>
            </div>

            <!-- Third column containing the first dropdown -->
            <div class="col-2">
                <p>Status</p>
                <select class="form-select dropdown" id="dropdown1" name="dropdown1">
                    <option value="option1">All</option>
                    <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option>
                </select>
            </div>

            <!-- Fourth column containing the second dropdown -->
            <div class="col-2">
                <p>User Role Name</p>
                <select class="form-select dropdown" id="dropdown2" name="dropdown2">
                    <option value="option1">All</option>
                    <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option>
                </select>
            </div>

            <!-- Fifth column containing the third dropdown -->
            <div class="col-2">
                <p>Last Visit</p>
                <input type="date" class="form-control input-field" id="datePicker" name="datePicker">
            </div>
        </div>
        <!-- //row end -->

        <!-- start table -->
        <div class="table-container">
            <table class="table">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>
                        <th>S/N</th>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Status</th>
                        <th>User Role Name</th>
                        <th>Last Visit</th>
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
                        <td style="color: #208D33; font-weight: bold">
                            <button class="status-toggle" data-user-id="{{ $userData->id }}" data-status="{{ $userData->status }}">
                                @php
                                    $statusText = '';
                                    switch ($userData->status) {
                                        case '0':
                                            $statusText = 'Blocked';
                                            break;
                                        case '1':
                                            $statusText = 'Active';
                                            break;
                                        case '2':
                                            $statusText = 'Wait';
                                            break;
                                        case '3':
                                            $statusText = 'Deleted';
                                            break;
                                        default:
                                            $statusText = 'Unknown';
                                            break;
                                    }
                                @endphp
                                {{ $statusText }}
                            </button>
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
                                <i class="fa fa-trash" id="delete-popup-btn"></i>
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
                                <option value="5">5</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
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

<!-- Javascript for User Page Popup -->
<script src="assets\js\backendSystem_UserPopup.js"></script>

<!-- Javascript to handle status change (Start)-->
<script>
    $('.status-toggle').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');   //Get the value of 'user-id' under 'status-toggle' class
        
        // Send a POST request to update the user's status without expecting a response
        $.post('/admin/usersDashboardStatus/' + userId, { }, function() {});
    });
</script>
<!-- Javascript to handle status change (End)-->

@endsection