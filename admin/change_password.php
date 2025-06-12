<?php
	require("functions.php");
	session_start();
	
	// Lấy thông tin admin để hiển thị avatar
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	$name = "";
	$email = "";
	if(isset($_SESSION['email'])) {
		$admin_email_session = mysqli_real_escape_string($connection, $_SESSION['email']);
		$query = "select admin_name, admin_email from admins where admin_email = '$admin_email_session'";
		$query_run = mysqli_query($connection,$query);
		while ($row = mysqli_fetch_assoc($query_run)){
			$name = $row['admin_name'];
			$email = $row['admin_email'];
		}
	}

	// Handle password change form submission
	if (isset($_POST['change_password'])) {
		$current_password = mysqli_real_escape_string($connection, $_POST['current_password']);
		$new_password = mysqli_real_escape_string($connection, $_POST['new_password']);
		$confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);

		$admin_email_for_update = mysqli_real_escape_string($connection, $_SESSION['email']);

		// Fetch current password from database
		$password_check_query = "SELECT admin_password FROM admins WHERE admin_email = '$admin_email_for_update'";
		$password_check_run = mysqli_query($connection, $password_check_query);

		if (mysqli_num_rows($password_check_run) > 0) {
			$row = mysqli_fetch_assoc($password_check_run);
			$hashed_password_from_db = $row['admin_password'];

			// Verify current password
			if (password_verify($current_password, $hashed_password_from_db)) {
				// Check if new password matches confirm password
				if ($new_password === $confirm_password) {
					// Hash new password
					$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

					// Update password in database
					$update_password_query = "UPDATE admins SET admin_password = '$hashed_new_password' WHERE admin_email = '$admin_email_for_update'";
					if (mysqli_query($connection, $update_password_query)) {
						$_SESSION['success_message'] = "Mật khẩu đã được đổi thành công!";
						header("Location: view_profile.php"); // Redirect to view profile or a success page
						exit();
					} else {
						$_SESSION['error_message'] = "Có lỗi xảy ra khi đổi mật khẩu: " . mysqli_error($connection);
					}
				} else {
					$_SESSION['error_message'] = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
				}
			} else {
				$_SESSION['error_message'] = "Mật khẩu hiện tại không đúng.";
			}
		} else {
			$_SESSION['error_message'] = "Không tìm thấy tài khoản quản trị viên.";
		}
	}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đổi mật khẩu - LMS</title>
	<!-- Thêm vào phần <head> nếu chưa có Bootstrap -->
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
		.password-container {
			max-width: 600px;
			margin: 0 auto;
			background-color: white;
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
		}
		.form-group {
			margin-bottom: 20px;
		}
		.form-group label {
			color: #32434e;
			font-weight: 500;
			margin-bottom: 5px;
		}
		.form-control {
			border: 1px solid #ddd;
			border-radius: 5px;
			padding: 10px;
			font-size: 16px;
		}
		.form-control:focus {
			border-color: #32434e;
			box-shadow: 0 0 0 0.2rem rgba(50, 67, 78, 0.25);
		}
		.btn-save {
			background-color: #28a745;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			font-size: 16px;
			cursor: pointer;
		}
		.btn-save:hover {
			background-color: #218838;
		}
		.btn-cancel {
			background-color: #dc3545;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			font-size: 16px;
			text-decoration: none;
			margin-left: 10px;
		}
		.btn-cancel:hover {
			background-color: #c82333;
			color: white;
			text-decoration: none;
		}
		.alert {
			border-radius: 5px;
			margin-bottom: 20px;
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
				<!-- Quản lý sách -->
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

				<!-- Quản lý người dùng -->
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

				<!-- Hồ sơ -->
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

				<!-- Đăng xuất -->
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
			<b>Đổi mật khẩu</b>
		</h1>
		<h5 class="text-center"> <b><i>Cập nhật mật khẩu tài khoản của bạn</i></b></h5>
	</div>

	<!-- Password Change Container -->
	<div class="password-container">
		<?php if(isset($_SESSION['error_message'])): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
			</div>
		<?php endif; ?>

		<?php if(isset($_SESSION['success_message'])): ?>
			<div class="alert alert-success" role="alert">
				<?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
			</div>
		<?php endif; ?>

		<form action="" method="POST">
			<div class="form-group">
				<label for="current_password">Mật khẩu hiện tại</label>
				<input type="password" class="form-control" id="current_password" name="current_password" required>
			</div>

			<div class="form-group">
				<label for="new_password">Mật khẩu mới</label>
				<input type="password" class="form-control" id="new_password" name="new_password" required>
			</div>

			<div class="form-group">
				<label for="confirm_password">Xác nhận mật khẩu mới</label>
				<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
			</div>

			<div class="text-center">
				<button type="submit" name="change_password" class="btn-save">Đổi mật khẩu</button>
				<a href="view_profile.php" class="btn-cancel">Hủy</a>
			</div>
		</form>
	</div>
</body>
</html>