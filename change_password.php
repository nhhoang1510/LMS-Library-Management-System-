<?php
	session_start();
	$email = "";
	$name = "";

	// Xử lý khi form được submit
	if (isset($_POST['update'])) {
		$connection = mysqli_connect("localhost", "root", "");
		$db = mysqli_select_db($connection, "lms");
		$password = "";

		// Lấy mật khẩu hiện tại từ database
		$query = "SELECT * FROM users WHERE email = '$_SESSION[email]'";
		$query_run = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_assoc($query_run)) {
			$password = $row['password'];
			$name = $row['name'];
		}

		// Kiểm tra mật khẩu cũ
		if ($password == $_POST['old_password']) {
			$query = "UPDATE users SET password = '$_POST[new_password]' WHERE email = '$_SESSION[email]'";
			mysqli_query($connection, $query);
			?>
			<script type="text/javascript">
				alert("Cập nhật mật khẩu thành công!");
				window.location.href = "user_dashboard.php";
			</script>
			<?php
		} else {
			?>
			<script type="text/javascript">
				alert("Sai mật khẩu");
			</script>
			<?php
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Đổi mật khẩu LMS</title>
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
		.logout {
			margin-top: 50px;
			width: 100%;
			max-width: 480px;
			background-color: #fffdfd;
			padding: 60px;
			border-radius: 20px;
		}
		.header {
			background-color: #32434e;
			color: white;
			font-size: 25px;
			font-weight: bold;
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 15px 30px;
		}
		.nav-links {
			display: flex;
			gap: 10px;
			align-items: center;
		}
		.nav-links a {
			margin: 0 10px;
			color: rgb(11, 11, 11);
			text-decoration: none;
			font-size: 18px;
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

	<div class="d-flex justify-content-center">
		<div class="logout card shadow">
			<h3 class="text-center mb-1">
				<img src="https://cdn-icons-png.flaticon.com/128/12305/12305885.png" width="40">
				<b>Đổi mật khẩu</b>
			</h3>

			<div class="profile-avatar">
				<?php echo $name ? strtoupper(substr($name, 0, 1)) : 'A'; ?>
			</div>
			<h5 class="mb-0 text-center"><?php echo htmlspecialchars($name); ?></h5>
			<p class="text-muted text-center">User</p>
							
			<form action="update_password.php" method="post" class="profile-form">
				<div class="form-group">
					<label for="old_password">
						<img src="https://cdn-icons-png.flaticon.com/128/3064/3064155.png" width="20"> Mật khẩu hiện tại:
					</label>
					<input type="password" id="old_password" name="old_password" class="form-control" placeholder="Nhập mật khẩu hiện tại*" required>
				</div>
				<div class="form-group">
					<label for="new_password">
						<img src="https://cdn-icons-png.flaticon.com/128/807/807292.png" width="20"> Mật khẩu mới:
					</label>
					<input type="password" id="new_password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới*" required>
				</div>
				<div class="d-flex justify-content-center mt-4 gap-2">
					<button type="submit" name="update" class="btn btn-info mr-2"> Đổi mật khẩu</button>
					<a href="user_dashboard.php" class="btn btn-secondary">Hủy bỏ</a>
				</div>
			</form>
		</div>
	</div>
	<div style="height: 100px;"></div>
</body>
</html>
