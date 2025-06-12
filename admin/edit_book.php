<?php
	require("functions.php");
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");

	$original_book_name = isset($_GET['bn']) ? $_GET['bn'] : '';
	$book_name = "";
	$author = "";
	$category = "";
	$quantity = ""; // Use 'quantity' instead of 'price' for clarity in PHP variables.
	$error_message = "";
	$success_message = "";

	// Fetch existing book details
	if (!empty($original_book_name)) {
		$original_book_name_escaped = mysqli_real_escape_string($connection, $original_book_name);
		$query = "SELECT * FROM books WHERE book_name = '$original_book_name_escaped'";
		$query_run = mysqli_query($connection, $query);

		if (mysqli_num_rows($query_run) > 0) {
			$row = mysqli_fetch_assoc($query_run);
			$book_name = $row['book_name'];
			$author = $row['book_author'];
			$category = $row['book_category'];
			$quantity = $row['book_no']; // Assuming 'book_no' is the column for quantity
		} else {
			$error_message = "Không tìm thấy sách!";
		}
	} else {
		header("Location: manage_book.php");
		exit();
	}

	// Handle form submission for update
	if (isset($_POST['update_book'])) {
		$new_book_name = mysqli_real_escape_string($connection, $_POST['book_name']);
		$new_author = mysqli_real_escape_string($connection, $_POST['book_author']);
		$new_category = mysqli_real_escape_string($connection, $_POST['book_category']);
		$new_quantity = mysqli_real_escape_string($connection, $_POST['book_price']); // User's form uses book_price for quantity

		if (empty($new_book_name) || empty($new_author) || empty($new_category) || !is_numeric($new_quantity) || $new_quantity < 0) {
			$error_message = "Vui lòng điền đầy đủ thông tin và số lượng phải là số không âm!";
		} else {
			$update_query = "UPDATE books SET
								book_name = '$new_book_name',
								book_author = '$new_author',
								book_category = '$new_category',
								book_no = '$new_quantity'
							WHERE book_name = '$original_book_name_escaped'"; // Use original book name for WHERE clause

			if (mysqli_query($connection, $update_query)) {
				$_SESSION['success_message'] = "Cập nhật sách thành công!";
				header("Location: manage_book.php");
				exit();
			} else {
				$_SESSION['error_message'] = "Có lỗi xảy ra khi cập nhật: " . mysqli_error($connection);
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chỉnh sửa sách - LMS</title>
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
		.edit-book-container {
			max-width: 600px;
			margin: 0 auto;
			background-color: white;
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
		}
		.form-group {
			margin-bottom: 20px;
		}
		.form-group label {
			color: #32434e;
			font-weight: 500;
			margin-bottom: 5px;
		}
		.form-control {
			border: 1px solid #ddd;
			border-radius: 5px;
			padding: 10px;
			font-size: 16px;
		}
		.form-control:focus {
			border-color: #32434e;
			box-shadow: 0 0 0 0.2rem rgba(50, 67, 78, 0.25);
		}
		.btn-save {
			background-color: #28a745;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			font-size: 16px;
			cursor: pointer;
		}
		.btn-save:hover {
			background-color: #218838;
		}
		.btn-cancel {
			background-color: #dc3545;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			font-size: 16px;
			text-decoration: none;
			margin-left: 10px;
		}
		.btn-cancel:hover {
			background-color: #c82333;
			color: white;
			text-decoration: none;
		}
		.alert {
			border-radius: 5px;
			margin-bottom: 20px;
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
			<b>Chỉnh sửa sách</b>
		</h1>
		<h5 class="text-center"> <b><i>Cập nhật thông tin chi tiết của sách</i></b></h5>
	</div>

	<!-- Edit Book Container -->
	<div class="edit-book-container">
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

		<form action="" method="post">
			<div class="form-group">
				<label for="book_name">Tên sách:</label>
				<input type="text" name="book_name" class="form-control" value="<?php echo $book_name; ?>" required>
			</div>
			<div class="form-group">
				<label for="book_author">Tác giả:</label>
				<input type="text" name="book_author" class="form-control" value="<?php echo $author; ?>" required>
			</div>	
			<div class="form-group">
				<label for="book_category">Thể loại:</label>
				<select class="form-control" name="book_category" required>
					<?php 
						$cat_query = "select cat_id, cat_name from category";
						$cat_query_run = mysqli_query($connection,$cat_query);
						while($cat_row = mysqli_fetch_assoc($cat_query_run)){
							$selected = ($cat_row['cat_id'] == $category) ? 'selected' : '';
							echo "<option value='" . $cat_row['cat_id'] . "' $selected>" . $cat_row['cat_name'] . "</option>";
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<label for="book_price">Giá:</label>
				<input type="text" name="book_price" class="form-control" value="<?php echo $quantity; ?>" required>
			</div>
			<div class="text-center">
				<button type="submit" name="update_book" class="btn-save">Cập nhật sách</button>
				<a href="manage_book.php" class="btn-cancel">Hủy</a>
			</div>
		</form>
	</div>
</body>
</html>