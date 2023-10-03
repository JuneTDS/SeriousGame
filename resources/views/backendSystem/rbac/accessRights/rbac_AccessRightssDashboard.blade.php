@extends('layouts.backendSystem_layout')

@section('content')

<div class="container custom-container">
    <div class="header-row">
        <div class="left"><h3>Row Based Access Control</h3></div>
            <div class="right" >
                <button type="button" id="open-popup-btn" class="btn btn-outline-dark">Create New Role</button>
            </div>
        </div>

        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>

        <!-- Popup Form -->
        <div id="popup-form" class="popup-form">
            <h3 class="mb-4">Create New Role</h3>
            <div class="mb-3">
                <label for="name" class="form-label">Name*</label>
                <input type="text" class="form-control" id="name" required placeholder="Example: updatePost">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" required placeholder="Enter description">
            </div>
            <button type="button" class="btn btn-dark" id="create-btn" style="width:526px">Create</button>
        </div>

        <!-- Success Popup -->
        <div id="success-popup" class="popup-form">
            <!-- #1de9b6 -->
            <div class="row justify-content-center align-items-center ">
                <div class="warning-icon">
                    <i class="fa fa-check" ></i>
                </div>
            </div>
            <p class="text-center" style="padding-top:50px">A new role has been created.</p>
        </div>

        <!--  //row star -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <h5>Access Rights</h5>
            </div>
        </div>
        <!-- //row end -->

        <!-- start table -->
        <div class="table-container">
            <table class="table">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>
                        <th>S/N</th>
                        <th>User</th>
                        <th>Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody style="background-color: #Neutral/50;">
                    @foreach ($assigns as $index => $assignData)
                    <tr style="color:#737B7F">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $assignData->username }}</td>
                        <td>{{ $assignData->description }}</td>
                        <td>
                            <div class="icon-container">
                                <a href="{{ url('/admin/assignRightInfo/' . $assignData->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ url('/admin/assignRightEdit/' . $assignData->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <i class="fa fa-trash delete-popup-btn" data-user-id="{{ $assignData->id }}"></i>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="icon-container">
                    <div class="items-per-page-dropdown">
                        <div class="col">
                            <p style="font-size:14px">Item Per Page</p>
                            <select id="items-per-page">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25" selected>25</option> <!-- Set as default -->
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="pagination-container" style="padding-top:110px">
                    <nav aria-label="Page navigation">
                        <ul class="pagination" id="pagination">
                        <!-- Pagination links will be dynamically generated here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- end table -->
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for User Page Popup -->
<script src="{{ asset('assets/js/backendSystem_RBACAccessRightPopup.js') }}"></script>

@endsection