<?php
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$name = "";
	$email = "";
	$password = "";
	$mobile = "";
	$address = "";
	
	// Thêm chức năng tìm kiếm
	$query_string = isset($_GET['search']) ? trim($_GET['search']) : '';
	if($query_string != '') {
		$query_string_escaped = mysqli_real_escape_string($connection, $query_string);
		$query = "SELECT * FROM users WHERE (name LIKE '%$query_string_escaped%' OR email LIKE '%$query_string_escaped%' OR mobile LIKE '%$query_string_escaped%') ORDER BY name ASC";
	} else {
		$query = "SELECT * FROM users ORDER BY name ASC";
	}
	
	// Tính tổng số bạn đọc
	$total_users_query = "SELECT COUNT(*) as total_users FROM users";
	$total_result = mysqli_query($connection, $total_users_query);
	$total_data = mysqli_fetch_assoc($total_result);
	$total_users = $total_data['total_users'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Danh sách người dùng - LMS</title>
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
		.table-container {
			background-color: white;
			border-radius: 20px;
			padding: 20px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
			margin: 20px;
		}
		.table th {
			background-color: #32434e;
			color: white;
			font-weight: 500;
			border: none;
		}
		.table td {
			vertical-align: middle;
		}
		.btn-action {
			padding: 5px 10px;
			border-radius: 5px;
			color: white;
			border: none;
			margin: 0 2px;
		}
		.btn-edit {
			background-color: #28a745;
		}
		.btn-delete {
			background-color: #dc3545;
		}
		.btn-edit:hover {
			background-color: #218838;
			color: white;
		}
		.btn-delete:hover {
			background-color: #c82333;
			color: white;
		}
		.search-form {
			max-width: 700px;
			margin: 0 auto 20px;
			background-color: white;
			padding: 20px;
			border-radius: 20px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
		}
		.search-form .form-control {
			border-radius: 5px;
			height: 38px;
		}
		.search-form .btn {
			border-radius: 5px;
			height: 38px;
			background-color: #32434e;
			color: white;
		}
		.search-form .btn:hover {
			background-color: #93bee4;
			color: white;
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
			<b>Danh sách người dùng</b>
		</h1>
		<h5 class="text-center"> <b><i>Quản lý thông tin người dùng trong hệ thống</i></b></h5>
	</div>

	<!-- Search Form -->
	<div class="search-form">
		<form action="" method="GET">
			<div class="input-group">
				<input type="text" name="search" class="form-control" placeholder="Tìm kiếm người dùng..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
				<div class="input-group-append">
					<button class="btn" type="submit">Tìm kiếm</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Table Container -->
	<div class="table-container">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Tên</th>
						<th>Email</th>
						<th>Số điện thoại</th>
						<th>Thao tác</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$connection = mysqli_connect("localhost", "root", "", "lms");
					$query_display = "SELECT * FROM users WHERE role = 'user'";
					
					if(isset($_GET['search']) && !empty($_GET['search'])) {
						$search_term = mysqli_real_escape_string($connection, $_GET['search']);
						$query_display .= " AND (name LIKE '%$search_term%' OR email LIKE '%$search_term%' OR mobile LIKE '%$search_term%')";
					}

					$query_display .= " ORDER BY name ASC";
					
					$query_run = mysqli_query($connection, $query_display);
					while ($row = mysqli_fetch_assoc($query_run)) {
						?>
						<tr>
							<td><?php echo $row['id']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $row['mobile']; ?></td>
							<td>
								<a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-edit btn-action">Sửa</a>
								<a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-delete btn-action" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>