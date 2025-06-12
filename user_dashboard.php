<?php
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ LMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f2f2f2;
        }

        .search-form {
			max-width: 700px;
			flex: 1;
			margin: 0 auto;
            background-color: #fffdfd;
            padding: 12px;
            border-radius: 20px;
			margin-bottom: 30px;
        }

		.search-form .btn {
    		border-radius: 0 5px 5px 0;
    		height: 38px;
			background-color: #3f6680;
			color: white;
		}

		.search-form .btn:hover {
			background-color: rgb(154, 187, 246);
		}

		.search-form .form-control {
			border-radius: 5px 0 0 5px;
			width: 450px;
			border-right: none;
			height: 38px;
		}

        .header {
            background-color: #32434e;
            color: rgb(17, 17, 17);
            font-size: 25px;
            font-weight: bold;
            display: flex;                  
            justify-content: space-between; 
            align-items: center;           
            padding: 15px 30px;            
        }

		.hello {
			background-image: url('https://images.unsplash.com/photo-1553448540-fe069f7c95bf?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
  			background-size: cover 110%;        
			background-position: center 52.5%;
			filter: brightness(1.2);
			padding: 2rem;
			margin-bottom: 30px;
		}

        .nav-links a {
            margin: 0 10px;
            color: rgb(11, 11, 11);
            text-decoration: none;
            font-size: 18px;
        }
        .nav-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-links a:hover {
            text-decoration: underline;
			color: rgb(154, 187, 246) !important;
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
			color:rgb(2, 62, 82)
		}
		.btn-success:hover {
			background-color: #63dcc1;
			border-radius: 10px;
			color:rgb(23, 23, 23)
		}
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <a href="user_dashboard.php" style="color: white">
                Hệ thống LMS  
                <img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
            </a> 
        </div>
        <div class="nav-links">
            <ul class="navbar-nav ml-auto flex-row ">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button" data-toggle="dropdown" style="color: rgb(205, 203, 203)">Quản lý sách</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="borrow_book.php">Mượn sách</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="return_book.php">Trả sách</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" style="color: rgb(205, 203, 203)">Hồ sơ của tôi</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="view_profile.php">Xem hồ sơ</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="edit_profile.php">Chỉnh sửa hồ sơ</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="change_password.php">Đổi mật khẩu</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php" style="color: rgb(205, 203, 203)">Đăng xuất</a>
				</li>
			</ul>
        </div>
    </div>

	<!-- Banner -->
	<div class="hello">
		<h1 class="text-center"> 
			<img src="https://cdn-icons-png.flaticon.com/128/5849/5849203.png" width="50">
			<b>Chào mừng bạn đến với Hệ thống LMS</b>
		</h1>
		<h5 class="text-center"><b><i>Tìm kiếm và quản lý sách dễ dàng hơn bao giờ hết</i></b></h5>
	</div>

    <!-- Tìm kiếm sách -->
    <div class="d-flex justify-content-center">
        <div class="search-form card shadow">
            <h4 class="text-center mb-1">
                <img src="https://cdn-icons-png.flaticon.com/128/18717/18717382.png" width="30">
                <b>Tìm kiếm sách trong thư viện</b>
            </h4>
			<form class="search-form" action="search_book.php" method="GET">
				<div class="input-group">
					<input class="form-control" type="search" name="query" placeholder="Nhập tên sách hoặc tên tác giả..." required>
					<div class="input-group-append">
						<button class="btn btn-outline-success" type="submit"><b>Tìm kiếm</b></button>
					</div>
				</div>
			</form>          
        </div>
    </div>

	<!-- Sách mượn, Sách chưa trả -->
	<div class="row justify-content-center">
		<div class="col-md-5">
			<div class="card dashboard-card">
				<div class="card-header text-center">
					<img src="https://cdn-icons-png.flaticon.com/128/2417/2417791.png" width="40">
					Sách đang mượn
				</div>
				<div class="card-body text-center">
					<p class="card-text text-white mb-3">Xem danh sách các sách bạn đang mượn</p>
					<a class="btn btn-success" href="view_issued_book.php">
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
					Sách chưa trả
				</div>
				<div class="card-body text-center">
					<p class="card-text text-white mb-3">Kiểm tra và trả sách đã hết hạn</p>
					<a class="btn btn-success" href="return_book.php">
						<img src="https://cdn-icons-png.flaticon.com/128/3240/3240706.png" width="30">
						Xem thêm
					</a>
				</div>
			</div>
		</div>
	</div>

	<div style="height: 100px;"></div>
</body>
</html>
