<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Đăng nhập Admin LMS</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">

	<style>
		body {
			font-family: 'Quicksand', sans-serif;
			background-color: #f2f2f2;
		}
		.login {
			margin-top: 90px;
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
		.nav-links a {
			margin: 0 10px;
			color: white;
			text-decoration: none;
			font-size: 18px;
		}
		.nav-links {
			display: flex;
			gap: 10px;
			align-items: center;
		} 
		.custom-button-login-header {
			padding: 8px 16px;
			background-color: #377cb0; 
			color: white;              
			border: none;
			border-radius: 10px;
			cursor: pointer;
			font-size: 18px;
		}
		.custom-button-login-header:hover {
			background-color: #93bee4;
			color: white;
		}
		/* Nút đăng ký ở header */
		.custom-button-header {
			padding: 8px 16px;
			background-color: #ffffff; 
			color: rgb(3, 0, 8);              
			border: none;
			border-radius: 10px;
			cursor: pointer;
			font-size: 18px;
		}
		.custom-button-header:hover {
			background-color: #96b0ff; 
			color: white;
		}
		.custom-button {
			padding: 8px 16px;
			background-color: #377cb0; 
			color: white;              
			border: none;
			border-radius: 10px;
			cursor: pointer;
			font-size: 16px;
		}
		.custom-button:hover {
			background-color: #a562e9; 
		}
		.nav-links a:hover {
			text-decoration: underline;
		}
	</style>
</head>
<body>
	<!-- Header -->
	<div class="header">
		<div class="logo">
			<a href="../index.php" style="color: white">
				Hệ thống LMS  
				<img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
			</a> 
		</div>
		<div class="nav-links">
			<a href="../index.php"><button class="custom-button-login-header"><b>Đăng nhập</b></button></a>
			<a href="../signup.php"><button class="custom-button-header"><b>Đăng ký</b></button></a>
			<a href="admin_login.php"><b>Admin</b></a>
		</div>
	</div>

	<!-- Đăng nhập admin -->
	<div class="d-flex justify-content-center">
		<div class="login card shadow">
			<h3 class="text-center mb-4">
				<img src="https://cdn-icons-png.flaticon.com/128/7542/7542114.png" width="40">
				<b>Đăng nhập Admin</b>
			</h3>
			<form method="POST" action="">
				<div class="form-group">
					<label for="email">Email:</label>
					<input type="email" name="email" class="form-control" id="email" required placeholder="Nhập email*">
				</div>
				<div class="form-group">
					<label for="password">Mật khẩu:</label>
					<input type="password" name="password" class="form-control" id="password" required placeholder="Nhập mật khẩu*">
				</div>
				<div style="text-align: center;">
					<button class="custom-button" name="login"><b>Đăng nhập</b></button>
				</div>
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
								echo "<div class='alert alert-danger text-center mt-3'>Email hoặc mật khẩu không đúng</div>";
							}
						}
					}
				}
			?>
		</div>
	</div>
</body>
</html>
