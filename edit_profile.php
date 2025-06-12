<?php
	session_start();
	
	// Kiểm tra đăng nhập
	if(!isset($_SESSION['email'])) {
		header("Location: index.php");
		exit();
	}
	
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	// Xử lý khi form được submit
	if(isset($_POST['update'])) {
		$name = mysqli_real_escape_string($connection, $_POST['name']);
		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
		$address = mysqli_real_escape_string($connection, $_POST['address']);
		$current_email = $_SESSION['email'];
		
		$query = "update users set name = '$name', email = '$email', mobile = '$mobile', address = '$address' WHERE email = '$current_email'";
		$query_run = mysqli_query($connection, $query);
		
		if($query_run) {
			$_SESSION['email'] = $email; // Cập nhật session với email mới
			echo "<script>
				alert('Cập nhật thông tin thành công');
				window.location.href = 'user_dashboard.php';
			</script>";
			exit();
		} else {
			$error_message = "Có lỗi xảy ra khi cập nhật thông tin!";
		}
	}
	
	// Lấy thông tin hiện tại của user
	$name = "";
	$email = "";
	$mobile = "";
	$address = "";
	
	if(isset($_SESSION['email']) && !empty($_SESSION['email'])) {
		$query = "select * from users where email = '{$_SESSION['email']}'";
		$query_run = mysqli_query($connection,$query);
		if($query_run && mysqli_num_rows($query_run) > 0) {
			while ($row = mysqli_fetch_assoc($query_run)){
				$name = $row['name'] ?? '';
				$email = $row['email'] ?? '';
				$mobile = $row['mobile'] ?? '';
				$address = $row['address'] ?? '';
			}
		} else {
			$error_message = "Không tìm thấy thông tin người dùng!";
		}
	} else {
		header("Location: index.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa hồ sơ LMS</title>
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
                <img src="https://cdn-icons-png.flaticon.com/128/1828/1828032.png" width="40">
                <b>Chỉnh sửa hồ sơ</b>
            </h3>

            <div class="profile-avatar">
                <?php echo $name ? strtoupper(substr($name, 0, 1)) : 'A'; ?>
            </div>
            <h5 class="mb-0"><?php echo htmlspecialchars($name); ?></h5>
            <p class="text-muted text-center">User</p>

            <form method="post">
                <div class="form-group">
                    <label for="name">
                        <img src="https://cdn-icons-png.flaticon.com/128/3596/3596097.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Họ và tên:
                    </label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?php echo htmlspecialchars($name); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">
                        <img src="https://cdn-icons-png.flaticon.com/128/2669/2669570.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Email:
                    </label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="mobile">
                        <img src="https://cdn-icons-png.flaticon.com/128/18472/18472845.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Số điện thoại:
                    </label>
                    <input type="text" id="mobile" name="mobile" class="form-control"
                        value="<?php echo htmlspecialchars($mobile); ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">
                        <img src="https://cdn-icons-png.flaticon.com/128/535/535188.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Địa chỉ:
                    </label>
                    <textarea id="address" name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
                </div>

                <div class="action-buttons d-flex justify-content-center mt-4 gap-2">
                    <button type="submit" name="update" class="btn btn-primary mr-2">Cập nhật hồ sơ</button>
                    <a href="user_dashboard.php" class="btn btn-secondary">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>

    <div style="height: 100px;"></div>
</body>
</html>
