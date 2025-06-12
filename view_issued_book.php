<?php
	session_start();

	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	$book_name = "";
	$author = "";
	$book_no = "";
	$student_name = "";
	$query = "select issued_books.book_name,issued_books.book_author,users.name,issued_books.issue_date,issued_books.status,issued_books.return_date from issued_books left join users on issued_books.student_id = users.id ORDER BY issued_books.status ASC, issued_books.issue_date DESC";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sách đã mượn | LMS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	
	<style>
		body {
			background-color: #f8f9fa;
		}
		
		.content-section {
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			margin: 20px 0;
		}
		
		.page-header {
			background: linear-gradient(45deg, #ffc107, #e0a800);
			color: white;
			padding: 30px;
			border-radius: 8px;
			text-align: center;
			margin-bottom: 30px;
		}
		
		.table-responsive {
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			border-radius: 8px;
			overflow: hidden;
		}
		
		.table {
			margin-bottom: 0;
		}
		
		.table thead th {
			background: linear-gradient(45deg, #ffc107, #e0a800);
			color: #212529;
			border: none;
			font-weight: 500;
			text-align: center;
			vertical-align: middle;
			padding: 15px 8px;
			font-size: 0.9em;
		}
		
		.table tbody td {
			text-align: center;
			vertical-align: middle;
			padding: 12px 8px;
			border-bottom: 1px solid #dee2e6;
			font-size: 0.9em;
		}
		
		.table tbody tr:hover {
			background-color: #f8f9fa;
		}
		
		.status-badge {
			padding: 5px 10px;
			border-radius: 15px;
			font-size: 0.8em;
			font-weight: 500;
			white-space: nowrap;
		}
		
		.status-issued {
			background-color: #ffc107;
			color: #212529;
		}
		
		.status-returned {
			background-color: #28a745;
			color: white;
		}
		
		.navbar-light {
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		.dropdown-menu {
			display: none;
			position: absolute;
			top: 100%;
			left: 0;
			z-index: 1000;
			min-width: 160px;
			padding: 5px 0;
			margin: 2px 0 0;
			background-color: #fff;
			border: 1px solid rgba(0,0,0,.15);
			border-radius: 0.25rem;
			box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
		}
		
		.dropdown.show .dropdown-menu,
		.dropdown-menu.show {
			display: block;
		}
		
		.dropdown-item {
			display: block;
			width: 100%;
			padding: 0.25rem 1.5rem;
			clear: both;
			font-weight: 400;
			color: #212529;
			text-align: inherit;
			text-decoration: none;
			white-space: nowrap;
			background-color: transparent;
			border: 0;
		}
		
		.dropdown-item:hover,
		.dropdown-item:focus {
			color: #16181b;
			text-decoration: none;
			background-color: #f8f9fa;
		}
		
		.dropdown-divider {
			height: 0;
			margin: 0.5rem 0;
			overflow: hidden;
			border-top: 1px solid #e9ecef;
		}
		
		.back-btn {
			margin-bottom: 20px;
		}
		
		.empty-state {
			text-align: center;
			padding: 50px;
			color: #6c757d;
		}
		
		.empty-state i {
			font-size: 3em;
			margin-bottom: 20px;
			color: #dee2e6;
		}
		
		.alert-info {
			background: linear-gradient(45deg, #fff3cd, #ffeaa7);
			border: 1px solid #ffc107;
			color: #856404;
		}
		
		.btn-primary {
			background: linear-gradient(45deg, #ffc107, #e0a800);
			border: none;
			color: #212529;
			font-weight: 500;
		}
		
		.btn-primary:hover {
			background: linear-gradient(45deg, #e0a800, #d39e00);
			color: #212529;
		}
		.filter-buttons {
			margin-bottom: 20px;
		}
		
		.btn-filter {
			margin-right: 10px;
			margin-bottom: 10px;
		}
		
		.btn-filter.active {
			background-color: #ffc107;
			border-color: #ffc107;
			color: #212529;
		}
		@media (max-width: 768px) {
			.table thead th,
			.table tbody td {
				padding: 8px 4px;
				font-size: 0.8em;
			}
			
			.status-badge {
				font-size: 0.7em;
				padding: 3px 6px;
			}
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

	<div class="container-fluid">
		<div class="content-section">
			<div class="filter-buttons">
				<button class="btn btn-outline-primary btn-filter active" data-filter="all">
					<i class="fas fa-list"></i> Tất cả
				</button>
				<button class="btn btn-outline-warning btn-filter" data-filter="borrowing">
					<i class="fas fa-book"></i> Đang mượn
				</button>
				<button class="btn btn-outline-success btn-filter" data-filter="returned">
					<i class="fas fa-check"></i> Đã trả
				</button>
			</div>
			
			<div class="table-responsive">
				<?php
					$query_run = mysqli_query($connection,$query);
					$row_count = mysqli_num_rows($query_run);
					
					if($row_count > 0) {
				?>
				<table class="table table-hover">
					<thead>
						<tr>
							<th><i class="fas fa-book"></i> Tên sách</th>
							<th><i class="fas fa-user-edit"></i> Tác giả</th>
							<th><i class="fas fa-user"></i> Người mượn</th>
							<th><i class="fas fa-calendar-alt"></i> Ngày mượn</th>
							<th><i class="fas fa-calendar-check"></i> Ngày trả</th>
							<th><i class="fas fa-info-circle"></i> Trạng thái</th>
						</tr>
					</thead>
					<tbody>
						<?php
							mysqli_data_seek($query_run, 0); 
							$borrowing_count = 0;
							$returned_count = 0;
							
							while ($row = mysqli_fetch_assoc($query_run)){
								$is_borrowing = ($row['status'] == 'Chưa trả');
								$status_text = $is_borrowing ? 'Đang mượn' : 'Đã trả';
								$status_class = $is_borrowing ? 'status-issued' : 'status-returned';
								$row_class = $is_borrowing ? 'row-borrowing' : 'row-returned';
								
								if ($is_borrowing) {
									$borrowing_count++;
								} else {
									$returned_count++;
								}
								
								$issue_date = date('d/m/Y', strtotime($row['issue_date']));
								
								$return_date = '';
								if (!$is_borrowing && $row['return_date'] && $row['return_date'] != '0000-00-00') {
									$return_date = date('d/m/Y', strtotime($row['return_date']));
								} else if ($is_borrowing) {
									$return_date = '<span class="text-muted"><i>Chưa trả</i></span>';
								} else {
									$return_date = '<span class="text-warning"><i>Chưa cập nhật</i></span>';
								}
								?>
								<tr class="<?php echo $row_class; ?>">
									<td><strong><?php echo htmlspecialchars($row['book_name']);?></strong></td>
									<td><?php echo htmlspecialchars($row['book_author']);?></td>
									<td><?php echo htmlspecialchars($row['name']);?></td>
									<td><?php echo $issue_date;?></td>
									<td><?php echo $return_date;?></td>
									<td>
										<span class="status-badge <?php echo $status_class;?>">
											<?php echo $status_text;?>
										</span>
									</td>
								</tr>
							<?php
							}
						?>	
					</tbody>
				</table>
				<?php
					} else {
				?>
				<div class="empty-state">
					<i class="fas fa-book-open"></i>
					<h4>Không có dữ liệu mượn sách</h4>
					<p>Hiện tại không có dữ liệu mượn sách nào trong hệ thống.</p>
					<a href="user_dashboard.php" class="btn btn-primary">
						<i class="fas fa-home"></i> Về Dashboard
					</a>
				</div>
				<?php
					}
				?>
			</div>
			
			<?php if($row_count > 0) { ?>
			<div class="row mt-3">
				<div class="col-md-4">
					<div class="alert alert-warning">
						<i class="fas fa-book"></i> 
						<strong>Đang mượn:</strong> <span id="borrowing-count"><?php echo $borrowing_count; ?></span> sách
					</div>
				</div>
				<div class="col-md-4">
					<div class="alert alert-success">
						<i class="fas fa-check"></i> 
						<strong>Đã trả:</strong> <span id="returned-count"><?php echo $returned_count; ?></span> sách
					</div>
				</div>
				<div class="col-md-4">
					<div class="alert alert-info">
						<i class="fas fa-list"></i> 
						<strong>Tổng cộng:</strong> <span id="total-count"><?php echo $row_count; ?></span> bản ghi
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

	<script>

		$(document).ready(function() {
			console.log('jQuery version:', $.fn.jquery);
			console.log('Bootstrap loaded:', typeof $.fn.dropdown !== 'undefined');
			
			$('.dropdown-toggle').off('click');
			
			if (typeof $.fn.dropdown !== 'undefined') {
				$('.dropdown-toggle').dropdown();
				console.log('Bootstrap dropdown initialized');
			} else {
				$('.dropdown-toggle').on('click.customDropdown', function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					var $parent = $(this).parent();
					var isOpen = $parent.hasClass('show');
					
					$('.dropdown').removeClass('show');
					$('.dropdown-toggle').attr('aria-expanded', 'false');
					
					if (!isOpen) {
						$parent.addClass('show');
						$(this).attr('aria-expanded', 'true');
					}
				});
			
				$(document).on('click.customDropdown', function(e) {
					if (!$(e.target).closest('.dropdown').length) {
						$('.dropdown').removeClass('show');
						$('.dropdown-toggle').attr('aria-expanded', 'false');
					}
				});
				
				console.log('Custom dropdown functionality added');
			}
			
			$('.btn-filter').on('click', function() {
				var filter = $(this).data('filter');
				
				$('.btn-filter').removeClass('active');
				$(this).addClass('active');
				
				var visibleRows = 0;
				var borrowingVisible = 0;
				var returnedVisible = 0;
				
				$('tbody tr').each(function() {
					var $row = $(this);
					var shouldShow = false;
					
					if (filter === 'all') {
						shouldShow = true;
					} else if (filter === 'borrowing') {
						shouldShow = $row.hasClass('row-borrowing');
					} else if (filter === 'returned') {
						shouldShow = $row.hasClass('row-returned');
					}
					
					if (shouldShow) {
						$row.show();
						visibleRows++;
						
						if ($row.hasClass('row-borrowing')) {
							borrowingVisible++;
						} else {
							returnedVisible++;
						}
					} else {
						$row.hide();
					}
				});
				
				if (filter === 'all') {
					$('#borrowing-count').text(<?php echo $borrowing_count; ?>);
					$('#returned-count').text(<?php echo $returned_count; ?>);
					$('#total-count').text(<?php echo $row_count; ?>);
				} else {
					$('#borrowing-count').text(borrowingVisible);
					$('#returned-count').text(returnedVisible);
					$('#total-count').text(visibleRows);
				}
			});
		});
	</script>
</body>
</html>