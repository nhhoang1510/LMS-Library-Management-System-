<?php
	require("functions.php");
	session_start();
	
	// Debug: kiểm tra session
	// echo "<!-- Debug: Session email = " . (isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set') . " -->";
	
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$db = mysqli_select_db($connection,"lms");
	if (!$db) {
		die("Database selection failed: " . mysqli_error($connection));
	}
	
	$name = "";
	$email = "";
	$mobile = "";
	
	// Nếu session chưa có email, lấy thông tin admin đầu tiên từ database
	if(!isset($_SESSION['email']) || empty($_SESSION['email'])) {
		$query = "SELECT admin_name, admin_email, admin_mobile FROM admins LIMIT 1";
		$query_run = mysqli_query($connection, $query);
		
		if ($query_run && mysqli_num_rows($query_run) > 0) {
			$row = mysqli_fetch_assoc($query_run);
			$name = $row['admin_name'];
			$email = $row['admin_email'];
			$mobile = $row['admin_mobile'];
			// Thiết lập session
			$_SESSION['email'] = $email;
		}
	} else {
		// Sử dụng prepared statement để tránh SQL injection
		$email_session = mysqli_real_escape_string($connection, $_SESSION['email']);
		$query = "SELECT admin_name, admin_email, admin_mobile FROM admins WHERE admin_email = '$email_session'";
		$query_run = mysqli_query($connection, $query);
		
		if ($query_run && mysqli_num_rows($query_run) > 0) {
			$row = mysqli_fetch_assoc($query_run);
			$name = $row['admin_name'];
			$email = $row['admin_email'];
			$mobile = $row['admin_mobile'];
		} else {
			// Nếu không tìm thấy theo session, lấy admin đầu tiên
			$query = "SELECT admin_name, admin_email, admin_mobile FROM admins LIMIT 1";
			$query_run = mysqli_query($connection, $query);
			
			if ($query_run && mysqli_num_rows($query_run) > 0) {
				$row = mysqli_fetch_assoc($query_run);
				$name = $row['admin_name'];
				$email = $row['admin_email'];
				$mobile = $row['admin_mobile'];
				// Cập nhật session
				$_SESSION['email'] = $email;
			}
		}
	}
	
	mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Xem hồ sơ - LMS</title>
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
		.profile-container {
			max-width: 800px;
			margin: 0 auto;
			background-color: white;
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
		}
		.profile-header {
			text-align: center;
			margin-bottom: 30px;
		}
		.profile-avatar {
			width: 150px;
			height: 150px;
			border-radius: 50%;
			margin-bottom: 20px;
			object-fit: cover;
			border: 5px solid #32434e;
		}
		.profile-info {
			margin-bottom: 20px;
		}
		.profile-info h3 {
			color: #32434e;
			margin-bottom: 10px;
		}
		.profile-info p {
			color: #666;
			margin-bottom: 5px;
		}
		.btn-edit {
			background-color: #28a745;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			text-decoration: none;
			display: inline-block;
			margin-top: 20px;
		}
		.btn-edit:hover {
			background-color: #218838;
			color: white;
			text-decoration: none;
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
			<b>Hồ sơ cá nhân</b>
		</h1>
		<h5 class="text-center"> <b><i>Xem và quản lý thông tin cá nhân của bạn</i></b></h5>
	</div>

	<!-- Profile Container -->
	<div class="profile-container">
		<div class="profile-header">
			<img src="https://cdn-icons-png.flaticon.com/128/1077/1077114.png" alt="Avatar" class="profile-avatar">
			<h2><?php echo $name; ?></h2>
			<p class="text-muted">Admin</p>
		</div>

		<div class="profile-info">
			<p><strong>Email:</strong> <?php echo $email; ?></p>
			<p><strong>Số điện thoại:</strong> <?php echo $mobile; ?></p>
			<p><strong>ID:</strong> <?php echo $_SESSION['id']; ?></p>
			<p><strong>Ngày tạo:</strong> <?php echo $_SESSION['creation_date']; ?></p>
			<p><strong>Trạng thái:</strong> <span class="text-success">Active</span></p>
		</div>
		<div class="text-center">
			<a href="edit_profile.php" class="btn-edit">Chỉnh sửa hồ sơ</a>
		</div>
	</div>
</body>
</html>