<?php
	session_start();
	function get_user_issue_book_count(){
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"lms");
		$user_issue_book_count = 0;
		
		// Check if student_id exists in session
		if(isset($_SESSION['student_id'])) {
			$student_id = mysqli_real_escape_string($connection, $_SESSION['student_id']);
			// Count only currently issued books (not returned)
			$query = "SELECT COUNT(*) as user_issue_book_count FROM issued_books WHERE student_id = '$student_id' AND status != '0'";
			$query_run = mysqli_query($connection,$query);
			
			if($query_run) {
				while ($row = mysqli_fetch_assoc($query_run)){
					$user_issue_book_count = $row['user_issue_book_count'];
				}
			}
		}
		
		mysqli_close($connection);
		return($user_issue_book_count);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="LMS/bootstrap-4.4.1/js/jquery_latest.js"></script>
	<script type="text/javascript" src="LMS/bootstrap-4.4.1/js/bootstrap.min.js"></script>
	<style>
		body {
			background-color: #f8f9fa;
		}
		
		.search-section {
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			margin: 20px 0;
		}
		
		.search-form {
			max-width: 500px;
			margin: 0 auto;
		}
		
		.search-form .form-control {
			border-radius: 5px 0 0 5px;
			border-right: none;
			height: 48px;
		}
		
		.search-form .btn {
			border-radius: 0 5px 5px 0;
			height: 48px;
		}
		
		.dashboard-card {
			transition: transform 0.2s;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			border: none;
			border-radius: 8px;
		}
		
		.dashboard-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0,0,0,0.15);
		}
		
		.card-header {
			background: linear-gradient(45deg, #007bff, #0056b3);
			color: white;
			border-radius: 8px 8px 0 0 !important;
			font-weight: 500;
		}
		
		.btn-success {
			background: linear-gradient(45deg, #28a745, #20c997);
			border: none;
			border-radius: 5px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
			</div>
		    <ul class="nav navbar-nav navbar-right">
		      <li class="nav-item dropdown">
	        	<a class="nav-link dropdown-toggle" data-toggle="dropdown">Hồ sơ của tôi </a>
	        	<div class="dropdown-menu">
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
	</nav>

	<div class="container">
		<div class="search-section text-center">
			<h4 class="mb-4 text-muted">Tìm kiếm sách trong thư viện</h4>
			<form class="search-form" action="search_book.php" method="GET">
				<div class="input-group">
					<input class="form-control" type="search" name="query" placeholder="Tìm sách theo tên hoặc tác giả..." required>
					<div class="input-group-append">
						<button class="btn btn-outline-success" type="submit">Tìm kiếm</button>
					</div>
				</div>
			</form>
		</div>

		<div class="row justify-content-center">
			<div class="col-md-4 mb-4">
				<div class="card dashboard-card">
					<div class="card-header text-center">
						Sách đang mượn
					</div>
					<div class="card-body text-center">
						<p class="card-text text-muted mb-3">Xem danh sách các sách bạn đang mượn</p>
						<a class="btn btn-success" href="view_issued_book.php">Xem thêm</a>
					</div>
				</div>
			</div>
			
			<div class="col-md-4 mb-4">
				<div class="card dashboard-card">
					<div class="card-header text-center">
						Sách chưa trả
					</div>
					<div class="card-body text-center">
						<p class="card-text text-muted mb-3">Kiểm tra và trả sách đã hết hạn</p>
						<a class="btn btn-success" href="return_book.php">Xem thêm</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>