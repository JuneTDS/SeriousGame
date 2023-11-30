@extends('layouts.backendSystem_layout')

@section('content')

<div class="">
    <div class="header-row">
        <div class="left"><h3>Role Based Access Control</h3></div>
            <div class="right" ></div>
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
                <button type="button" class="btn btn-outline-dark" id="cancel-btn" style="width:200px;margin-right:20px">Cancel</button>
                <button type="button" class="btn btn-danger" id="delete-btn" style="width:200px">Revoke Access</button>
            </div>
        </div>

        <!--  //row star -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <h5>Access Rights</h5>
            </div>
        </div>
        <!-- //row end -->

        <!-- Sortation start-->
        <form href="/admin/rbac_AccessRightsDashboard" id="filter-form">
            <input type="hidden" id="sortBy" name="sortBy" value="">
            <input type="hidden" id="sortColumn" name="sortColumn" value="">
        </form>
        <!-- Sortation end-->

        <!-- start table -->
        <div class="table-container">
            <table class="table tableLeft">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>
                        <th>S/N</th>
                        <th class="sortable" data-column="username">User</th>
                        <th class="sortable" data-column="description">Role</th>
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
                                <i class="fa fa-trash delete-popup-btn" data-id="{{ $assignData->id }}"></i>
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
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<!-- Javascript for Popup -->
<script src="{{ asset('assets/js/backendSystem_RBACAccessRightPopup.js') }}"></script>

<script>
    $(document).ready(function() {
        if (getUrlParameter("sortBy") !== false) {
            $("#sortBy").val(getUrlParameter("sortBy"));
        } else {
            $("#sortBy").val("asc");
        }

        // Javascript to call function immediately when filter change (Start)
        $(document).on("click", ".sortable", function(e) {
            e.preventDefault();
            var attrSort = $(this).attr("data-column");
            
            if ($("#sortBy").val() === "asc") {
                $("#sortBy").val("desc");
            } else {
                $("#sortBy").val("asc");
            }

            // Set sortColumn to the clicked column
            $("#sortColumn").val(attrSort);

            $('form#filter-form').submit();
        });
        // Javascript to call function immediately when filter change (End)
    });

</script>

@endsection