<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Serious Games</title>

    <link href="/assets/css/common.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/tab_menu.css" rel="stylesheet">
    <link href="/assets/css/range_slider.css" rel="stylesheet">

    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/highcharts.js"></script>
</head>

<body>

    <div class="container">
        <div class="flex-box">
            <div class="menu">
                <div class="logo">
                    <img src="../../assets/images/wannabee_logo_menu.svg" />
                </div>
                <nav>
                    <ul>
                        <li>
                            <a href="/frontend/classes">
                                <img src="../../assets/images/groups.svg" />
                                <label for="">Classes</label>
                            </a>
                        </li>
                        <li>
                            <a href="/frontend/subject">
                                <img src="../../assets/images/menu_book.svg" />
                                <label for="">Subjects</label>
                            </a>
                        </li>
                        <li>
                            <a href="/frontend/feedback">
                                <img src="../../assets/images/thumb_up_off_alt.svg" />
                                <label for="">Feedback</label>
                            </a>
                        </li>
                        <li>
                            <a href="/frontend/profile">
                                <img src="../../assets/images/account_circle.svg" />
                                <label for="">My Profile</label>
                            </a>
                        </li>
                        <li>
                            <a href="/auth/logout">
                                <img src="../../assets/images/logout.svg" />
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