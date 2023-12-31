@extends('layouts.backendSystem_layout')

@section('content')
        
<a href="/admin/rbac_AccessRightsDashboard">
    <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Access Rights</p>
</a>

<div class="">

    <div class="header-row">
        <div class="left"><h3>Access Rights</h3></div>
        <div class="right" style="display: flex; justify-content: space-between;">
            <button type="button" id="update-btn" class="btn btn-dark" style="width:200px;margin-right:20px;">Save</button>
            <a href="/admin/assignRightInfo/{{ $assignData->id }}">
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

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-12">
            <div class="row justify-content-center" style="padding-left:20px">
                <div class="col-md-6">
                    <table class="table" style="border: none;">
                        <input type="hidden" id="userId" value="{{ $assignData->id }}">
                        <tr>
                            <td style="font-weight:bold;padding-top:10px;padding-right:30px">Role</td>
                            <td>
                                <select class="form-select form-control form-select" style="width:400px" id="roleDescription">
                                    @foreach ($roleDescriptions as $roleDescription)
                                        <option value="{{ $roleDescription }}" {{ $roleDescription == $assignData->name ? 'selected' : '' }}>
                                            {{ $roleDescription }}
                                        </option>
                                    @endforeach
                                </select>
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