<?php
	session_start();
	
	// Kiểm tra xem người dùng đã đăng nhập chưa
	if(!isset($_SESSION['id'])) {
		header("Location: index.php");
		exit();
	}
	
	// Kết nối database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	$student_id = $_SESSION['id'];
	$success_message = "";
	$error_message = "";
	
	// Xử lý trả sách
	if(isset($_POST['return_book'])) {
		$book_no = mysqli_real_escape_string($connection, $_POST['book_no']);
		
		// Kiểm tra xem sách có thuộc về người dùng này không và chưa được trả
		$check_query = "SELECT ib.*, b.book_id FROM issued_books ib 
						LEFT JOIN books b ON ib.book_name = b.book_name AND ib.book_author = b.book_author
						WHERE ib.no = '$book_no' AND ib.student_id = '$student_id' AND ib.status = 'Chưa trả'";
		$check_result = mysqli_query($connection, $check_query);
		
		if(mysqli_num_rows($check_result) > 0) {
			$book_info = mysqli_fetch_assoc($check_result);
			
			// Tính ngày dự kiến trả (7 ngày từ ngày mượn)
			$expected_return_date = date('Y-m-d');
			$actual_return_date = date('Y-m-d'); // Ngày trả thực tế là hôm nay
			
			// Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
			mysqli_autocommit($connection, FALSE);
			
			try {
				// Cập nhật trạng thái sách thành "Đã trả" và cập nhật return_date thành ngày trả thực tế
				$update_query = "UPDATE issued_books 
								SET status = 'Đã trả', 
									return_date = '$actual_return_date'
								WHERE no = '$book_no'";
				$update_result = mysqli_query($connection, $update_query);
				
				if(!$update_result) {
					throw new Exception("Lỗi cập nhật trạng thái sách");
				}
				
				// Tăng số lượng sách trong kho (chỉ khi tìm thấy book_id)
				if($book_info['book_id']) {
					$increase_quantity_query = "UPDATE books 
											   SET book_quantity = book_quantity + 1 
											   WHERE book_id = '" . $book_info['book_id'] . "'";
					$quantity_result = mysqli_query($connection, $increase_quantity_query);
					
					if(!$quantity_result) {
						throw new Exception("Lỗi cập nhật số lượng sách trong kho");
					}
				}
				
				// Commit transaction
				mysqli_commit($connection);
				mysqli_autocommit($connection, TRUE);
				
				$success_message = "Trả sách thành công: " . $book_info['book_name'];
				if($days_overdue > 0) {
					$success_message .= " (Trả muộn " . $days_overdue . " ngày)";
				}
				
			} catch (Exception $e) {
				// Rollback nếu có lỗi
				mysqli_rollback($connection);
				mysqli_autocommit($connection, TRUE);
				$error_message = "Có lỗi xảy ra khi trả sách: " . $e->getMessage();
			}
		} else {
			$error_message = "Không tìm thấy sách hoặc sách đã được trả.";
		}
	}
	
	// Lấy danh sách sách đang mượn (chưa trả)
	// return_date ở đây vẫn là ngày dự kiến trả khi chưa trả sách
	$query = "SELECT book_name, book_author, ISBN, issue_date, 
			  		 DATE_ADD(issue_date, INTERVAL 7 DAY) as expected_return_date, 
			  		 no
			  FROM issued_books 
			  WHERE student_id = '$student_id' AND status = 'Chưa trả'
			  ORDER BY issue_date ASC";
	$query_run = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Trả sách - LMS</title>
	<meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
  	<script type="text/javascript" src="bootstrap-4.4.1/js/jquery.min.js"></script>
  	<script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  	<style>
  		.overdue {
  			background-color: #ffebee !important;
  			border-left: 4px solid #f44336;
  		}
  		.due-soon {
  			background-color: #fff3e0 !important;
  			border-left: 4px solid #ff9800;
  		}
  		.return-card {
  			transition: all 0.3s ease;
  		}
  		.return-card:hover {
  			transform: translateY(-2px);
  			box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
				<h4 class="text-center mb-4">
					<i class="fas fa-book-reader"></i> Trả sách
				</h4>
				
				<!-- Thông báo -->
				<?php if($success_message != "") { ?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong>Trả sách thành công!</strong> <?php echo $success_message; ?>
						<button type="button" class="close" data-dismiss="alert">
							<span>&times;</span>
						</button>
					</div>
				<?php } ?>
				
				<?php if($error_message != "") { ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Đã xảy ra lỗi!</strong> <?php echo $error_message; ?>
						<button type="button" class="close" data-dismiss="alert">
							<span>&times;</span>
						</button>
					</div>
				<?php } ?>
				
				<?php if(mysqli_num_rows($query_run) > 0) { ?>
					<!-- Thống kê nhanh -->
					<div class="row mb-4">
						<?php 
							mysqli_data_seek($query_run, 0);
							$total_unreturned = 0;
							$overdue_count = 0;
							$due_soon_count = 0;
							$current_date = date('Y-m-d');
							
							while($stat_row = mysqli_fetch_assoc($query_run)) {
								$total_unreturned++;
								$expected_return = $stat_row['expected_return_date'];
								$days_diff = (strtotime($expected_return) - strtotime($current_date)) / (60 * 60 * 24);
								
								if($days_diff < 0) {
									$overdue_count++;
								} elseif($days_diff <= 2) {
									$due_soon_count++;
								}
							}
							mysqli_data_seek($query_run, 0);
						?>
						
						<div class="col-md-4">
							<div class="card text-center bg-info text-white">
								<div class="card-body">
									<h5><?php echo $total_unreturned; ?></h5>
									<p>Sách chưa trả</p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card text-center bg-warning text-white">
								<div class="card-body">
									<h5><?php echo $due_soon_count; ?></h5>
									<p>Sắp đến hạn</p>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card text-center bg-danger text-white">
								<div class="card-body">
									<h5><?php echo $overdue_count; ?></h5>
									<p>Quá hạn</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Danh sách sách cần trả -->
					<div class="row">
						<?php 
						$current_date = date('Y-m-d');
						while ($row = mysqli_fetch_assoc($query_run)) {
							// Tính toán trạng thái dựa trên ngày dự kiến trả
							$expected_return_date = $row['expected_return_date'];
							$days_diff = (strtotime($expected_return_date) - strtotime($current_date)) / (60 * 60 * 24);
							
							$is_overdue = $days_diff < 0;
							$is_due_soon = !$is_overdue && $days_diff <= 2;
							
							$card_class = "";
							$status_badge = "";
							$status_text = "";
							
							if($is_overdue) {
								$card_class = "overdue";
								$status_badge = "badge-danger";
								$status_text = "Quá hạn " . abs(floor($days_diff)) . " ngày";
							} elseif($is_due_soon) {
								$card_class = "due-soon";
								$status_badge = "badge-warning";
								$status_text = "Sắp đến hạn (" . ceil($days_diff) . " ngày)";
							} else {
								$status_badge = "badge-success";
								$status_text = "Còn " . ceil($days_diff) . " ngày";
							}
						?>
						<div class="col-md-6 mb-4">
							<div class="card return-card <?php echo $card_class; ?>">
								<div class="card-header d-flex justify-content-between align-items-center">
									<h6 class="mb-0"><?php echo htmlspecialchars($row['book_name']); ?></h6>
									<span class="badge <?php echo $status_badge; ?>"><?php echo $status_text; ?></span>
								</div>
								<div class="card-body">
									<p class="card-text">
										<strong>Tác giả:</strong> <?php echo htmlspecialchars($row['book_author']); ?><br>
										<strong>Ngày mượn:</strong> <?php echo date('d/m/Y', strtotime($row['issue_date'])); ?><br>
										<strong>Ngày trả dự kiến:</strong> <?php echo date('d/m/Y', strtotime($row['expected_return_date'])); ?>
									</p>
									
									<form method="POST" style="display: inline;">
										<input type="hidden" name="book_no" value="<?php echo $row['no']; ?>">
										<button type="submit" name="return_book" class="btn btn-primary btn-sm" 
											onclick="return confirm('Bạn có chắc chắn muốn trả sách này không?')">
											<i class="fas fa-undo"></i> Trả sách
										</button>
									</form>
									
									<?php if($is_overdue) { ?>
										<small class="text-danger d-block mt-2">
											<i class="fas fa-exclamation-triangle"></i> 
											Sách này đã quá hạn trả!
										</small>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					
					
				<?php } else { ?>
					<div class="alert alert-success text-center">
						<h5><i class="fas fa-check-circle"></i> Tuyệt vời!</h5>
						<p>Bạn không có sách nào cần trả. Tất cả sách đã được trả đầy đủ!</p>
					</div>
				<?php } ?>
				
				<!-- Nút điều hướng -->
				<div class="text-center mt-4">
					<a href="user_dashboard.php" class="btn btn-secondary">
						<i class="fas fa-home"></i> Quay lại Dashboard
					</a>
					<a href="view_issued_book.php" class="btn btn-info">
						<i class="fas fa-list"></i> Xem lịch sử mượn sách
					</a>
					<a href="search_book.php" class="btn btn-success">
						<i class="fas fa-search"></i> Tìm sách mới
					</a>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		// Auto hide alerts after 5 seconds
		$(document).ready(function() {
			setTimeout(function() {
				$('.alert').fadeOut('slow');
			}, 5000);
			
			// Add hover effect to cards
			$('.return-card').hover(
				function() {
					$(this).addClass('shadow-lg');
				},
				function() {
					$(this).removeClass('shadow-lg');
				}
			);
		});
	</script>
</body>
</html>