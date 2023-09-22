<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="/assets/css/backendSystem.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Start Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="text-white min-vh-100" style="width: 100%;">
                    <a href="#" class="d-flex align-items-center justify-content-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <img src="../assets/images/wannabee_logo.svg" alt="Your Logo" class="navbar-logo">
                    </a>
                    <div class="divider"></div>
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0" id="menu" style="padding-left: 20px; padding-right: 20px;">
                            <li class="nav-item">
                                <a href="/admin/usersDashboard" class="nav-link px-0 text-white">
                                    <i class="fas fa-users"></i> <span class="ms-1 d-none d-sm-inline">Users</span>
                                </a>
                            </li>
                            <li class="nav-item position-relative">
                                <a href="#" class="nav-link px-0 align-middle text-white">
                                    <i class="fas fa-users-cog"></i> <span class="ms-1 d-none d-sm-inline">RABC</span>
                                    <span class="dropdown-icon"></span>
                                </a>
                                <!-- Sub-menu under RABC -->
                                <ul class="sub-menu">
                                    <li>
                                        <a href="/admin/rbac_PermissionsDashboard" class="nav-link px-0 align-middle text-white">
                                            <span class="ms-1 d-none d-sm-inline">Permissions</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/rbac_RolesDashboard" class="nav-link px-0 align-middle text-white">
                                            <span class="ms-1 d-none d-sm-inline">Roles</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/rbac_AccessRightsDashboard" class="nav-link px-0 align-middle text-white">
                                            <span class="ms-1 d-none d-sm-inline">Access Rights</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/subjectsDashboard" class="nav-link px-0 align-middle text-white">
                                    <i class="fas fa-book"></i> <span class="ms-1 d-none d-sm-inline">Manage Subjects</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/subjectEnrollmentsDashboard" class="nav-link px-0 align-middle text-white">
                                    <i class="fas fa-book-open"></i> <span class="ms-1 d-none d-sm-inline">Manage Subject Enrollments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/lectureClassesDashboard" class="nav-link px-0 align-middle text-white">
                                    <i class="fas fa-chalkboard-teacher"></i> <span class="ms-1 d-none d-sm-inline">Manage Lecture Class</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/modeSiteDashboard" class="nav-link px-0 align-middle text-white">
                                    <i class="fas fa-globe"></i> <span class="ms-1 d-none d-sm-inline">Mode Site</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--end sidebar-->
            </div>

            <!-- Main Content Goes Here -->
            <div class="col py-3" style="margin-left: 15%;">
                <div class="row">
                    <div class="col">
                        <div class="account-info" onclick="toggleDropdownMenu()">
                            <!-- Account icon here using FontAwesome icon -->
                            <div class="account-icon">
                                <i class="fas fa-user" style="font-size: 16px; color: white;"></i>
                            </div>
                            {{ Auth::user()->username }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="dropdown-container">
                        <div class="dropdown-content" id="myDropdown">
                            <a href="/admin/usersProfile">User Profile</a>
                            <a href="/auth/logout">Logout</a>
                        </div>
                    </div>
                </div>
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