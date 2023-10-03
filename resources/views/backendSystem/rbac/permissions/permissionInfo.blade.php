@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/rbac_PermissionsDashboard" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Permissions</p>
</a>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>View Permission</h3></div>
        <div class="right" >
            <button type="button"  class="btn btn-outline-dark" style="width:200px">Update</button>
            <button type="button" id="open-popup-btn" class="btn btn-outline-danger" style="width:200px">Delete</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to delete this permission?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="create-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="create-btn" style="width:200px">Delete Permission</button>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-3">
        </div>
        <div class="col-md-9">
            <!-- Second column with User Information -->
            <div class="row" style="padding-left:20px">
                <!-- First sub-column -->
                <div class="col-md-9">
                    <table class="table" style=" border: none;">
                        <tr>
                            <td style="font-weight:bold">Name</td>
                            <td>{{ $permissionData->name }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Description</td>
                            <td>{{ $permissionData->description }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Rule Name</td>
                            <td>
                                @if (!empty($permissionData->rule_name))
                                    {{ $permissionData->rule_name }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Data</td>
                            <td>
                                @if (!empty($permissionData->data))
                                    {{ $permissionData->data }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Created On</td>
                            <td>{{ date('M d, Y, h:i:s A', $permissionData->created_at) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Updated On</td>
                            <td>{{ date('M d, Y, h:i:s A', $permissionData->updated_at) }}</td>
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

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_RBACPermissionPopup.js') }}"></script>

@endsection