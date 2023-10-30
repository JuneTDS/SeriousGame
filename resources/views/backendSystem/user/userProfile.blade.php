@extends('layouts.backendSystem_layout')

@section('content')

<div class="normal-text" style="margin-left: 5%;">
    <a href="/admin/usersDashboard" class="align-self-center col-3 normal-text" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Users</a>
</div>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>{{ $userData->username }}</h3></div>
        <div class="right" >
            <div class="row">
                <div class="col-6">
                    <!-- <button type="button"  class="btn btn-outline-dark" style="width:200px">Update</button> -->
                    <form method="GET" action="/admin/userProfileEdit/{{ $userData->id }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark" style="width:200px">Update</button>
                    </form>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-danger delete-user-btn" style="width:200px" data-user-id="{{ $userData->id }}">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="delete-popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to delete {{ $userData->username }}’s record?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete User</button>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-3">
            <!-- First column with account image -->
            <div class="account-info-view">
                <div class="account-icon-view">
                    @if ($userData->profile != "null" && $userData->profile != null)
                    <img src="/upload/{{$userData->profile}}" style="
                        border-radius: 50%;
                        width: 100px;
                    "/>
                    @else
                    <i class="fas fa-user" style="font-size: 30px; color: #737B7F;"></i>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Second column with User Information -->
            <div class="row" style="padding-left:20px">
                <!-- First sub-column -->
                <div class="col-md-9">
                    <table class="table leftTable" style="border: none;">
                        <tr>
                            <td style="font-weight:bold">User ID</td>
                            <td>{{ $userData->id }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Username</td>
                            <td>{{ $userData->username }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">First Name</td>
                            <td>{{ $userData->first_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Last Name</td>
                            <td>{{ $userData->last_name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Email Address</td>
                            <td>{{ $userData->email }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Email Gravatar</td>
                            <td>{{ $userData->email_gravatar }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">User Role Name</td>
                            <td>{{ $userData->description }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Status</td>
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
                                                break;
                                            case '3':
                                                $statusText = 'Deleted';
                                                break;
                                            default:
                                                $statusText = 'Unknown';
                                                break;
                                        }
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Created On</td>
                            <td>{{ date('M d, Y, h:i:s A', $userData->created_at) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Updated On</td>
                            <td>{{ date('M d, Y, h:i:s A', $userData->updated_at) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Last Visit On</td>
                            <td>{{ date('M d, Y, h:i:s A', $userData->last_visit) }}</td>
                        </tr>
                    </table>
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
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Javascript to handle status change (Start)
    $('.status-toggle').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');   //Get the value of 'user-id' under 'status-toggle' class
        
        // Send a POST request to update the user's status without expecting a response
        $.post('/admin/usersDashboardStatus/' + userId, { }, function() {});
    });
    // Javascript to handle status change (End)
</script>

@endsection