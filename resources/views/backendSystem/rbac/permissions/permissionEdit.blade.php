@extends('layouts.backendSystem_layout')

@section('content')

<a href="{{ url('/admin/permissionInfo/' . $permissionData->name) }}" style="margin-left: 5%;">
    <p class="align-self-center col-3" style="padding-bottom:20px;font-weight:bold"> ❮  Back to Permissions</p>
</a>
<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>View Permission</h3></div>
        <div class="right" >
            <button type="button" id="open-popup-btn" class="btn btn-dark" style="width:200px">Save</button>
            <button type="button" class="btn btn-outline-dark"  style="width:200px">Cancel</button>
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

    <div class="row" style="padding-top:30px">
        <div class="col-md-12">
            <div class="row" style="padding-left:20px">
                <div class="col-md-12">
                    <table class="table leftTable" style="border: none;">
                        <tr>
                            <td style="font-weight:bold">Name</td>
                            <td><input type="text"class="form-control" required placeholder="Enter Name" value="{{ $permissionData->name }}" ></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Description</td>
                            <td><input type="text"  class="form-control" placeholder="Enter Description" value="{{ $permissionData->description }}" ></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold">Permission By Roles*</td>
                            <td>
                                <table id="permissionByRolesTable" class="table">
                                    <tbody>
                                        @foreach ($permissionByRoles as $index => $permissionByRole)
                                        <tr>
                                            <!-- <td>{{ $permissionByRole->child }} ({{ $permissionByRoleDescriptions[$permissionByRole->child] }})</td> -->
                                            <td>{{ $permissionByRole->child }}
                                                @if(isset($permissionByRoleDescriptions[$permissionByRole->child]))
                                                    ({{ $permissionByRoleDescriptions[$permissionByRole->child] }})
                                                @endif
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
                                        @foreach ($itemPermissions as $index => $itemPermission)
                                        <tr>
                                            <td>{{ $itemPermission->name }} ({{ $itemPermissionsDescriptions[$itemPermission->name] }})</td>
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

<script>
    //To switch permission between table (Start)
document.addEventListener('DOMContentLoaded', function() {
    const itemPermissionsTable = document.getElementById('itemPermissionsTable');
    const permissionByRolesTable = document.getElementById('permissionByRolesTable');
    const upBtn = document.getElementById('upBtn');
    const downBtn = document.getElementById('downBtn');
    let selectedRow;

    // Function to move selected row from itemPermissionsTable to permissionByRolesTable
    function moveRowUp() {
        if (selectedRow && !selectedRow.parentElement.isSameNode(permissionByRolesTable)) {
            itemPermissionsTable.querySelector('tbody').removeChild(selectedRow);
            permissionByRolesTable.querySelector('tbody').appendChild(selectedRow);
            selectedRow.classList.remove('selected');
            selectedRow = null;
        }
    }

    // Function to move selected row from permissionByRolesTable to itemPermissionsTable
    function moveRowDown() {
        if (selectedRow && !selectedRow.parentElement.isSameNode(itemPermissionsTable)) {
            permissionByRolesTable.querySelector('tbody').removeChild(selectedRow);
            itemPermissionsTable.querySelector('tbody').appendChild(selectedRow);
            selectedRow.classList.remove('selected');
            selectedRow = null;
        }
    }

    // Attach click event listeners to move the rows
    upBtn.addEventListener('click', moveRowUp);
    downBtn.addEventListener('click', moveRowDown);

    // Add click event listeners to rows for selecting them
    itemPermissionsTable.querySelectorAll('tr').forEach(row => {
        row.addEventListener('click', () => {
            if (selectedRow) {
                selectedRow.classList.remove('selected');
            }
            row.classList.add('selected');
            selectedRow = row;
        });
    });

    permissionByRolesTable.querySelectorAll('tr').forEach(row => {
        row.addEventListener('click', () => {
            if (selectedRow) {
                selectedRow.classList.remove('selected');
            }
            row.classList.add('selected');
            selectedRow = row;
        });
    });
});
//To switch permission between table (End)
</script>

@endsection