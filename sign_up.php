<!DOCTYPE html>
<html>
<head>
	<title>LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="bootstrap-4.4.1/js/juqery_latest.js"></script>
  	<script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<style type="text/css">
	#main_content{
		padding: 50px;
		background-color: whitesmoke;
	}
	#side_bar{
		background-color: whitesmoke;
		padding: 50px;
		width: 300px;
		height: 450px;
	}
</style>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Phần mềm quản lý thư viện (LMS)</a>
			</div>
	
		    <ul class="nav navbar-nav navbar-right">
		      <li class="nav-item">
		        <a class="nav-link" href="index.php">Đăng nhập Admin</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="#"></span>Đăng ký</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="index.php">Đăng nhập</a>
		      </li>
		    </ul>
		</div>
	</nav><br>
	<span><marquee>Chúc mọi người một ngày vui vẻ!!</marquee></span><br><br>
	<div class="row">
		<div class="col-md-4" id="side_bar">
			<h5>Thời gian hoạt động</h5>
			<ul>
				<li>Mở cửa: 8:00 AM</li>
				<li>Đóng cửa: 8:00 PM</li>
				<li>Hoạt động mọi ngày trong tuần, trừ Chủ Nhật</li>
			</ul>
			<h5>What We provide ?</h5>
			<ul>
				<li>Full furniture</li>
				<li>Free Wi-fi</li>
			</ul>
		</div>
		<div class="col-md-8" id="main_content">
			<center><h3><u>Đăng ký bạn đọc</u></h3></center>
			<form action="register.php" method="post">
				<div class="form-group">
					<label for="name">Họ và tên: </label>
					<input type="text" name="name" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="email">Email :</label>
					<input type="text" name="email" class="form-control" required>
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
				<button type="submit" class="btn btn-primary">Đăng ký</button>	
			</form>
		</div>
	</div>
</body>
</html>