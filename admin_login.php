<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>LMS | Admin Login</title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
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
			align-items: center;
			justify-content: center;
		}
		#main_content {
			padding: 40px;
			background-color: white;
			border-radius: 10px;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
			width: 100%;
			max-width: 450px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="../admin_login.php">Phần mềm quản lý thư viện (LMS)</a>
		<div class="collapse navbar-collapse">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="../index.php">Đăng nhập người dùng</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../signup.php">Đăng ký</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="full-height">
		<div id="main_content">
			<center><h3>Đăng nhập cho admin</h3></center>
			<form action="" method="post">
				<div class="form-group">
					<label for="admin_email">Email</label>
					<input type="text" name="admin_email" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="admin_password">Mật khẩu</label>
					<input type="admin_password" name="admin_password" class="form-control" required>
				</div>
				<button type="submit" name="login" class="btn btn-primary btn-block">Đăng nhập</button>
			</form>
			<?php 
				if(isset($_POST['login'])){
					$connection = mysqli_connect("localhost","root","");
					$db = mysqli_select_db($connection,"lms");
					$query = "select * from admins where admin_email = '$_POST[admin_email]'";
					$query_run = mysqli_query($connection,$query);
					$found = false;
					while ($row = mysqli_fetch_assoc($query_run)) {
						if($row['admin_email'] == $_POST['admin_email']){
							if($row['admin_password'] == $_POST['admin_password']){
								$_SESSION['admin_name'] =  $row['admin_name'];
								$_SESSION['admin_email'] =  $row['admin_email'];
								header("Location: admin_dashboard.php");
								exit();
							}
							else{
								$found = true;
								break;
							}
						}
					}
					if ($found || mysqli_num_rows($query_run) == 0) {
						echo '<br><center><span class="alert-danger p-2 d-inline-block">Email hoặc mật khẩu không chính xác!</span></center>';
					}
				}
			?>
		</div>
	</div>
</body>
</html>
