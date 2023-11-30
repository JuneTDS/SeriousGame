@extends('layouts.backendSystem_layout')

@section('content')

<a href="{{ url('/admin/permissionInfo/' . $permissionData->name) }}">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Permissions</p>
</a>
<div class="">
    <div class="header-row">
        <div class="left"><h3>View Permission</h3></div>
        <div class="right" style="display: flex; justify-content: space-between;">
            <button type="button" id="update-btn" class="btn btn-dark" style="width:200px;margin-right: 18px;" data-permission-name="{{ $permissionData->name }}">Save</button>
            <a href="/admin/permissionInfo/{{ $permissionData->name }}">
                <button type="button" class="btn btn-outline-dark"  style="width:200px">Cancel</button>
            </a>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
        <button type="button" class="btn btn-cancel" id="close" style="width:100%; margin-top: 10px;">Close Window</button>
    </div>

    <div class="row" style="padding-top:30px">
        <div class="col-md-12">
            <div class="row" style="padding-left:20px">
                <div class="col-md-12">
                    <table class="table leftTable" style="border: none;">
                        <tr>
                            <td style="font-weight:bold">Name</td>
                            <td><input id="permissionName" type="text"class="form-control" required placeholder="Enter Name" value="{{ $permissionData->name }}" ></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Description</td>
                            <td><input id="description" type="text"  class="form-control" placeholder="Enter Description" value="{{ $permissionData->description }}" ></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Permission By Roles*</td>
                            <td>
                                <table id="permissionByRolesTable" class="table">
                                    <tbody>
                                        @foreach($permissionByRoles as $permission)
                                        <tr data-permission-name="{{ $permission }}">
                                            <td>
                                                {{ $permission }} ({{ $permissionByRoleDescriptions[$permission] }})
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <div class="d-flex flex-row align-items-center justify-content-center h-100">
                                    <button id="upBtn" class="btn btn-outline" style="margin-right:24px"><i class="fas fa-arrow-up"></i></button>
                                    <button id="downBtn" class="btn btn-outline"><i class="fas fa-arrow-down"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Item Permissions</td>
                            <td>
                                <table id="itemPermissionsTable" class="table">
                                    <tbody>
                                    @foreach($itemPermissions as $itemPermission)
                                        <tr data-permission-name="{{ $itemPermission->name }}">
                                            <td>{{ $itemPermission->name }} ({{ $itemPermission->description }})</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_RBACPermissionPopup.js') }}"></script>

@endsection