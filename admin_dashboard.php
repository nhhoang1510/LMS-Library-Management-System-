<?php
	require("functions.php");
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Bootstrap 4 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	
	<style>
		body {
			background-color: #f8f9fa;
		}
		
		.admin-section {
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			margin: 20px 0;
		}
		
		.dashboard-card {
			transition: transform 0.2s;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			border: none;
			border-radius: 8px;
			margin-bottom: 20px;
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
			text-align: center;
		}
		
		.card-users .card-header {
			background: linear-gradient(45deg, #28a745, #20c997);
		}
		
		.card-books .card-header {
			background: linear-gradient(45deg, #17a2b8, #138496);
		}
		
		.card-issued .card-header {
			background: linear-gradient(45deg, #ffc107, #e0a800);
		}
		
		.btn-primary {
			background: linear-gradient(45deg, #007bff, #0056b3);
			border: none;
			border-radius: 5px;
			padding: 8px 20px;
		}
		
		.btn-success {
			background: linear-gradient(45deg, #28a745, #20c997);
			border: none;
			border-radius: 5px;
			padding: 8px 20px;
		}
		
		.btn-warning {
			background: linear-gradient(45deg, #ffc107, #e0a800);
			border: none;
			border-radius: 5px;
			padding: 8px 20px;
			color: #212529;
		}
		
		.card-body {
			text-align: center;
			padding: 25px;
		}
		
		.card-text {
			font-size: 1.1em;
			margin-bottom: 20px;
			color: #6c757d;
		}
		
		.stats-number {
			font-size: 2em;
			font-weight: bold;
			color: #007bff;
			display: block;
			margin-bottom: 10px;
		}
		
		.navbar-light {
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		
		.welcome-section {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 40px;
			border-radius: 8px;
			text-align: center;
			margin: 20px 0;
		}
		
		.quick-actions {
			background: white;
			padding: 25px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			margin: 20px 0;
		}
		
		/* Custom dropdown styles để đảm bảo hoạt động */
		.dropdown-menu {
			display: none;
			position: absolute;
			top: 100%;
			left: 0;
			z-index: 1000;
			min-width: 160px;
			padding: 5px 0;
			margin: 2px 0 0;
			background-color: #fff;
			border: 1px solid rgba(0,0,0,.15);
			border-radius: 0.25rem;
			box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
		}
		
		.dropdown.show .dropdown-menu,
		.dropdown-menu.show {
			display: block;
		}
		
		.dropdown-item {
			display: block;
			width: 100%;
			padding: 0.25rem 1.5rem;
			clear: both;
			font-weight: 400;
			color: #212529;
			text-align: inherit;
			text-decoration: none;
			white-space: nowrap;
			background-color: transparent;
			border: 0;
		}
		
		.dropdown-item:hover,
		.dropdown-item:focus {
			color: #16181b;
			text-decoration: none;
			background-color: #f8f9fa;
		}
		
		.dropdown-divider {
			height: 0;
			margin: 0.5rem 0;
			overflow: hidden;
			border-top: 1px solid #e9ecef;
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
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card dashboard-card card-users">
					<div class="card-header">Người dùng đã đăng ký</div>
					<div class="card-body">
						<span class="stats-number"><?php echo get_user_count();?></span>
						<p class="card-text">Tổng số người dùng trong hệ thống</p>
						<a class="btn btn-primary" href="Regusers.php" target="_blank">Xem chi tiết</a>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="card dashboard-card card-books">
					<div class="card-header">Số sách</div>
					<div class="card-body">
						<span class="stats-number"><?php echo get_book_count();?></span>
						<p class="card-text">Tổng đầu sách trong thư viện</p>
						<a class="btn btn-success" href="Regbooks.php" target="_blank">Xem chi tiết</a>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="card dashboard-card card-issued">
					<div class="card-header">Lịch sử mượn trả</div>
					<div class="card-body">
						<span class="stats-number"><?php echo get_issue_book_count();?></span>
						<p class="card-text">Số sách hiện đang được mượn</p>
						<a class="btn btn-warning" href="view_issued_book.php" target="_blank">Xem chi tiết</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery first -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
	<!-- Popper.js -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<!-- Bootstrap 4 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

	<script>
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
</body>
</html>