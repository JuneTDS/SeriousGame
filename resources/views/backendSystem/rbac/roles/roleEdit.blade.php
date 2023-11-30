@extends('layouts.backendSystem_layout')

@section('content')

<a href="{{ url('/admin/roleInfo/' . $roleData->name) }}">
  <p class="align-self-center col-3" style="padding-left:0px;padding-bottom:20px;font-weight:bold"> ❮  Back to Roles</p>
</a>

<div class="">
  <div class="header-row">
    <div class="left"><h3>View Role</h3></div>
    <div class="right" style="display: flex; justify-content: space-between;">
      <button type="button" id="update-btn" class="btn btn-dark" style="width:200px" data-role-name="{{ $roleData->name }}">Save</button>
      <a href="{{ url('/admin/roleInfo/' . $roleData->name) }}" style="margin-left: 5%;">
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
              <td><input type="text"class="form-control" id="role_name" required placeholder="Enter Name" value="{{ $roleData->name }}"></td>
            </tr>
            <tr>
              <td style="font-weight:bold">Description</td>
              <td>
                <input type="text"  class="form-control" id="role_description" placeholder="Enter Description" value="{{ $roleData->description }}">
              </td>
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
<script src="{{ asset('assets/js/backendSystem_RBACRolePopup.js') }}"></script>

@endsection