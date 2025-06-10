<?php
	require("functions.php");
	session_start();
	#fetch data from database
	$connection = mysqli_connect("localhost","root","");
	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$db = mysqli_select_db($connection,"lms");
	if (!$db) {
		die("Database selection failed: " . mysqli_error($connection));
	}
	
	$name = "";
	$email = "";
	$mobile = "";
	
	$admin_email = $_SESSION['email'];
	$query = "SELECT admin_name, admin_email, admin_mobile FROM admins WHERE admin_email = ?";
	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, "s", $admin_email);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	
	if ($row = mysqli_fetch_assoc($result)) {
		$name = $row['admin_name'];
		$email = $row['admin_email'];
		$mobile = $row['admin_mobile'];
	}
	mysqli_stmt_close($stmt);
	
	// Initialize message variables
	$success_message = "";
	$error_message = "";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add New Book</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/juqery_latest.js"></script>
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  	<style>
		body {
			background-color: #f8f9fa;
		}
		
		.form-container {
			background: white;
			padding: 40px;
			border-radius: 12px;
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			margin: 30px 0;
		}
		
		.page-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 30px;
			border-radius: 8px;
			text-align: center;
			margin: 20px 0;
		}
		
		.form-group label {
			font-weight: 600;
			color: #495057;
			margin-bottom: 8px;
		}
		
		.form-control {
			border-radius: 8px;
			border: 2px solid #e9ecef;
			padding: 12px 15px;
			font-size: 15px;
			transition: all 0.3s ease;
		}
		
		.form-control:focus {
			border-color: #007bff;
			box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
		}
		
		.btn-primary {
			background: linear-gradient(45deg, #007bff, #0056b3);
			border: none;
			border-radius: 8px;
			padding: 12px 30px;
			font-weight: 600;
			font-size: 16px;
			transition: all 0.3s ease;
		}
		
		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0,123,255,0.3);
		}
		
		.btn-secondary {
			background: linear-gradient(45deg, #6c757d, #545b62);
			border: none;
			border-radius: 8px;
			padding: 12px 30px;
			font-weight: 600;
			margin-right: 10px;
		}
		
		.navbar-light {
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		
		.alert-success {
			border-radius: 8px;
			border: none;
			background: linear-gradient(45deg, #28a745, #20c997);
			color: white;
		}
		
		.alert-danger {
			border-radius: 8px;
			border: none;
			background: linear-gradient(45deg, #dc3545, #c82333);
			color: white;
		}
		
		.input-group-text {
			background: linear-gradient(45deg, #007bff, #0056b3);
			color: white;
			border: none;
		}
		
		.form-row {
			margin-bottom: 20px;
		}
		
		.required {
			color: #dc3545;
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
	</nav>
	<div class="container">
		<div class="page-header">
			<h2>📚 Thêm sách mới </h2>
		</div>

		<?php if(!empty($success_message)): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<strong>Thành công!</strong> <?php echo htmlspecialchars($success_message); ?>
				<button type="button" class="close" data-dismiss="alert">
					<span>&times;</span>
				</button>
			</div>
		<?php endif; ?>

		<?php if(!empty($error_message)): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Lỗi!</strong> <?php echo htmlspecialchars($error_message); ?>
				<button type="button" class="close" data-dismiss="alert">
					<span>&times;</span>
				</button>
			</div>
		<?php endif; ?>

		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-6">
				<div class="form-container">
					<form action="" method="post" id="addBookForm">
						<div class="form-group">
							<label for="book_name">
								<i class="fa fa-book"></i> Tên sách <span class="required">*</span>
							</label>
							<input type="text" 
								   name="book_name" 
								   id="book_name"
								   class="form-control" 
								   placeholder="Nhập tên sách..."
								   value="<?php echo isset($_POST['book_name']) ? htmlspecialchars($_POST['book_name']) : ''; ?>"
								   required>
						</div>

						<div class="form-group">
							<label for="book_author">
								<i class="fa fa-user"></i> Tác giả <span class="required">*</span>
							</label>
							<input type="text" 
								   name="book_author" 
								   id="book_author"
								   class="form-control" 
								   placeholder="Nhập tên tác giả..."
								   value="<?php echo isset($_POST['book_author']) ? htmlspecialchars($_POST['book_author']) : ''; ?>"
								   required>
						</div>

						<div class="form-group">
							<label for="book_quantity">
								<i class="fa fa-sort-numeric-up"></i> Số lượng <span class="required">*</span>
							</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">#</span>
								</div>
								<input type="number" 
									   name="book_quantity" 
									   id="book_quantity"
									   class="form-control" 
									   placeholder="Nhập số lượng sách..."
									   min="1"
									   max="1000"
									   value="<?php echo isset($_POST['book_quantity']) ? (int)$_POST['book_quantity'] : '1'; ?>"
									   required>
							</div>
							<small class="form-text text-muted">Số lượng sách từ 1 đến 1000</small>
						</div>

						<hr style="margin: 30px 0;">

						<div class="form-row">
							<div class="col text-center">
								<a href="admin_dashboard.php" class="btn btn-secondary">
									<i class="fa fa-arrow-left"></i> Quay lại
								</a>
								<button type="submit" name="add_book" class="btn btn-primary">
									<i class="fa fa-plus"></i> Thêm sách
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		// Form validation
		document.getElementById('addBookForm').addEventListener('submit', function(e) {
			var bookName = document.getElementById('book_name').value.trim();
			var bookAuthor = document.getElementById('book_author').value.trim();
			var bookQuantity = document.getElementById('book_quantity').value;

			if (bookName.length < 2) {
				alert('Tên sách phải có ít nhất 2 ký tự!');
				e.preventDefault();
				return false;
			}

			if (bookAuthor.length < 2) {
				alert('Tên tác giả phải có ít nhất 2 ký tự!');
				e.preventDefault();
				return false;
			}

			if (bookQuantity < 1 || bookQuantity > 1000) {
				alert('Số lượng sách phải từ 1 đến 1000!');
				e.preventDefault();
				return false;
			}
		});

		// Auto dismiss alerts
		setTimeout(function() {
			$('.alert').fadeOut('slow');
		}, 5000);
	</script>
	
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

<?php
	if(isset($_POST['add_book']))
	{
		// Validate and sanitize input
		$book_name = mysqli_real_escape_string($connection, trim($_POST['book_name']));
		$book_author = mysqli_real_escape_string($connection, trim($_POST['book_author']));
		$book_quantity = intval($_POST['book_quantity']);
		
		// Validation
		if(empty($book_name) || empty($book_author) || $book_quantity < 1) {
			$error_message = "Vui lòng điền đầy đủ thông tin hợp lệ!";
		} else {
			// Check if book already exists using prepared statement
			$check_query = "SELECT book_id, book_quantity FROM books WHERE book_name = ? AND book_author = ?";
			$check_stmt = mysqli_prepare($connection, $check_query);
			mysqli_stmt_bind_param($check_stmt, "ss", $book_name, $book_author);
			mysqli_stmt_execute($check_stmt);
			$check_result = mysqli_stmt_get_result($check_stmt);
			
			if(mysqli_num_rows($check_result) > 0) {
				// Book exists, update quantity
				$existing_book = mysqli_fetch_assoc($check_result);
				$new_quantity = $existing_book['book_quantity'] + $book_quantity;
				
				$update_query = "UPDATE books SET book_quantity = ? WHERE book_id = ?";
				$update_stmt = mysqli_prepare($connection, $update_query);
				mysqli_stmt_bind_param($update_stmt, "ii", $new_quantity, $existing_book['book_id']);
				
				if(mysqli_stmt_execute($update_stmt)) {
					$success_message = "Sách đã tồn tại! Đã cập nhật số lượng từ {$existing_book['book_quantity']} thành $new_quantity.";
				} else {
					$error_message = "Lỗi khi cập nhật số lượng sách!";
				}
				mysqli_stmt_close($update_stmt);
			} else {
				// New book, insert using prepared statement
				$insert_query = "INSERT INTO books (book_name, book_author, book_quantity) VALUES (?, ?, ?)";
				$insert_stmt = mysqli_prepare($connection, $insert_query);
				mysqli_stmt_bind_param($insert_stmt, "ssi", $book_name, $book_author, $book_quantity);
				
				if(mysqli_stmt_execute($insert_stmt)) {
					$success_message = "Thêm sách '$book_name' thành công với số lượng $book_quantity!";
					// Clear form data
					$_POST = array();
				} else {
					$error_message = "Lỗi khi thêm sách: " . mysqli_error($connection);
				}
				mysqli_stmt_close($insert_stmt);
			}
			mysqli_stmt_close($check_stmt);
		}
	}
	
	mysqli_close($connection);
?>