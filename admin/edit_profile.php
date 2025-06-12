<?php
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	// Initialize variables for the form
	$name = "";
	$email = "";
	$mobile = "";
	$error_message = "";

	// Fetch current admin details from session email
	if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
		$current_email_session = mysqli_real_escape_string($connection, $_SESSION['email']);
		$fetch_query = "SELECT admin_name, admin_email, admin_mobile FROM admins WHERE admin_email = '$current_email_session'";
		$fetch_query_run = mysqli_query($connection, $fetch_query);

		if ($fetch_query_run && mysqli_num_rows($fetch_query_run) > 0) {
			$row = mysqli_fetch_assoc($fetch_query_run);
			$name = $row['admin_name'];
			$email = $row['admin_email'];
			$mobile = $row['admin_mobile'];
		} else {
			$error_message = "Không tìm thấy thông tin quản trị viên.";
		}
	} else {
		// If session email is not set, redirect to login or dashboard
		header("Location: admin_login.php"); // Or admin_dashboard.php if appropriate
		exit();
	}

	// Handle form submission for update
	if(isset($_POST['update'])) {
		$new_name = mysqli_real_escape_string($connection, $_POST['admin_name']);
		$new_email = mysqli_real_escape_string($connection, $_POST['admin_email']);
		$new_mobile = mysqli_real_escape_string($connection, $_POST['admin_mobile']);
		
		// Use the email from session as the identifier for the update query
		$current_email_for_update = mysqli_real_escape_string($connection, $_SESSION['email']);
		
		$update_query = "UPDATE admins SET admin_name = '$new_name', admin_email = '$new_email', admin_mobile = '$new_mobile' WHERE admin_email = '$current_email_for_update'";
		$update_query_run = mysqli_query($connection, $update_query);
		
		if($update_query_run) {
			// Update session variables with new data
			$_SESSION['email'] = $new_email;
			$_SESSION['admin_name'] = $new_name; // Assuming admin_name is stored in session
			$_SESSION['admin_mobile'] = $new_mobile; // Assuming admin_mobile is stored in session

			$_SESSION['success_message'] = "Cập nhật hồ sơ thành công!";
			header("Location: view_profile.php"); // Redirect to view profile page
			exit();
		} else {
			$_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật thông tin: " . mysqli_error($connection);
			header("Location: edit_profile.php"); // Redirect back to edit with error
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chỉnh sửa hồ sơ - LMS</title>
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
		.edit-container {
			max-width: 800px;
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
			<b>Chỉnh sửa hồ sơ</b>
		</h1>
		<h5 class="text-center"> <b><i>Cập nhật thông tin cá nhân của bạn</i></b></h5>
	</div>

	<!-- Edit Form Container -->
	<div class="edit-container">
		<?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
		<form action="" method="POST">
			<div class="form-group">
				<label for="name">Họ và tên</label>
				<input type="text" class="form-control" id="name" name="admin_name" value="<?php echo htmlspecialchars($name); ?>" required>
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($email); ?>" required>
			</div>

			<div class="form-group">
				<label for="phone">Số điện thoại</label>
				<input type="text" class="form-control" id="admin_mobile" name="admin_mobile" value="<?php echo htmlspecialchars($mobile); ?>" required>
			</div>

			<div class="text-center">
				<button type="submit" name="update" class="btn-save">Lưu thay đổi</button>
				<a href="view_profile.php" class="btn-cancel">Hủy</a>
			</div>
		</form>
	</div>
</body>
</html>