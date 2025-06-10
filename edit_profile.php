<?php
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	if(isset($_POST['update'])) {
		$name = mysqli_real_escape_string($connection, $_POST['admin_name']);
		$email = mysqli_real_escape_string($connection, $_POST['admin_email']);
		$mobile = mysqli_real_escape_string($connection, $_POST['admin_mobile']);
		$current_email = $_SESSION['admin_email'];
		
		$query = "update admins set admin_name = '$name', admin_email = '$email', admin_mobile = '$mobile' WHERE admin_email = '$current_email'";
		$query_run = mysqli_query($connection, $query);
		
		if($query_run) {

			$_SESSION['admin_email'] = $email;
			echo "<script>
				alert('Cập nhật thành công');
				window.location.href = 'admin_dashboard.php';
			</script>";
			exit();
		} else {
			$error_message = "Có lỗi xảy ra khi cập nhật thông tin!";
		}
	}
	
	$name = "";
	$email = "";
	$mobile = "";
	$query = "select * from admins where admin_email = '{$_SESSION['email']}'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$name = $row['admin_name'];
		$email = $row['admin_email'];
		$mobile = $row['admin_mobile'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chỉnh sửa hồ sơ - LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
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
  		.alert {
  			margin-bottom: 1.5rem;
  		}
  	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="admin_dashboard.php">
				<i class="fas fa-book-open"></i> LMS
			</a>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-cog"></i> Quản lý sách 
						</a>
						<div class="dropdown-menu" aria-labelledby="bookDropdown">
							<a class="dropdown-item" href="view_issued_book.php">
								<i class="fas fa-history"></i> Lịch sử mượn/trả
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="edit_book.php">
								<i class="fas fa-edit"></i> Chỉnh sửa
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="manage_book.php">
								<i class="fas fa-cog"></i> Kho sách
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="add_book.php">
								<i class="fas fa-plus"></i> Thêm sách
							</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-user"></i> Hồ sơ
						</a>
						<div class="dropdown-menu" aria-labelledby="profileDropdown">
							<a class="dropdown-item" href="view_profile.php">
								<i class="fas fa-eye"></i> Xem hồ sơ
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="edit_profile.php">
								<i class="fas fa-edit"></i> Chỉnh sửa hồ sơ
							</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="change_password.php">
								<i class="fas fa-key"></i> Đổi mật khẩu
							</a>
							
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="logout.php">
							<i class="fas fa-sign-out-alt"></i> Đăng xuất
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav><br>

	<div class="container">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">		
				<div class="card">
					<div class="card-header bg-dark text-white">
						<h5 class="mb-0">Chỉnh sửa thông tin cá nhân</h5>
					</div>
					<div class="card-body">
						<div class="profile-card">
							<div class="profile-header">
								<div class="profile-avatar">
									<?php echo strtoupper(substr($name, 0, 1)); ?>
								</div>
								<h5 class="mb-0"><?php echo htmlspecialchars($name); ?></h5>
								<p class="text-muted">Quản trị viên</p>
							</div>
							
							<?php if(isset($error_message)): ?>
								<div class="alert alert-danger" role="alert">
									<?php echo $error_message; ?>
								</div>
							<?php endif; ?>
							
							<form method="post" class="profile-form">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="name"><i class="fas fa-user"></i> Họ và tên:</label>
											<input type="text" id="name" name="admin_name" class="form-control" 
												   value="<?php echo htmlspecialchars($name); ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="admin_email"><i class="fas fa-envelope"></i> Email:</label>
											<input type="email" id="admin_email" name="admin_email" class="form-control" 
												   value="<?php echo htmlspecialchars($email); ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="admin_mobile"><i class="fas fa-phone"></i> Số điện thoại:</label>
											<input type="text" id="admin_mobile" name="admin_mobile" class="form-control" 
												   value="<?php echo htmlspecialchars($mobile); ?>" required>
										</div>
									</div>
								</div>
								
								<div class="action-buttons">
									<button type="submit" name="update" class="btn btn-primary">
										<i class="fas fa-save"></i> Cập nhật thông tin
									</button>
									<a href="view_profile.php" class="btn btn-secondary">
										<i class="fas fa-times"></i> Hủy bỏ
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
		<!-- jQuery first -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
	<!-- Popper.js -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<!-- Bootstrap 4 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

	<script>
		// Thêm hiệu ứng hover cho avatar
		document.querySelector('.profile-avatar').addEventListener('mouseenter', function() {
			this.style.transform = 'scale(1.05)';
			this.style.transition = 'transform 0.3s ease';
		});
		
		document.querySelector('.profile-avatar').addEventListener('mouseleave', function() {
			this.style.transform = 'scale(1)';
		});

		// Kiểm tra và khởi tạo dropdown
		$(document).ready(function() {
			console.log('jQuery version:', $.fn.jquery);
			console.log('Bootstrap loaded:', typeof $.fn.dropdown !== 'undefined');
			
			// Loại bỏ tất cả event listeners cũ
			$('.dropdown-toggle').off('click');
			
			// Sử dụng Bootstrap dropdown mặc định
			if (typeof $.fn.dropdown !== 'undefined') {
				$('.dropdown-toggle').dropdown();
				console.log('Bootstrap dropdown initialized');
			} else {
				// Fallback: Tự tạo dropdown functionality
				$('.dropdown-toggle').on('click.customDropdown', function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					var $parent = $(this).parent();
					var isOpen = $parent.hasClass('show');
					
					// Đóng tất cả dropdown khác
					$('.dropdown').removeClass('show');
					$('.dropdown-toggle').attr('aria-expanded', 'false');
					
					// Toggle dropdown hiện tại
					if (!isOpen) {
						$parent.addClass('show');
						$(this).attr('aria-expanded', 'true');
					}
				});
				
				// Đóng dropdown khi click ra ngoài
				$(document).on('click.customDropdown', function(e) {
					if (!$(e.target).closest('.dropdown').length) {
						$('.dropdown').removeClass('show');
						$('.dropdown-toggle').attr('aria-expanded', 'false');
					}
				});
				
				console.log('Custom dropdown functionality added');
			}
		});
	</script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>