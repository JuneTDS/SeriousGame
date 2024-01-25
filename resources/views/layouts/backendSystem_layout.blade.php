<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Serious Games</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="/assets/css/common.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/backendSystem.css">
    <link href="/assets/css/range_slider.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>

    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/highcharts.js"></script>

    <script src="/assets/js/common.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
</head>

<body>

    <div>
        <div class="flex-box">
            <div class="menu">
                <div class="logo">
                    <img src="../../assets/images/wannabee_logo_menu.svg" />
                </div>
                <nav>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0" id="menu">
                        <li class="nav-item">
                            <a href="/admin/usersDashboard" class="nav-link px-0">
                                <i class="fas fa-users"></i> <span class="ms-1 d-none d-sm-inline">Users</span>
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a href="#" class="nav-link px-0 align-middle">
                                <i class="fas fa-users-cog"></i> <span class="ms-1 d-none d-sm-inline">RBAC</span>
                                <span class="dropdown-icon"></span>
                            </a>
                            <!-- Sub-menu under RABC -->
                            <ul class="sub-menu" style="margin-top: 20px">
                                <li>
                                    <a href="/admin/rbac_PermissionsDashboard" class="nav-link px-0 align-middle">
                                        <span class="ms-1 d-none d-sm-inline">Permissions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/rbac_RolesDashboard" class="nav-link px-0 align-middle">
                                        <span class="ms-1 d-none d-sm-inline">Roles</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/rbac_AccessRightsDashboard" class="nav-link px-0 align-middle">
                                        <span class="ms-1 d-none d-sm-inline">Access Rights</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/subjectsDashboard" class="nav-link px-0 align-middle">
                                <i class="fas fa-book"></i> <span class="ms-1 d-none d-sm-inline">Manage Subjects</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/subjectEnrollmentsDashboard" class="nav-link px-0 align-middle">
                                <i class="fas fa-book-open"></i> <span class="ms-1 d-none d-sm-inline">Manage Subject Enrolments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/lectureClassesDashboard" class="nav-link px-0 align-middle">
                                <i class="fas fa-chalkboard-teacher"></i> <span class="ms-1 d-none d-sm-inline">Manage Lecture Class</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/classCodesDashboard" class="nav-link px-0 align-middle">
                                <i class="fas fa-graduation-cap"></i> <span class="ms-1 d-none d-sm-inline">Manage Class Code</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/frontend/profile" class="nav-link px-0 align-middle">
                                <img src="../../assets/images/account_circle.svg" style="margin-right: 15px;"/>
                                <label for="">My Profile</label>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/auth/logout" class="nav-link px-0 align-middle">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="ms-1 d-none d-sm-inline">Logout</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content Goes Here -->
            <div class="content">
                <div class="row" style="position: absolute;top: 20px;right: 20px;">
                    <!-- <div class="col">
                        <div class="account-info" onclick="toggleDropdownMenu()">
                            <div class="account-icon">
                                <i class="fas fa-user" style="font-size: 16px; color: white;"></i>
                            </div>
                            {{ Auth::user()->username }}
                        </div>
                    </div> -->

                    <div class="dropdown-container">
                        <div class="dropdown-content" id="myDropdown">
                            <a href="{{ url('/admin/usersProfile', ['id' => auth()->user()->id]) }}">User Profile</a>
                            <!-- <a href="/auth/logout">Logout</a> -->
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    
                </div> -->
                <div>
                    @yield('content')

                    <!-- Bootstrap JavaScript (optional, for certain features like dropdowns, modals, etc.) -->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    <!-- jQuery (required for dynamic pagination) -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <!-- Pagination Javascript -->
                    <script src="{{ asset('assets\js\backendSystem_Pagination.js') }}"></script>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript (if required) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <!-- Javascript for User Profile Dropdown -->
    <script src="{{ asset('assets\js\backendSystem.js') }}"></script>
</body>
</html>