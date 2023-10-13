@extends('layouts.backendSystem_layout')

@section('content')

<!-- Main Content Goes Here -->
<div class="normal-text" style="margin-left: 5%;">
    <a href="/admin/usersDashboard" class="align-self-center col-3 normal-text" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Users</a>
</div>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>{{ $userData->username }}’s Profile</h3></div>
        <div class="right" >
            <div class="d-flex">
                <button type="button" id="update-btn" class="btn btn-dark" style="width:200px">Save</button>
                <a href="/admin/userInfo/{{ $userData->id }}">
                    <button type="button" class="btn btn-outline-dark"  style="width:200px">Cancel</button>
                </a>
            </div>
        </div>
    </div>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon col-1 ">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-3">
            <!-- First column with account image -->
            <div class="account-info-view">
                <div class="account-icon-view">
                    <i class="fas fa-user" style="font-size: 30px; color: #737B7F;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Second column with User Information -->
            <div class="row" style="padding-left:20px">
                <!-- First sub-column -->
                <div class="col-md-9">
                    <form action="/admin/userEditSave" method="post">
                        @csrf
                        <table class="table leftTable" style="border: none;">
                            <input type="hidden" id="userId" value="{{ $userData->id }}">
                            <tr>
                                <td style="font-weight:bold">Username</td>
                                <!-- Input field for Username -->
                                <td><input type="text"class="form-control" required placeholder="Enter username" value="{{ $userData->username }}" id="username"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">First Name</td>
                                <!-- Input field for First Name -->
                                <td><input type="text"  class="form-control" placeholder="Enter first name" value="{{ $userData->first_name }}" id="firstName"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Last Name</td>
                                <!-- Input field for Last Name -->
                                <td><input type="text" class="form-control"  placeholder="Enter last name" value="{{ $userData->last_name }}" id="lastName"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Email Address</td>
                                <!-- Input field for Email Address -->
                                <td><input type="email" value="{{ $userData->email }}" id="email" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Email Gravatar</td>
                                <!-- Input field for Email Gravatar -->
                                <td><input type="email" value="{{ $userData->email_gravatar }}" id="emailGravatar" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Password</td>
                                <!-- Input field for User Role Name -->
                                <td><input type="password" class="form-control" placeholder="Enter password" id="password"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Status</td>
                                <!-- Input field for Status -->
                                <td>
                                    <select class="form-select dropdown" id="status" name="statusDropdown">
                                        <option value="0" {{ $userData->status == 0 ? 'selected' : '' }}>Blocked</option>
                                        <option value="1" {{ $userData->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="2" {{ $userData->status == 2 ? 'selected' : '' }}>Wait</option>
                                        <option value="3" {{ $userData->status == 3 ? 'selected' : '' }}>Deleted</option>
                                    </select>
                                </td>
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
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>

@endsection