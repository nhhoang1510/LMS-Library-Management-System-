<?php
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ Admin LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f2f2f2;
        }
        .header {
            background-color: #2c3e50;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo a {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links .nav-link {
            color: white !important;
            margin: 0 10px;
        }

        .dropdown-menu {
            min-width: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            animation: fadeIn 0.3s ease-in-out;
        }

        .dropdown-menu a.dropdown-item:hover {
            background-color: #ecf0f1;
            color: #2c3e50;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(10px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .hello {
            background-image: url('https://images.unsplash.com/photo-1553448540-fe069f7c95bf?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover 110%;        
            background-position: center 52.5%;
            filter: brightness(1.2);
            padding: 2rem;
            margin-bottom: 30px;
        }
        .dashboard-card {
            max-width: 700px;
            flex: 1;
            margin: 0 auto;
            background-color: #314b5c;
            padding: 0px;
            border-radius: 20px;
            color: white;
            font-size: 18px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .card-header {
            background-color: #ffffff;
            color: #2a3a44;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
        }
        .btn-success {
            background-color: rgb(207, 224, 253);
            border-radius: 10px;
            color: rgb(2, 62, 82);
        }
        .btn-success:hover {
            background-color: #63dcc1;
            border-radius: 10px;
            color: rgb(23, 23, 23);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <a href="admin_dashboard.php" style="color: white">
                Hệ thống LMS  
                <img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
            </a> 
        </div>
        <div class="nav-links">
            <ul class="navbar-nav ml-auto flex-row">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Quản lý sách
                    </a>
                    <div class="dropdown-menu" aria-labelledby="bookDropdown">
                        <a class="dropdown-item" href="add_book.php">Thêm sách</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_book.php">Quản lý sách</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Quản lý người dùng
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="Regusers.php">Danh sách người dùng</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_requests.php">Quản lý yêu cầu</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Hồ sơ
                    </a>
                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="view_profile.php">Xem hồ sơ</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Chỉnh sửa hồ sơ</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Đổi mật khẩu</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Đăng xuất</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Banner -->
    <div class="hello">
        <h1 class="text-center"> 
            <img src="https://cdn-icons-png.flaticon.com/128/5849/5849203.png" width="50">
            <b>Chào mừng Admin đến với Hệ thống LMS</b>
        </h1>
        <h5 class="text-center"> <b><i>Quản lý thư viện hiệu quả và dễ dàng</i></b></h5>
    </div>

    <!-- Dashboard Cards -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card dashboard-card">
                    <div class="card-header text-center">
                        <img src="https://cdn-icons-png.flaticon.com/128/2417/2417791.png" width="40">
                        Quản lý sách
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-white mb-3">Thêm và quản lý sách trong thư viện</p>
                        <a class="btn btn-success" href="manage_book.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/3240/3240706.png" width="30">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="card dashboard-card">
                    <div class="card-header text-center">
                        <img src="https://cdn-icons-png.flaticon.com/128/9436/9436168.png" width="40">
                        Quản lý người dùng
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-white mb-3">Xem và quản lý người dùng hệ thống</p>
                        <a class="btn btn-success" href="Regusers.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/3240/3240706.png" width="30">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card dashboard-card">
                    <div class="card-header text-center">
                        <img src="https://cdn-icons-png.flaticon.com/128/2417/2417791.png" width="40">
                        Quản lý yêu cầu
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-white mb-3">Xử lý các yêu cầu mượn/trả sách</p>
                        <a class="btn btn-success" href="manage_requests.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/3240/3240706.png" width="30">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="card dashboard-card">
                    <div class="card-header text-center">
                        <img src="https://cdn-icons-png.flaticon.com/128/9436/9436168.png" width="40">
                        Sách đang được mượn
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-white mb-3">Xem danh sách sách đã được mượn</p>
                        <a class="btn btn-success" href="view_issued_book.php">
                            <img src="https://cdn-icons-png.flaticon.com/128/3240/3240706.png" width="30">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>