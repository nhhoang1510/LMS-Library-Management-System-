<?php
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$name = "";
	$email = "";
	$mobile = "";
	$address = "";
	$query = "select * from users where email = '$_SESSION[email]'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$name = $row['name'];
		$email = $row['email'];
		$mobile = $row['mobile'];
		$address = $row['address'];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Hồ sơ của tôi LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
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

        .edit {
            margin-top: 50px;
            width: 100%;
            max-width: 480px;
            background-color: #fffdfd;
            padding: 60px;
            border-radius: 20px;
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

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: #6b7d91;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <a href="user_dashboard.php" style="color: white">
                Hệ thống LMS  
                <img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
            </a>
        </div>

        <div class="nav-links">
            <ul class="navbar-nav ml-auto flex-row">
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

    <div class="d-flex justify-content-center">
        <div class="edit card shadow">
            <h3 class="text-center mb-1">
                <img src="https://cdn-icons-png.flaticon.com/128/17295/17295987.png" width="45">
                <b>Hồ sơ của tôi</b>
            </h3>

            <div class="profile-avatar">
                <?php echo $name ? strtoupper(substr($name, 0, 1)) : 'A'; ?>
            </div>
            <h5 class="mb-0"><?php echo htmlspecialchars($name); ?></h5>
            <p class="text-muted text-center">User</p>

            <form method="post">
                <div class="form-group">
					<label for="name"><i class="fas fa-user"></i> 
						<img src="https://cdn-icons-png.flaticon.com/128/3596/3596097.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
						Họ và tên:
					</label>
						<input type="text" id="name" class="form-control" value="<?php echo htmlspecialchars($name);?>" disabled>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i>
                        <img src="https://cdn-icons-png.flaticon.com/128/2669/2669570.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Email:
                    </label>
                    <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email);?>" disabled>
                </div>

                <div class="form-group">
                    <label for="mobile"><i class="fas fa-phone"></i>
                        <img src="https://cdn-icons-png.flaticon.com/128/18472/18472845.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Số điện thoại:
                    </label>
                    <input type="text" id="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile);?>" disabled>
                </div>

                <div class="form-group">
                    <label for="address"><i class="fas fa-map-marker-alt"></i>
                        <img src="https://cdn-icons-png.flaticon.com/128/535/535188.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Địa chỉ:
                    </label>
                    <input type="text" id="address" class="form-control" value="<?php echo htmlspecialchars($address);?>" disabled>
                </div>

                <div class="action-buttons d-flex justify-content-center mt-4 gap-2">
					<a href="edit_profile.php" class="btn btn-primary mr-2">
						<i class="fas fa-edit"></i> Chỉnh sửa hồ sơ
					</a>
					<a href="change_password.php" class="btn btn-secondary">
						<i class="fas fa-key"></i> Đổi mật khẩu
					</a>
				</div>
            </form>
        </div>
    </div>

    <div style="height: 100px;"></div>
</body>
</html>
