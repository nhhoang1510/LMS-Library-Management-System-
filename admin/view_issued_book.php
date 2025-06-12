<?php
	session_start();
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$book_name = "";
	$author = "";
	$book_no = "";
	$student_name = "";
	$query = "select issued_books.book_name,issued_books.book_author,users.name,issued_books.issue_date,issued_books.status,issued_books.return_date from issued_books left join users on issued_books.student_id = users.id ORDER BY issued_books.status ASC, issued_books.issue_date DESC";

	$search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_query_part = "";
    if (!empty($search)) {
        $search_escaped = mysqli_real_escape_string($connection, $search);
        $search_query_part = " WHERE issued_books.book_name LIKE '%$search_escaped%' OR users.name LIKE '%$search_escaped%'";
    }
    $query = "SELECT issued_books.book_name, issued_books.book_author, users.name, issued_books.issue_date, issued_books.status, issued_books.return_date FROM issued_books LEFT JOIN users ON issued_books.student_id = users.id" . $search_query_part . " ORDER BY issued_books.status ASC, issued_books.issue_date DESC";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lịch sử mượn/trả sách - LMS</title>
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
		.table-container {
			background-color: white;
			border-radius: 20px;
			padding: 20px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
			margin: 20px;
		}
		.table th {
			background-color: #32434e;
			color: white;
			font-weight: 500;
			border: none;
		}
		.table td {
			vertical-align: middle;
		}
		.status-badge {
			padding: 5px 10px;
			border-radius: 15px;
			font-size: 12px;
			font-weight: 500;
		}
		.status-issued {
			background-color: #ffc107;
			color: #000;
		}
		.status-returned {
			background-color: #28a745;
			color: white;
		}
		.search-form {
			max-width: 700px;
			margin: 0 auto 20px;
			background-color: white;
			padding: 20px;
			border-radius: 20px;
			box-shadow: 0 0 15px rgba(0,0,0,0.1);
		}
		.search-form .form-control {
			border-radius: 5px;
			height: 38px;
		}
		.search-form .btn {
			border-radius: 5px;
			height: 38px;
			background-color: #32434e;
			color: white;
		}
		.search-form .btn:hover {
			background-color: #93bee4;
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
			<b>Lịch sử mượn/trả sách</b>
		</h1>
		<h5 class="text-center"> <b><i>Xem lịch sử mượn và trả sách của người dùng</i></b></h5>
	</div>

	<!-- Search Form -->
	<div class="search-form">
		<form action="" method="GET">
			<div class="input-group">
				<input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên sách hoặc người dùng..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
				<div class="input-group-append">
					<button class="btn" type="submit">Tìm kiếm</button>
				</div>
			</div>
		</form>
	</div>

	<!-- Table Container -->
	<div class="table-container">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Tên sách</th>
						<th>Người mượn</th>
						<th>Ngày mượn</th>
                        <th>Ngày trả</th>
                        <th>Trạng thái</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$query_run = mysqli_query($connection, $query);
						while($row = mysqli_fetch_assoc($query_run)){
							$status_class = '';
							if($row['status'] == 'Issued'){
								$status_class = 'status-issued';
							} elseif($row['status'] == 'Returned'){
								$status_class = 'status-returned';
							}
							
							$issue_date = date("d/m/Y", strtotime($row['issue_date']));
							$return_date = date("d/m/Y", strtotime($row['return_date']));
							?>
							<tr>
								<td><?php echo $row['book_name']; ?></td>
								<td><?php echo $row['book_author']; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo $issue_date; ?></td>
								<td><?php echo $return_date; ?></td>
								<td><span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
							</tr>
							<?php
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>