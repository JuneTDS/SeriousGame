<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Serious Games</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>

    <link href="/assets/css/common.css?<?= env('JS_VERSION') ?>" rel="stylesheet">
    <link href="/assets/css/style.css?<?= env('JS_VERSION') ?>" rel="stylesheet">
    <link href="/assets/css/tab_menu.css?<?= env('JS_VERSION') ?>" rel="stylesheet">
    <link href="/assets/css/range_slider.css?<?= env('JS_VERSION') ?>" rel="stylesheet">

    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/highcharts.js"></script>
</head>

<body>

    <div class="">
        <div class="flex-box">
            <div class="menu">
                <div class="logo">
                    <img src="../../assets/images/wannabee_logo_menu.svg" />
                </div>
                <nav>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0">
                        @if(Auth::user()->role == "Lecturer_Manager" || Auth::user()->role == "Lecturer")
                        <li>
                            <a class="nav-link" href="/frontend/classes">
                                <img src="../../assets/images/groups.svg" style="margin-right: 15px;"/>
                                <label for="">Classes</label>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="/frontend/subject">
                                <img src="../../assets/images/menu_book.svg" style="margin-right: 15px;"/>
                                <label for="">Subjects</label>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="/frontend/feedback">
                                <img src="../../assets/images/thumb_up_off_alt.svg" style="margin-right: 15px;"/>
                                <label for="">Feedback</label>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->role == "Student")
                        <li>
                            <a class="nav-link" href="/frontend/studentSubject">
                                <i class="fas fa-gamepad" style="margin-right: 15px;color:#a1acb1"></i>
                                <label for="">Student Subjects</label>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="nav-link" href="/frontend/profile">
                                <img src="../../assets/images/account_circle.svg" style="margin-right: 15px;"/>
                                <label for="">My Profile</label>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="/auth/logout">
                                <img src="../../assets/images/logout.svg" style="margin-right: 15px;"/>
                                <label for="">Logout</label>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
        
    </div>
</body>
</html>