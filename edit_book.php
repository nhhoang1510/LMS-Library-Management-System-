<?php
	session_start();
	
	#kết nối database
	$connection = mysqli_connect("localhost","root","","lms");
	
	// Kiểm tra kết nối
	if (!$connection) {
		die("Kết nối thất bại: " . mysqli_connect_error());
	}
	
	// Khởi tạo biến
	$book_id = "";
	$book_name = "";
	$book_author = "";
	$book_quantity = "";
	$error_message = "";
	$success_message = "";
	
	// Lấy thông tin sách từ database
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$book_id = mysqli_real_escape_string($connection, $_GET['id']);
		$query = "SELECT * FROM books WHERE book_id = '$book_id'";
		$query_run = mysqli_query($connection, $query);
		
		if(mysqli_num_rows($query_run) > 0) {
			$row = mysqli_fetch_assoc($query_run);
			$book_name = $row['book_name'];
			$book_author = $row['book_author'];
			$book_quantity = $row['book_quantity'];
		} else {
			$error_message = "Không tìm thấy sách!";
		}
	} else {
		header("location:manage_book.php");
		exit();
	}
	
	if(isset($_POST['update'])) {
		$book_name = mysqli_real_escape_string($connection, $_POST['book_name']);
		$book_author = mysqli_real_escape_string($connection, $_POST['book_author']);
		$book_quantity = mysqli_real_escape_string($connection, $_POST['book_quantity']);
		
		if(empty($book_name) || empty($book_author) || empty($book_quantity)) {
			$error_message = "Vui lòng điền đầy đủ thông tin!";
		} elseif(!is_numeric($book_quantity) || $book_quantity < 0) {
			$error_message = "Số lượng phải là số không âm!";
		} else {
			$update_query = "UPDATE books SET 
							book_name = '$book_name',
							book_author = '$book_author',
							book_quantity = '$book_quantity'
							WHERE book_id = '$book_id'";
			
			if(mysqli_query($connection, $update_query)) {
				$success_message = "Cập nhật sách thành công!";
				$query = "SELECT * FROM books WHERE book_id = '$book_id'";
				$query_run = mysqli_query($connection, $query);
				$row = mysqli_fetch_assoc($query_run);
				$book_name = $row['book_name'];
				$book_author = $row['book_author'];
				$book_quantity = $row['book_quantity'];
			} else {
				$error_message = "Có lỗi xảy ra khi cập nhật: " . mysqli_error($connection);
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chỉnh Sửa Sách - LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  	
  	<style>
  		body {
  			background-color: #f8f9fa;
  		}
  		.main-content {
  			margin-top: 20px;
  		}
  		.form-container {
  			background: white;
  			padding: 30px;
  			border-radius: 8px;
  			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  		}
  		.btn-back {
  			margin-bottom: 20px;
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
	
	<div class="container main-content">
		<a href="manage_book.php" class="btn btn-secondary btn-back">
			<i class="fas fa-arrow-left"></i> Quay lại danh sách
		</a>
		
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="form-container">
					<?php if(!empty($error_message)): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
							<button type="button" class="close" data-dismiss="alert">
								<span>&times;</span>
							</button>
						</div>
					<?php endif; ?>
					
					<?php if(!empty($success_message)): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
							<button type="button" class="close" data-dismiss="alert">
								<span>&times;</span>
							</button>
						</div>
					<?php endif; ?>
					
					<?php if(!empty($book_id)): ?>
					<form action="" method="post">
						
						<div class="form-group">
							<label for="book_name"><i class="fas fa-book"></i> Tên Sách: <span class="text-danger">*</span></label>
							<input type="text" name="book_name" class="form-control" 
								   value="<?php echo htmlspecialchars($book_name); ?>" 
								   placeholder="Nhập tên sách..." required>
						</div>
						
						<div class="form-group">
							<label for="book_author"><i class="fas fa-user"></i> Tác Giả: <span class="text-danger">*</span></label>
							<input type="text" name="book_author" class="form-control" 
								   value="<?php echo htmlspecialchars($book_author); ?>" 
								   placeholder="Nhập tên tác giả..." required>
						</div>
						
						<div class="form-group">
							<label for="book_quantity"><i class="fas fa-boxes"></i> Số Lượng: <span class="text-danger">*</span></label>
							<input type="number" name="book_quantity" class="form-control" 
								   value="<?php echo htmlspecialchars($book_quantity); ?>" 
								   min="0" placeholder="Nhập số lượng..." required>
						</div>
						
						<div class="form-group text-center">
							<button type="submit" name="update" class="btn btn-primary btn-lg">
								<i class="fas fa-save"></i> Cập Nhật Sách
							</button>
							<a href="manage_book.php" class="btn btn-secondary btn-lg ml-2">
								<i class="fas fa-times"></i> Hủy
							</a>
						</div>
					</form>
					<?php else: ?>
						<div class="text-center">
							<i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
							<h5>Không tìm thấy sách!</h5>
							<a href="manage_book.php" class="btn btn-primary">Quay lại danh sách</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>