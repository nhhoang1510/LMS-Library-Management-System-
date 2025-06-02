<!DOCTYPE html>
<html>
<head>
	<title>LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
	<script type="text/javascript" src="LMS/bootstrap-4.4.1/js/jquery_latest.js"></script>
	<script type="text/javascript" src="LMS/bootstrap-4.4.1/js/bootstrap.min.js"></script>
	<style type="text/css">
		body, html {
			height: 100%;
			margin: 0;
			padding: 0;
			background-color: whitesmoke;
		}
		.full-height {
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#main_content {
			padding: 40px;
			background-color: white;
			border-radius: 10px;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
			width: 100%;
			max-width: 500px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> 
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">Phần mềm quản lý thư viện (LMS)</a>
			</div>
	
		    <ul class="navbar-nav ml-auto">
		      <li class="nav-item">
		        <a class="nav-link" href="admin/index.php">Đăng nhập Admin</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="signup.php"></span>Đăng ký</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="index.php">Đăng nhập</a>
		      </li>
		    </ul>
		</div>
	</nav><br>
	<div class="full-height">
		<div id="main_content">
			<center><h3>Đăng ký </h3></center>
			<form action="register.php" method="post">
				<div class="form-group">
					<label for="name">Họ và tên:</label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="email">Email:</label>
					<input type="email" name="email" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="password">Mật khẩu:</label>
					<input type="password" name="password" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="mobile">Số điện thoại:</label>
					<input type="text" name="mobile" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="address">Địa chỉ:</label>
					<textarea name="address" class="form-control" required></textarea>
				</div>
				<button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
			</form>
		</div>
	</div>
</body>
</html>
