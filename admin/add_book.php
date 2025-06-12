<?php
	require("functions.php");
	session_start();
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
	
	$success_message = "";
	$error_message = "";

	// Handle book addition
	if (isset($_POST['add_book'])) {
		$book_name = mysqli_real_escape_string($connection, $_POST['book_name']);
		$book_author = mysqli_real_escape_string($connection, $_POST['book_author']);
		$book_category = mysqli_real_escape_string($connection, $_POST['book_category']);
		$book_no = mysqli_real_escape_string($connection, $_POST['book_no']); // Corresponds to 'Số lượng'

		// Basic validation
		if (empty($book_name) || empty($book_author) || empty($book_category) || !is_numeric($book_no) || $book_no < 0) {
			$_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin và số lượng phải là số không âm.";
		} else {
			// Check if book already exists
			$check_query = "SELECT book_id FROM books WHERE book_name = '$book_name'";
			$check_result = mysqli_query($connection, $check_query);

			if (mysqli_num_rows($check_result) > 0) {
				$_SESSION['error_message'] = "Sách này đã tồn tại trong thư viện.";
			} else {
				// Insert new book
				$insert_query = "INSERT INTO books (book_name, book_author, book_category, book_no) VALUES ('$book_name', '$book_author', '$book_category', '$book_no')";
				if (mysqli_query($connection, $insert_query)) {
					$_SESSION['success_message'] = "Thêm sách thành công!";
					header("Location: manage_book.php"); // Redirect to manage books page
					exit();
				} else {
					$_SESSION['error_message'] = "Có lỗi xảy ra khi thêm sách: " . mysqli_error($connection);
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thêm sách mới - LMS</title>
	<!-- Thêm vào phần <head> nếu chưa có Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

	<link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
	
	<style>
		body {
			font-family: 'Quicksand', sans-serif;
			background-color: #f2f2f2;
		}
		.header {
			background-color: #2c3e50;
			padding: 10px 20px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.header .logo a {
			font-size: 24px;
			font-weight: bold;
			text-decoration: none;
		}

		.nav-links .nav-link {
			color: white !important;
			margin: 0 10px;
		}

		.dropdown-menu {
			min-width: 200px;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0,0,0,0.1);
			animation: fadeIn 0.3s ease-in-out;
		}

		.dropdown-menu a.dropdown-item:hover {
			background-color: #ecf0f1;
			color: #2c3e50;
		}

		@keyframes fadeIn {
			from {opacity: 0; transform: translateY(10px);}
			to {opacity: 1; transform: translateY(0);}
		}
		.hello {
			background-image: url('https://images.unsplash.com/photo-1553448540-fe069f7c95bf?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
			background-size: cover 110%;        
			background-position: center 52.5%;
			filter: brightness(1.2);
			padding: 2rem;
			margin-bottom: 30px;
		}
		.form-container {
			background-color: white;
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
			margin: 20px auto;
			max-width: 800px;
		}
		.form-group label {
			font-weight: 500;
			color: #32434e;
		}
		.form-control {
			border-radius: 5px;
			border: 1px solid #ddd;
			padding: 10px;
		}
		.form-control:focus {
			border-color: #93bee4;
			box-shadow: 0 0 0 0.2rem rgba(147, 190, 228, 0.25);
		}
		.btn-submit {
			background-color: #32434e;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			font-weight: 500;
		}
		.btn-submit:hover {
			background-color: #93bee4;
			color: white;
		}
		.btn-cancel {
			background-color: #dc3545;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			font-weight: 500;
			margin-left: 10px;
		}
		.btn-cancel:hover {
			background-color: #c82333;
			color: white;
		}
	</style>
</head>

<body>
	<!-- Header -->
	<div class="header">
		<div class="logo">
			<a href="admin_dashboard.php" style="color: white">
				Hệ thống LMS  
				<img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
			</a> 
		</div>
		<div class="nav-links">
			<ul class="navbar-nav ml-auto flex-row">
				<!-- Quản lý sách -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Quản lý sách
					</a>
					<div class="dropdown-menu" aria-labelledby="bookDropdown">
						<a class="dropdown-item" href="add_book.php">Thêm sách</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="manage_book.php">Quản lý sách</a>
					</div>
				</li>

				<!-- Quản lý người dùng -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Quản lý người dùng
					</a>
					<div class="dropdown-menu" aria-labelledby="userDropdown">
						<a class="dropdown-item" href="Regusers.php">Danh sách người dùng</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="manage_requests.php">Quản lý yêu cầu</a>
					</div>
				</li>

				<!-- Hồ sơ -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Hồ sơ
					</a>
					<div class="dropdown-menu" aria-labelledby="profileDropdown">
						<a class="dropdown-item" href="view_profile.php">Xem hồ sơ</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="edit_profile.php">Chỉnh sửa hồ sơ</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="change_password.php">Đổi mật khẩu</a>
					</div>
				</li>

				<!-- Đăng xuất -->
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Đăng xuất</a>
				</li>
			</ul>
		</div>
	</div>

	<!-- Banner -->
	<div class="hello">
		<h1 class="text-center"> 
			<img src="https://cdn-icons-png.flaticon.com/128/5849/5849203.png" width="50">
			<b>Thêm sách mới</b>
		</h1>
		<h5 class="text-center"> <b><i>Thêm sách mới vào thư viện</i></b></h5>
	</div>

	<!-- Form Container -->
	<div class="form-container">
		<?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
		<form action="" method="POST">
			<div class="form-group">
				<label for="book_name">Tên sách:</label>
				<input type="text" name="book_name" class="form-control" required>
			</div>
			<div class="form-group">
				<label for="book_author">Tác giả:</label>
				<input type="text" name="book_author" class="form-control" required>
			</div>
			<div class="form-group">
				<label for="book_category">Thể loại:</label>
				<select class="form-control" name="book_category" required>
					<?php 
						$cat_query = "select cat_id, cat_name from category";
						$cat_query_run = mysqli_query($connection,$cat_query);
						while($cat_row = mysqli_fetch_assoc($cat_query_run)){
							echo "<option value='" . $cat_row['cat_id'] . "'>" . $cat_row['cat_name'] . "</option>";
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<label for="book_no">Số lượng:</label>
				<input type="number" name="book_no" class="form-control" required>
			</div>
			<div class="text-center">
				<button type="submit" name="add_book" class="btn-submit">Thêm sách</button>
				<a href="manage_book.php" class="btn-cancel">Hủy</a>
			</div>
		</form>
	</div>
</body>
</html>