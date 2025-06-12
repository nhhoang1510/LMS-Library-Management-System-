<?php
	$connection = mysqli_connect("localhost", "root", "");
	$db = mysqli_select_db($connection, "lms");
	$query_string = isset($_GET['query']) ? trim($_GET['query']) : '';
	$query = "SELECT * FROM books WHERE book_name LIKE '%$query_string%' OR book_author LIKE '%$query_string%'";
	$query_run = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Kết quả tìm kiếm</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
	<style>
		body {
			background-color: #f8f9fa;
		}
		
		.container {
			background: white;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			padding: 30px;
			margin-top: 20px;
		}
		
		.search-title {
			color: #495057;
			border-bottom: 2px solid #007bff;
			padding-bottom: 10px;
			margin-bottom: 25px;
		}
		
		.table {
			box-shadow: 0 1px 3px rgba(0,0,0,0.1);
		}
		
		.table thead th {
			background-color: #343a40;
			border: none;
		}
		
		.table tbody tr:hover {
			background-color: #f8f9fa;
		}
		
		.btn {
			border-radius: 5px;
			padding: 8px 20px;
		}
		
		.alert {
			border-radius: 8px;
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
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="search_book.php">Tìm kiếm sách</a>
	        	</div>
		      </li>
		      <li class="nav-item dropdown">
	        	<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hồ sơ của tôi </a>
	        	<div class="dropdown-menu" aria-labelledby="profileDropdown">
	        		<a class="dropdown-item" href="view_profile.php">Xem hồ sơ</a>
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="edit_profile.php">Chỉnh sửa hồ sơ</a>
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="change_password.php">Đổi mật khẩu</a>
	        	</div>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="logout.php">Đăng xuất</a>
		      </li>
		    </ul>
		</div>
	</nav>

	<div class="container">
		<h3 class="search-title">Kết quả tìm kiếm cho: <em><?php echo htmlspecialchars($query_string); ?></em></h3>
		
		<?php if(mysqli_num_rows($query_run) > 0) { ?>
			<table class="table table-bordered table-hover">
				<thead class="thead-dark">
					<tr>
						<th>ID</th>
						<th>Tên sách</th>
						<th>Tác giả</th>
						<th>Số lượng</th>
						<th>Trạng thái</th>
					</tr>
				</thead>
				<tbody>
					<?php while($row = mysqli_fetch_assoc($query_run)) { ?>
						<tr>
							<td><?php echo $row['book_id']; ?></td>
							<td><strong><?php echo $row['book_name']; ?></strong></td>
							<td><?php echo $row['book_author']; ?></td>
							<td>
								<span class="badge badge-<?php echo $row['book_quantity'] > 5 ? 'success' : ($row['book_quantity'] > 0 ? 'warning' : 'danger'); ?>">
									<?php echo $row['book_quantity']; ?>
								</span>
							</td>
							<td>
								<?php if($row['book_quantity'] > 0) { ?>
									<span class="text-success"><strong>Có sẵn</strong></span>
								<?php } else { ?>
									<span class="text-danger"><strong>Hết sách</strong></span>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			
			<div class="d-flex justify-content-between mt-4">
				<a href="user_dashboard.php" class="btn btn-secondary">Quay lại Dashboard</a>
				<a href="borrow_book.php?query=<?php echo urlencode($query_string); ?>" class="btn btn-success">
					Đi đến trang mượn sách
				</a>
			</div>
		<?php } else { ?>
			<div class="alert alert-warning">
				<h5>Không tìm thấy kết quả</h5>
				<p class="mb-0">Không tìm thấy sách nào phù hợp với từ khóa tìm kiếm "<strong><?php echo htmlspecialchars($query_string); ?></strong>"</p>
			</div>
			
			<div class="mt-3">
				<a href="user_dashboard.php" class="btn btn-secondary">Quay lại Dashboard</a>
			</div>
		<?php } ?>
	</div>
</body>
</html>