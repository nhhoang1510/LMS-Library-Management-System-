<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="bootstrap-4.4.1/js/juqery_latest.js"></script>
  	<script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
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
		        <a class="nav-link" href="../logout.php">Đăng xuất</a>
		      </li>
		    </ul>
		</div>
	</nav><br>
		<center><h4>Đổi mật khẩu</h4><br></center>
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<form action="update_password.php" method="post">
					<div class="form-group">
						<label for="password">Mật khẩu hiện tại:</label>
						<input type="password" class="form-control" name="old_password">
					</div>
					<div class="form-group">
						<label for="New Password">Mật khẩu mới:</label>
						<input type="password" name="new_password" class="form-control">
					</div>
					<button type="submit" name="update" class="btn btn-primary">Đổi mật khẩu</button>
				</form>
			</div>
			<div class="col-md-4"></div>
		</div>
</body>
</html>
