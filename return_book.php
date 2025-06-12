<?php
	session_start();
	
	if(!isset($_SESSION['id'])) {
		header("Location: index.php");
		exit();
	}
	
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	$student_id = $_SESSION['id'];
	$message = "";
	$alert_type = "";
	
	if(isset($_POST['return_book'])) {
		$book_no = mysqli_real_escape_string($connection, $_POST['book_no']);
		
		$check_query = "SELECT ib.*, b.book_id FROM issued_books ib 
						LEFT JOIN books b ON ib.book_name = b.book_name AND ib.book_author = b.book_author
						WHERE ib.no = '$book_no' AND ib.student_id = '$student_id' AND ib.status = 'Chưa trả'";
		$check_result = mysqli_query($connection, $check_query);
		
		if(mysqli_num_rows($check_result) > 0) {
			$book_info = mysqli_fetch_assoc($check_result);
			
			$actual_return_date = date('Y-m-d'); 
			
			mysqli_autocommit($connection, FALSE);
			
			try {
				$update_query = "UPDATE issued_books 
								SET status = 'Đã trả', 
									return_date = '$actual_return_date'
								WHERE no = '$book_no'";
				$update_result = mysqli_query($connection, $update_query);
				
				if(!$update_result) {
					throw new Exception("Lỗi cập nhật trạng thái sách");
				}
				
				if($book_info['book_id']) {
					$increase_quantity_query = "UPDATE books 
											   SET book_quantity = book_quantity + 1 
											   WHERE book_id = '" . $book_info['book_id'] . "'";
					$quantity_result = mysqli_query($connection, $increase_quantity_query);
					
					if(!$quantity_result) {
						throw new Exception("Lỗi cập nhật số lượng sách trong kho");
					}
				}
				
				mysqli_commit($connection);
				mysqli_autocommit($connection, TRUE);
				
				$message = "Trả sách thành công: " . $book_info['book_name'];
				$alert_type = "success";
				
			} catch (Exception $e) {
				mysqli_rollback($connection);
				mysqli_autocommit($connection, TRUE);
				$message = "Có lỗi xảy ra khi trả sách: " . $e->getMessage();
				$alert_type = "danger";
			}
		} else {
			$message = "Không tìm thấy sách hoặc sách đã được trả.";
			$alert_type = "danger";
		}
	}
	
	$query = "SELECT book_name, book_author, issue_date, no
			  FROM issued_books 
			  WHERE student_id = '$student_id' AND status = 'Chưa trả'
			  ORDER BY issue_date ASC";
	$query_run = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Trả sách - LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="bootstrap-4.4.1/js/juqery_latest.js"></script>
  	<script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
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
	</nav><br>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h4 class="text-center mb-4">
					<i class="fas fa-book-reader"></i> Trả sách
				</h4>
				
				<?php if($message != "") { ?>
					<div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
						<strong>
							<?php if($alert_type == 'success') echo 'Thành công!'; 
								  elseif($alert_type == 'danger') echo 'Lỗi!'; 
								  else echo 'Thông báo!'; ?>
						</strong> <?php echo $message; ?>
						<button type="button" class="close" data-dismiss="alert">
							<span>&times;</span>
						</button>
					</div>
				<?php } ?>
				
				<?php if(mysqli_num_rows($query_run) > 0) { ?>
					<div class="card">
						<div class="card-header bg-dark text-white">
							<h5 class="mb-0">Danh sách sách cần trả</h5>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead class="thead-light">
										<tr>
											<th>STT</th>
											<th>Tên sách</th>
											<th>Tác giả</th>
											<th>Ngày mượn</th>
											<th>Thao tác</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$stt = 1;
										while ($row = mysqli_fetch_assoc($query_run)) {
										?>
										<tr>
											<td><?php echo $stt++; ?></td>
											<td><strong><?php echo htmlspecialchars($row['book_name']); ?></strong></td>
											<td><?php echo htmlspecialchars($row['book_author']); ?></td>
											<td><?php echo date('d/m/Y', strtotime($row['issue_date'])); ?></td>
											<td>
												<form method="POST" style="display: inline;">
													<input type="hidden" name="book_no" value="<?php echo $row['no']; ?>">
													<button type="submit" name="return_book" 
														class="btn btn-sm btn-primary" 
														onclick="return confirm('Bạn có chắc chắn muốn trả sách này không?')">
														<i class="fas fa-undo"></i> Trả sách
													</button>
												</form>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
				<?php } else { ?>
					<div class="alert alert-success text-center">
						<p class="mb-0">Bạn không có sách nào cần trả. Tất cả sách đã được trả đầy đủ!</p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>