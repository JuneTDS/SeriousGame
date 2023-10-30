@extends('layouts.backendSystem_layout')

@section('content')

<a href="/admin/rbac_AccessRightsDashboard" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Access Rights</p>
</a>

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>View Access Right</h3></div>
        <div class="right" style="display: flex; justify-content: space-between;">
            <a href="{{ url('/admin/assignRightEdit/' . $assignData->id) }}" class="btn-link" style="text-decoration: none;">
                <button type="button" class="btn btn-outline-dark" style="width:200px">Update</button>
            </a>
            <button type="button" class="btn btn-outline-danger delete-popup-btn" style="width:200px" data-id="{{ $assignData->id }}">Revoke</button>
        </div>

    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Delete Popup Form -->
    <div id="delete-popup-form" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="delete-warning-icon col-1 ">
                <i class="fa fa-exclamation"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Are you sure you want to revoke this access?</p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:24px">
            <p class="text-center"><b>This action cannot be undone.</b></p>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Don't Delete</button>
            <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Revoke Access</button>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-2">
        </div>
        <div class="col-md-10">
            <div class="row" style="padding-left:20px">
                <div class="col-md-10">
                    <table class="tabl tableLeft" style=" border: none;">
                        <tr>
                            <td style="font-weight:bold">User ID</td>
                            <td>{{ $assignData->id }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">User Name</td>
                            <td>{{ $assignData->username }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Role</td>
                            <td>{{ $assignData->description }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; vertical-align: top;">Permission By Role</td>
                            <td>
                                <ul class="custom-bullet-list">
                                    @foreach($roleByPermission as $child)
                                        @if(isset($roleDescriptions[$child->child]))
                                            <li>{{ $child->child }} ({{ $roleDescriptions[$child->child] }})</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
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
<script src="{{ asset('assets/js/backendSystem_RBACAccessRightPopup.js') }}"></script>

@endsection