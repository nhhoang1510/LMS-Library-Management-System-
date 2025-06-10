<?php
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$name = "";
	$email = "";
	$mobile = "";
	$address = "";
	$query = "select * from users where email = '$_SESSION[email]'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$name = $row['name'];
		$email = $row['email'];
		$mobile = $row['mobile'];
		$address = $row['address'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Hồ sơ của tôi - LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
	<script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
	<!-- Backup CDN nếu local không hoạt động -->
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  	<style>
  		.profile-card {
  			background: #f8f9fa;
  			border: 1px solid #dee2e6;
  			border-radius: 8px;
  			padding: 2rem;
  			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  		}
  		.profile-header {
  			text-align: center;
  			margin-bottom: 2rem;
  		}
  		.profile-avatar {
  			width: 100px;
  			height: 100px;
  			background: #007bff;
  			border-radius: 50%;
  			display: flex;
  			align-items: center;
  			justify-content: center;
  			margin: 0 auto 1rem;
  			color: white;
  			font-size: 2.5rem;
  			font-weight: bold;
  		}
  		.profile-form .form-group {
  			margin-bottom: 1.5rem;
  		}
  		.profile-form label {
  			font-weight: 600;
  			color: #495057;
  			margin-bottom: 0.5rem;
  		}
  		.profile-form .form-control {
  			background-color: #e9ecef;
  			border: 1px solid #ced4da;
  			padding: 0.75rem;
  			font-size: 1rem;
  		}
  		.action-buttons {
  			text-align: center;
  			margin-top: 2rem;
  		}
  		.action-buttons .btn {
  			margin: 0 0.5rem;
  			padding: 0.6rem 1.5rem;
  		}
  	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="user_dashboard.php">LMS</a>
			</div>
		    <ul class="nav navbar-nav navbar-right">
		      <li class="nav-item dropdown">
	        	<a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Quản lý sách </a>
	        	<div class="dropdown-menu" aria-labelledby="bookDropdown">
	        		<a class="dropdown-item" href="borrow_book.php">Mượn sách</a>
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="return_book.php">Trả sách</a>
	        	</div>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="logout.php">Đăng xuất</a>
		      </li>
		    </ul>
		</div>
	</nav><br>

	<div class="container">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-header bg-dark text-white">
						<h5 class="mb-0">Thông tin cá nhân</h5>
					</div>
					<div class="card-body">
						<div class="profile-card">
							<div class="profile-header">
								<div class="profile-avatar">
									<?php echo strtoupper(substr($name, 0, 1)); ?>
								</div>
								<h5 class="mb-0"><?php echo htmlspecialchars($name); ?></h5>
								<p class="text-muted">Thành viên thư viện</p>
							</div>
							
							<form class="profile-form">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="name"><i class="fas fa-user"></i> Họ và tên:</label>
											<input type="text" id="name" class="form-control" value="<?php echo htmlspecialchars($name);?>" disabled>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="email"><i class="fas fa-envelope"></i> Email:</label>
											<input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email);?>" disabled>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="mobile"><i class="fas fa-phone"></i> Số điện thoại:</label>
											<input type="text" id="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile);?>" disabled>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="address"><i class="fas fa-map-marker-alt"></i> Địa chỉ:</label>
											<input type="text" id="address" class="form-control" value="<?php echo htmlspecialchars($address);?>" disabled>
										</div>
									</div>
								</div>
								
								<div class="action-buttons">
									<a href="edit_profile.php" class="btn btn-primary">
										<i class="fas fa-edit"></i> Chỉnh sửa hồ sơ
									</a>
									<a href="change_password.php" class="btn btn-warning">
										<i class="fas fa-key"></i> Đổi mật khẩu
									</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>