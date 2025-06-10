<?php
	session_start();

	// Kết nối database
	$connection = mysqli_connect("localhost","root","");
	$db = mysqli_select_db($connection,"lms");
	
	// Thêm chức năng tìm kiếm
	$query_string = isset($_GET['query']) ? trim($_GET['query']) : '';
	if($query_string != '') {
		$query_string_escaped = mysqli_real_escape_string($connection, $query_string);
		$query = "SELECT book_id, book_name, book_author, book_quantity FROM books 
				  WHERE (book_name LIKE '%$query_string_escaped%' OR book_author LIKE '%$query_string_escaped%') 
				  ORDER BY book_name ASC";
	} else {
		$query = "SELECT book_id, book_name, book_author, book_quantity FROM books ORDER BY book_name ASC";
	}
	
	// Tính tổng số đầu sách và tổng số lượng
	$total_books_query = "SELECT COUNT(*) as total_titles, SUM(book_quantity) as total_quantity FROM books";
	$total_result = mysqli_query($connection, $total_books_query);
	$total_data = mysqli_fetch_assoc($total_result);
	
	$total_titles = $total_data['total_titles'];
	$total_quantity = $total_data['total_quantity'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Danh sách sách - LMS</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
	
	<!-- Bootstrap 4 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<style>
		body {
			background-color: #f8f9fa;
		}
		
        .search-form {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
        }
        .stats-info {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .quantity-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .quantity-high {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .quantity-medium {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .quantity-low {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
		
		.content-section {
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			margin: 20px 0;
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
			background: linear-gradient(45deg, #007bff, #0056b3);
			color: white;
			border: none;
			font-weight: 500;
			text-align: center;
			vertical-align: middle;
			padding: 15px 10px;
		}
		
		.table tbody td {
			text-align: center;
			vertical-align: middle;
			padding: 12px 10px;
			border-bottom: 1px solid #dee2e6;
		}
		
		.table tbody tr:hover {
			background-color: #f8f9fa;
		}
		
		.navbar-light {
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		
		/* Custom dropdown styles */
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
		
		.edit-btn {
			padding: 0.25rem 0.5rem;
			font-size: 0.875rem;
		}
    </style>
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

	<div class="container-fluid">
        <div class="content-section">
            <div class="stats-info mb-4">
                <div class="row text-center">
                    <div class="col-md-6">
                        <h5 class="text-primary mb-1"><?php echo $total_titles; ?></h5>
                        <small class="text-muted">Tổng số đầu sách</small>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-success mb-1"><?php echo $total_quantity; ?></h5>
                        <small class="text-muted">Tổng số lượng sách</small>
                    </div>
                </div>
            </div>
            
            <!-- Form tìm kiếm -->
            <div class="search-form mb-4">
                <form method="GET" action="Regbooks.php">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="query" 
                                   placeholder="Tìm kiếm theo tên sách hoặc tác giả..." 
                                   value="<?php echo htmlspecialchars($query_string); ?>">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">
								<i class="fas fa-search"></i> Tìm kiếm
							</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php 
            $query_run = mysqli_query($connection, $query);
            if(mysqli_num_rows($query_run) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-list-ol"></i> STT</th>
                                <th><i class="fas fa-book"></i> Tên sách</th>
                                <th><i class="fas fa-user-edit"></i> Tác giả</th>
                                <th><i class="fas fa-sort-numeric-up"></i> Số lượng</th>
                                <th><i class="fas fa-info-circle"></i> Trạng thái</th>
                                <th><i class="fas fa-cog"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 1;
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                // Xác định màu badge dựa trên số lượng
                                $quantity = $row['book_quantity'];
                                if($quantity >= 10) {
                                    $badge_class = "quantity-high";
                                    $status_badge = "success";
                                    $status_text = "Còn nhiều";
                                } elseif($quantity >= 5) {
                                    $badge_class = "quantity-medium";
                                    $status_badge = "warning";
                                    $status_text = "Còn ít";
                                } elseif($quantity > 0) {
                                    $badge_class = "quantity-low";
                                    $status_badge = "danger";
                                    $status_text = "Sắp hết";
                                } else {
                                    $badge_class = "quantity-low";
                                    $status_badge = "secondary";
                                    $status_text = "Hết sách";
                                }
                            ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['book_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['book_author']); ?></td>
                                <td>
                                    <span class="quantity-badge <?php echo $badge_class; ?>">
                                        <?php echo $quantity; ?> cuốn
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $status_badge; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_book.php?book_id=<?php echo $row['book_id']; ?>" 
                                       class="btn btn-sm btn-primary edit-btn" 
                                       title="Chỉnh sửa sách">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <a href="delete_book.php?book_id=<?php echo $row['book_id']; ?>" 
                                       class="btn btn-sm btn-danger edit-btn ml-1" 
                                       title="Xóa sách"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sách này không?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
            <?php } else { ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> 
                    <h5>Không có sách nào</h5>
                    <p class="mb-0">
                        <?php if($query_string != '') { ?>
                            Không tìm thấy sách nào phù hợp với từ khóa "<strong><?php echo htmlspecialchars($query_string); ?></strong>".
                            <br><a href="Regbooks.php" class="btn btn-primary mt-2">
								<i class="fas fa-list"></i> Xem tất cả sách
							</a>
                        <?php } else { ?>
                            Hiện tại chưa có sách nào trong hệ thống.
                            <br><a href="add_book.php" class="btn btn-success mt-2">
								<i class="fas fa-plus"></i> Thêm sách mới
							</a>
                        <?php } ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </div>

	<!-- jQuery first -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
	<!-- Popper.js -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<!-- Bootstrap 4 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

	<script>
		// Kiểm tra và khởi tạo dropdown
		$(document).ready(function() {
			console.log('jQuery version:', $.fn.jquery);
			console.log('Bootstrap loaded:', typeof $.fn.dropdown !== 'undefined');
			
			// Loại bỏ tất cả event listeners cũ
			$('.dropdown-toggle').off('click');
			
			// Sử dụng Bootstrap dropdown mặc định
			if (typeof $.fn.dropdown !== 'undefined') {
				$('.dropdown-toggle').dropdown();
				console.log('Bootstrap dropdown initialized');
			} else {
				// Fallback: Tự tạo dropdown functionality
				$('.dropdown-toggle').on('click.customDropdown', function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					var $parent = $(this).parent();
					var isOpen = $parent.hasClass('show');
					
					// Đóng tất cả dropdown khác
					$('.dropdown').removeClass('show');
					$('.dropdown-toggle').attr('aria-expanded', 'false');
					
					// Toggle dropdown hiện tại
					if (!isOpen) {
						$parent.addClass('show');
						$(this).attr('aria-expanded', 'true');
					}
				});
				
				// Đóng dropdown khi click ra ngoài
				$(document).on('click.customDropdown', function(e) {
					if (!$(e.target).closest('.dropdown').length) {
						$('.dropdown').removeClass('show');
						$('.dropdown-toggle').attr('aria-expanded', 'false');
					}
				});
				
				console.log('Custom dropdown functionality added');
			}
		});
	</script>
</body>
</html>