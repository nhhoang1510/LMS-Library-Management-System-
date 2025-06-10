<?php
	require("functions.php");
	session_start();
	#fetch data from database

	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$name = "";
	$email = "";
	$mobile = "";
	$query = "select * from admins where admin_email = '$_SESSION[email]'";
	$query_run = mysqli_query($connection,$query);
	while ($row = mysqli_fetch_assoc($query_run)){
		$name = $row['admin_name'];
		$email = $row['admin_email'];
		$mobile = $row['admin_mobile'];
	}
	$search = isset($_GET['search']) ? $_GET['search'] : '';
	$search_query = "";
	if (!empty($search)) {
		$search_query = " WHERE book_name LIKE '%$search%' OR book_author LIKE '%$search%'";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Book</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/juqery_latest.js"></script>
  	<script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  	
  	<style>
  		body {
  			background-color: #f8f9fa;
  		}
  		
  		.main-content {
  			margin-top: 20px;
  		}
  		
  		.page-header {
  			background: white;
  			padding: 20px;
  			border-radius: 8px;
  			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  			margin-bottom: 20px;
  		}
  		
  		.search-box {
  			background: white;
  			padding: 20px;
  			border-radius: 8px;
  			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  			margin-bottom: 20px;
  		}
  		
  		.table-container {
  			background: white;
  			border-radius: 8px;
  			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  			overflow: hidden;
  		}
  		
  		.table thead th {
  			background-color: #e9ecef;
  			border-bottom: 2px solid #dee2e6;
  			font-weight: 600;
  		}
  		
  		.table tbody tr:hover {
  			background-color: #f8f9fa;
  		}
  		
  		.btn-action {
  			padding: 5px 10px;
  			margin: 0 2px;
  			border: none;
  			border-radius: 4px;
  			text-decoration: none;
  			font-size: 12px;
  		}
  		
  		.btn-edit {
  			background-color: #ffc107;
  			color: #212529;
  		}
  		
  		.btn-edit:hover {
  			background-color: #e0a800;
  			color: #212529;
  			text-decoration: none;
  		}
  		
  		.btn-delete {
  			background-color: #dc3545;
  			color: white;
  		}
  		
  		.btn-delete:hover {
  			background-color: #c82333;
  			color: white;
  			text-decoration: none;
  		}
  		
  		.search-input {
  			border-radius: 20px;
  		}
  		
  		.search-btn {
  			border-radius: 20px;
  		}
  		
  		.no-results {
  			text-align: center;
  			padding: 40px;
  			color: #6c757d;
  		}
  	</style>
  	
  	<script type="text/javascript">
  		function alertMsg(){
  			alert("Book added successfully...");
  			window.location.href = "admin_dashboard.php";
  		}
  	</script>
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
		<div class="search-box">
			<form method="GET">
				<div class="row justify-content-center">
					<div class="col-md-6">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-search"></i></span>
							</div>
							<input type="text" class="form-control search-input" name="search" 
								   placeholder="Tìm kiếm theo tên sách hoặc tác giả..." 
								   value="<?php echo htmlspecialchars($search); ?>">
							<div class="input-group-append">
								<button type="submit" class="btn btn-primary search-btn">Tìm kiếm</button>
							</div>
						</div>
						<?php if (!empty($search)): ?>
							<div class="text-center mt-2">
								<a href="manage_book.php" class="btn btn-secondary btn-sm">
									<i class="fas fa-times"></i> Xóa bộ lọc
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</form>
		</div>
		<div class="table-container">
			<table class="table table-hover mb-0">
				<thead>
					<tr>
						<th><i class="fas fa-book"></i> Tên sách</th>
						<th><i class="fas fa-user"></i> Tác giả</th>
						<th><i class="fas fa-boxes"></i> Số lượng</th>
						<th><i class="fas fa-cogs"></i> Thao tác</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query = "SELECT * FROM books" . $search_query . " ORDER BY book_name ASC";
						$query_run = mysqli_query($connection, $query);
						
						if (mysqli_num_rows($query_run) > 0) {
							while ($row = mysqli_fetch_assoc($query_run)) {
					?>
						<tr>
							<td><?php echo htmlspecialchars($row['book_name']); ?></td>
							<td><?php echo htmlspecialchars($row['book_author']); ?></td>
							<td>
								<span class="badge badge-primary"><?php echo $row['book_quantity']; ?> cuốn</span>
							</td>
							<td>
								<a href="edit_book.php?id=<?php echo $row['book_id']; ?>" 
								   class="btn-action btn-edit" title="Chỉnh sửa">
									<i class="fas fa-edit"></i> Sửa
								</a>
								<a href="delete_book.php?id=<?php echo $row['book_id']; ?>" 
								   class="btn-action btn-delete" title="Xóa"
								   onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?')">
									<i class="fas fa-trash"></i> Xóa
								</a>
							</td>
						</tr>
					<?php
							}
						} else {
					?>
						<tr>
							<td colspan="4" class="no-results">
								<i class="fas fa-book fa-2x mb-2"></i>
								<h5>Không tìm thấy sách nào</h5>
								<?php if (!empty($search)): ?>
									<p>Không có kết quả cho từ khóa: "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
									<a href="manage_book.php" class="btn btn-outline-primary">Xem tất cả sách</a>
								<?php else: ?>
									<p>Chưa có sách nào trong thư viện</p>
									<a href="add_book.php" class="btn btn-primary">Thêm sách đầu tiên</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<script>
		<?php if (!empty($search)): ?>
			document.querySelector('input[name="search"]').focus();
		<?php endif; ?>
	</script>
</body>
</html>