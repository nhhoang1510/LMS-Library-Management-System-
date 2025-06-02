<?php
	session_start();
	
	// Kiểm tra xem người dùng đã đăng nhập chưa
	if(!isset($_SESSION['id'])) {
		header("Location: user_login.php");
		exit();
	}
	
	// Kết nối database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	// Query để lấy sách đã mượn (cả đã trả và chưa trả)
	$student_id = $_SESSION['id'];
	$query = "SELECT book_name, book_author, ISBN, issue_date, status, return_date, no 
			  FROM issued_books 
			  WHERE student_id = '$student_id' 
			  ORDER BY issue_date DESC";
	$query_run = mysqli_query($connection, $query);

	// Khởi tạo các biến thống kê với giá trị mặc định
	$total_books = 0;
	$returned_books = 0;
	$unreturned_books = 0;
	$overdue_books = 0;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sách đã mượn - LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="LMS/bootstrap-4.4.1/js/jquery_latest.js"></script>
	<script type="text/javascript" src="LMS/bootstrap-4.4.1/js/bootstrap.min.js"></script>
  	<style>
  		.overdue {
  			background-color: #ffebee !important;
  			color: #c62828;
  		}
  		.due-soon {
  			background-color: #fff3e0 !important;
  			color: #ef6c00;
  		}
  		.returned {
  			background-color: #e8f5e8 !important;
  			color: #2e7d32;
  		}
  	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
			</div>
		    <ul class="nav navbar-nav navbar-right">
		      <li class="nav-item dropdown">
	        	<a class="nav-link dropdown-toggle" data-toggle="dropdown">Hồ sơ của tôi </a>
	        	<div class="dropdown-menu">
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
	</nav><br>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-center mb-4">Sách đang mượn</h4>
				
				<?php if(mysqli_num_rows($query_run) > 0) { ?>
					<!-- Thống kê nhanh -->
					<div class="row mb-4">
						<?php 
							// Tính toán thống kê
							mysqli_data_seek($query_run, 0); // Reset pointer
							$current_date = date('Y-m-d');
							
							while($stat_row = mysqli_fetch_assoc($query_run)) {
								$total_books++;
								if($stat_row['status'] == 'Đã trả') {
									$returned_books++;
								} else {
									$unreturned_books++;
									// Kiểm tra quá hạn chỉ với sách chưa trả
									if($stat_row['return_date'] < $current_date) {
										$overdue_books++;
									}
								}
							}
							mysqli_data_seek($query_run, 0); // Reset lại pointer
						?>
						
						<div class="col-md-3">
							<div class="card text-center bg-primary text-white">
								<div class="card-body">
									<h5><?php echo $total_books; ?></h5>
									<p>Tổng số sách đã mượn</p>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card text-center bg-success text-white">
								<div class="card-body">
									<h5><?php echo $returned_books; ?></h5>
									<p>Đã trả</p>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card text-center bg-warning text-white">
								<div class="card-body">
									<h5><?php echo $unreturned_books; ?></h5>
									<p>Chưa trả</p>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card text-center bg-danger text-white">
								<div class="card-body">
									<h5><?php echo $overdue_books; ?></h5>
									<p>Quá hạn</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Bảng chi tiết -->
					<div class="card">
						<div class="card-header bg-dark text-white">
							<h5 class="mb-0">Danh sách sách đã mượn</h5>
						</div>
						<div class="card-body p-0">
							<table class="table table-bordered table-hover mb-0">
								<thead class="thead-light">
									<tr>
										<th>STT</th>
										<th>Tên sách</th>
										<th>Tác giả</th>
										<th>Ngày mượn</th>
										<th>Ngày trả dự kiến</th>
										<th>Trạng thái</th>
										<th>Ghi chú</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$stt = 1;
									$current_date = date('Y-m-d');
									while ($row = mysqli_fetch_assoc($query_run)) {
										// Tính toán trạng thái
										$is_overdue = false;
										$is_due_soon = false;
										$is_returned = ($row['status'] == 'Đã trả');
										$note = "";
										
										if(!$is_returned) {
											// Chỉ tính toán quá hạn với sách chưa trả
											$return_date = $row['return_date'];
											$days_diff = (strtotime($return_date) - strtotime($current_date)) / (60 * 60 * 24);
											
											if($days_diff < 0) {
												$is_overdue = true;
												$note = "Quá hạn " . abs(floor($days_diff)) . " ngày";
											} elseif($days_diff <= 2) {
												$is_due_soon = true;
												$note = "Sắp đến hạn (" . ceil($days_diff) . " ngày)";
											}
										}
										
										$row_class = "";
										if($is_returned) {
											$row_class = "returned";
										} elseif($is_overdue) {
											$row_class = "overdue";
										} elseif($is_due_soon) {
											$row_class = "due-soon";
										}
									?>
									<tr class="<?php echo $row_class; ?>">
										<td><?php echo $stt++; ?></td>
										<td><strong><?php echo htmlspecialchars($row['book_name']); ?></strong></td>
										<td><?php echo htmlspecialchars($row['book_author']); ?></td>
										<td><?php echo date('d/m/Y', strtotime($row['issue_date'])); ?></td>
										<td><?php echo date('d/m/Y', strtotime($row['return_date'])); ?></td>
										<td>
											<?php if($is_returned) { ?>
												<span class="badge badge-success">Đã trả</span>
											<?php } else { ?>
												<span class="badge badge-warning">Chưa trả</span>
											<?php } ?>
										</td>
										<td>
											<?php if($note != "" && !$is_returned) { ?>
												<span class="badge badge-<?php echo $is_overdue ? 'danger' : 'warning'; ?>">
													<?php echo $note; ?>
												</span>
											<?php } elseif($is_returned) { ?>
												<span class="badge badge-success">
													<i class="fas fa-check"></i> Hoàn thành
												</span>
											<?php } ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					
					<!-- Chú thích -->
					<div class="mt-3">
						<h6>Chú thích:</h6>
						<div class="row">
							<div class="col-md-6">
								<p><span class="badge badge-success">Xanh</span> - Sách đã trả</p>
								<p><span class="badge badge-warning">Vàng</span> - Sách chưa trả</p>
								<p><span class="badge badge-danger">Đỏ</span> - Sách quá hạn trả</p>
							</div>
							<div class="col-md-6">
								<p><span class="badge badge-warning">Cam</span> - Sách chưa trả</p>
								<p><em>Lưu ý: Hãy trả sách đúng hạn để tránh phí phạt</em></p>
							</div>
						</div>
					</div>
					
				<?php } else { ?>
					<div class="alert alert-info text-center">
						<h5>Chưa có sách nào được mượn</h5>
						<p>Bạn chưa mượn cuốn sách nào. Hãy tìm kiếm và mượn sách yêu thích của bạn!</p>
					</div>
				<?php } ?>
				
<div class="text-center mt-4">
	<a href="user_dashboard.php" class="btn btn-secondary">Quay lại Dashboard</a>
	<a href="borrow_book.php" class="btn btn-success">Mượn sách mới</a>
	<?php if($unreturned_books > 0) { ?>
		<a href="return_book.php" class="btn btn-info">Trả sách</a>
	<?php } ?>
</div> 

			</div>
		</div>
	</div>

	<!-- Thêm Font Awesome cho icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<script>
		// Highlight overdue books
		$(document).ready(function() {
			$('.overdue').effect('highlight', {color: '#ffcdd2'}, 3000);
		});
	</script>
</body>
</html>