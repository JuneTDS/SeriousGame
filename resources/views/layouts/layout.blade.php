<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Serious Games</title>

    <link href="/assets/css/common.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
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
                            <img src="../../assets/images/groups.svg" />
                            <label for="">Classes</label>
                        </li>
                        <li>
                            <img src="../../assets/images/menu_book.svg" />
                            <label for="">Subjects</label>
                        </li>
                        <li>
                            <img src="../../assets/images/thumb_up_off_alt.svg" />
                            <label for="">Feedback</label>
                        </li>
                        <li>
                            <img src="../../assets/images/account_circle.svg" />
                            <label for="">My Profile</label>
                        </li>
                        <li>
                            <img src="../../assets/images/logout.svg" />
                            <label for="">Logout</label>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
        
    </div>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
</body>
</html>