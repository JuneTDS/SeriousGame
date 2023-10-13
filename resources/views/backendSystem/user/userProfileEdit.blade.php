@extends('layouts.backendSystem_layout')

@section('content')

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>{{ $userData->username }}</h3></div>
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
            <div class="row">
                <div class="account-info-view">
                    <div class="account-icon-view">
                        <i class="fas fa-user" style="font-size: 30px; color: #737B7F;"></i>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <label for="image-upload" class="btn btn-outline-dark" style="width:150px;margin-top:20px">Select Image</label>
                <input type="file" id="image-upload" style="display: none;" />
            </div>
        </div>

        <div class="col-md-9">
            <div class="row" style="padding-left:20px">
                <div class="col-md-9">
                    <form action="/admin/userProfileEditSave" method="post">
                        @csrf
                        <table class="table leftTable" style="border: none;">
                            <input type="hidden" id="userId" value="{{ $userData->id }}">
                            <tr>
                                <td style="font-weight:bold">First Name</td>
                                <td><input type="text"  class="form-control" placeholder="Enter first name" value="{{ $userData->first_name }}" id="firstName"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Last Name</td>
                                <td><input type="text" class="form-control"  placeholder="Enter last name" value="{{ $userData->last_name }}" id="lastName"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Email Address</td>
                                <td><input type="email" value="{{ $userData->email }}" id="email" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Email Gravatar</td>
                                <td><input type="email" value="{{ $userData->email_gravatar }}" id="emailGravatar" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-size: 14px;">
                                    <p>To change the avatar, please use the <span style="font-weight: bold;" onclick="window.location.href='https://gravatar.com'">Gravatar</span> service.</p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <!-- Input field for User Role Name -->
                                <td><button class="btn btn-outline-dark" style="width:139px" id="update-profile-btn">Save</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  user info end -->

    <!--   password field start --><!-- <div class="header-row"></div> -->
    <div class="row" style="padding-top:30px">
        <h4 style="font-weight:normal">Update Password</h4>
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <!-- Second column with User Information -->
            <div class="row" style="padding-left:20px">
                <!-- First sub-column -->
                <div class="col-md-9">
                    <form action="/admin/userProfilePasswordSave" method="post">
                        @csrf
                        <table class="table" style="border: none;">
                            <input type="hidden" id="userId" value="{{ $userData->id }}">
                            <tr>
                                <td style="font-weight:bold">Current Password*</td>
                                <td><input type="password" id="current_Password" name="current_Password" placeholder="Enter current password" class="form-control"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">New Password*</td>
                                <td><input type="password" id="new_Password" name="new_Password" placeholder="Enter new password" class="form-control"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Confirm Password*</td>
                                <td><input type="password" id="confirm_Password" name="confirm_Password" placeholder="Enter new password again" class="form-control"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><button class="btn btn-outline-dark" style="width:139px" id="update_psw_btn">Update</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- password field end -->
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for Popup -->
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection