@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/usersDashboard">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Users</p>
</a>

<div class="">
    <div class="header-row">
        <div class="left"><h3>{{ $userData->username }}’s Profile</h3></div>
        <div class="right" >
            <div class="row">
                <div class="col-6">
                    <!-- <button type="button"  class="btn btn-outline-dark" style="width:200px">Update</button> -->
                    <form method="GET" action="/admin/userEdit/{{ $userData->id }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark" style="width:200px">Update</button>
                    </form>
                </div>
                <div class="col-6">
                    <!-- <button type="button" id="open-popup-btn" class="btn btn-outline-danger" style="width:200px">Delete</button> -->
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
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Delete</button>
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
                            <td class="text-center" style="font-weight: bold">
                                <label class="status-toggle" data-user-id="{{ $userData->id }}" data-status="{{ $userData->status }}" style="text-decoration: none;">
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
                                </label>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td style="font-weight:bold">Authorization Key</td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        {{ $userData->auth_key }}
                                    </div>
                                    <div class="col-3">
                                        <a href="#" class="generate-key" data-user-id="{{ $userData->id }}" data-status="{{ $userData->status }}">
                                            <button class="btn btn-outline-dark">Generate New key</button>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr> -->
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
    $(document).ready(function () {

        let _token = $('meta[name="csrf-token"]').attr('content');

        $(document).on("click", "#overlay", function(event) {
            if (event.target === document.getElementById('overlay')) {
                $(".popup-form").hide();
                $("#overlay").hide();
            }
        });

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
                        window.location.reload();
                    } else {
                        console.log("Status change was fail.");
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });

        $('.delete-user-btn').on('click', function(e) {
            console.log('delete-user-btn')
            var userId = $(this).attr('data-user-id');
            console.log(userId)
            showDeleteUserPopup(userId);
        });

        $('#delete-btn').on('click', function(e) {
            var userId = $(this).attr('data-user-id');
            deleteUser(userId);
        });

        $('#cancel-btn').on('click', function(e) {
            $('#overlay').hide();
            $('.popup-form').hide();
        });
    })
    // Javascript to handle status change (End)
</script>

@endsection