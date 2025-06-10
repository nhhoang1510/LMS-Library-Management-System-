<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>LMS | Login</title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
	<script type="text/javascript" src="./bootstrap-4.4.1/js/jquery_latest.js"></script>
	<script type="text/javascript" src="./bootstrap-4.4.1/js/bootstrap.min.js"></script>
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
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">LMS</a>
			</div>
		    <ul class="nav navbar-nav navbar-right">
		      <li class="nav-item">
		        <a class="nav-link" href="../admin_login.php">Đăng nhập admin</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="signup.php">Đăng ký</a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="index.php">Đăng nhập</a>
		      </li>
		    </ul>
		</div>
	</nav><br>
		<div class="full-height">
		<div id="main_content">
			<center><h3>Đăng nhập admin</h3></center>
			<form action="" method="POST">
				<div class="form-group">
					<label for="email">Email:</label>
					<input type="email" name="email" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="password">Mật khẩu:</label>
					<input type="password" name="password" class="form-control" required>
				</div>
				<button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>	
			</form>
			<?php 
				if(isset($_POST['login'])){
					$connection = mysqli_connect("localhost","root","");
					$db = mysqli_select_db($connection,"lms");
					$query = "select * from admins where admin_email = '$_POST[email]'";
					$query_run = mysqli_query($connection,$query);
					while ($row = mysqli_fetch_assoc($query_run)) {
						if($row['admin_email'] == $_POST['email']){
							if($row['admin_password'] == $_POST['password']){
								$_SESSION['name'] =  $row['admin_name'];
								$_SESSION['admin_mail'] =  $row['admin_email'];
								header("Location: admin_dashboard.php");
							}
							else{
								?>
								<br><br><center><span class="alert-danger">Email hoặc mật khẩu không đúng </span></center>
								<?php
							}
						}
					}
				}
			?>
		</div>
	</div>
</body>
</html>
