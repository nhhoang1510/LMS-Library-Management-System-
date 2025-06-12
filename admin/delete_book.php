<?php
	session_start();
	$connection = mysqli_connect("localhost","root","","lms");
	if (!$connection) {
		die("Kết nối thất bại: " . mysqli_connect_error());
	}

	$message = "";
	$message_type = "error";
	
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$book_id = mysqli_real_escape_string($connection, $_GET['id']);

		$check_query = "SELECT book_name, ISBN FROM books WHERE book_id = '$book_id'";
		$check_result = mysqli_query($connection, $check_query);
		
		if(mysqli_num_rows($check_result) > 0) {
			$book_info = mysqli_fetch_assoc($check_result);
			$book_name = $book_info['book_name'];
			$ISBN = $book_info['ISBN'];
			
			$issued_check = "SELECT COUNT(*) as count FROM issued_books 
							WHERE ISBN = '$ISBN' AND status = 'Chưa trả'";
			$issued_result = mysqli_query($connection, $issued_check);
			$issued_row = mysqli_fetch_assoc($issued_result);
			
			if($issued_row['count'] > 0) {

				$message = "Không thể xóa sách '$book_name' vì hiện tại có " . $issued_row['count'] . " cuốn đang được mượn!";
				$message_type = "warning";
			} else {
				$delete_query = "DELETE FROM books WHERE book_id = '$book_id'";
				
				if(mysqli_query($connection, $delete_query)) {
					$message = "Đã xóa sách '$book_name' thành công!";
					$message_type = "success";
				} else {
					$message = "Có lỗi xảy ra khi xóa sách: " . mysqli_error($connection);
					$message_type = "error";
				}
			}
		} else {
			$message = "Không tìm thấy sách cần xóa!";
			$message_type = "error";
		}
	} else {
		$message = "ID sách không hợp lệ!";
		$message_type = "error";
	}
	
	mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Xóa Sách - LMS</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<style>
		body {
			background-color: #f8f9fa;
			font-family: Arial, sans-serif;
		}
		
		.message-container {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
		}
		
		.message-card {
			background: white;
			padding: 40px;
			border-radius: 10px;
			box-shadow: 0 4px 6px rgba(0,0,0,0.1);
			text-align: center;
			max-width: 500px;
			width: 90%;
		}
		
		.icon-success {
			color: #28a745;
			font-size: 4rem;
			margin-bottom: 20px;
		}
		
		.icon-error {
			color: #dc3545;
			font-size: 4rem;
			margin-bottom: 20px;
		}
		
		.icon-warning {
			color: #ffc107;
			font-size: 4rem;
			margin-bottom: 20px;
		}
		
		.message-text {
			font-size: 1.2rem;
			margin-bottom: 30px;
		}
		
		.btn-return {
			font-size: 1.1rem;
			padding: 12px 30px;
		}
	</style>
</head>
<body>
	<div class="message-container">
		<div class="message-card">
			<?php if($message_type == "success"): ?>
				<i class="fas fa-check-circle icon-success"></i>
				<h4 class="text-success mb-3">Thành Công!</h4>
			<?php elseif($message_type == "warning"): ?>
				<i class="fas fa-exclamation-triangle icon-warning"></i>
				<h4 class="text-warning mb-3">Cảnh Báo!</h4>
			<?php else: ?>
				<i class="fas fa-times-circle icon-error"></i>
				<h4 class="text-danger mb-3">Lỗi!</h4>
			<?php endif; ?>
			
			<p class="message-text"><?php echo htmlspecialchars($message); ?></p>
			
			<a href="manage_book.php" class="btn btn-primary btn-return">
				<i class="fas fa-arrow-left"></i> Quay lại danh sách sách
			</a>
		</div>
	</div>
	
	<script>
		setTimeout(function() {
			window.location.href = "manage_book.php";
		}, 3000);
		
		<?php if($message_type == "success"): ?>
			<?php echo addslashes($message); ?>;
		<?php endif; ?>
	</script>
</body>
</html>