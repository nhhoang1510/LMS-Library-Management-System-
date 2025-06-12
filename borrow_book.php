<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Kết nối database với error handling
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}

// Set charset để tránh lỗi encoding
mysqli_set_charset($connection, 'utf8');

$message = "";
$alert_type = "";

if(isset($_POST['borrow_book'])) {
    $book_id = mysqli_real_escape_string($connection, $_POST['book_id']);
    $student_id = mysqli_real_escape_string($connection, $_SESSION['id']);
    
    // Kiểm tra xem sách có tồn tại không
    $book_query = "SELECT * FROM books WHERE book_id = '$book_id'";
    $book_result = mysqli_query($connection, $book_query);
    
    if(mysqli_num_rows($book_result) > 0) {
        $book = mysqli_fetch_assoc($book_result);
        
        // Kiểm tra số lượng sách có sẵn
        if($book['book_quantity'] > 0) {
            $request_date = date('Y-m-d');
            
            // Bắt đầu transaction
            mysqli_autocommit($connection, false);
            
            try {
                // Thêm yêu cầu mượn sách vào bảng user_request
                $insert_request_query = "INSERT INTO user_request 
                                       (student_id, book_id, request_date, return_date, action, status) 
                                       VALUES ('$student_id', '$book_id', '$request_date', '0000-00-00', 'Mượn', 'pending')";
                
                if(mysqli_query($connection, $insert_request_query)) {
                    // Lấy ID của request vừa tạo
                    $request_id = mysqli_insert_id($connection);
                    
                    // Commit transaction
                    mysqli_commit($connection);
                    
                    $message = "Gửi yêu cầu mượn sách \"" . $book['book_name'] . "\" thành công! Mã yêu cầu: #$request_id. Vui lòng chờ thư viện xác nhận.";
                    $alert_type = "success";
                } else {
                    throw new Exception("Lỗi khi thêm yêu cầu: " . mysqli_error($connection));
                }
            } catch (Exception $e) {
                // Rollback nếu có lỗi
                mysqli_rollback($connection);
                $message = "Có lỗi xảy ra khi gửi yêu cầu mượn sách: " . $e->getMessage();
                $alert_type = "danger";
            }
            
            // Khôi phục autocommit
            mysqli_autocommit($connection, true);
        } else {
            $message = "Xin lỗi, sách \"" . $book['book_name'] . "\" hiện đã hết!";
            $alert_type = "warning";
        }
    } else {
        $message = "Không tìm thấy sách với ID: $book_id!";
        $alert_type = "danger";
    }
}

// Xử lý tìm kiếm
$query_string = isset($_GET['query']) ? trim($_GET['query']) : '';
$where_clause = "WHERE book_quantity > 0";

if($query_string != '') {
    $query_string_escaped = mysqli_real_escape_string($connection, $query_string);
    $where_clause .= " AND (book_name LIKE '%$query_string_escaped%' OR book_author LIKE '%$query_string_escaped%')";
}

$query = "SELECT * FROM books $where_clause ORDER BY book_name ASC";
$query_run = mysqli_query($connection, $query);
$books_to_borrow = [];
if (mysqli_num_rows($query_run) > 0) {
    while ($row = mysqli_fetch_assoc($query_run)) {
        $books_to_borrow[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mượn sách - LMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f2f2f2;
        }

        .search-form {
			max-width: 700px;
			flex: 1;
			margin: 0 auto;

            padding: 12px;
            border-radius: 20px;
			margin-bottom: 30px;
        }

		.search-form .btn {
    		border-radius: 0 5px 5px 0;
    		height: 38px;
			background-color: #3f6680;
			color: white;
		}

		.search-form .btn:hover {
			background-color: rgb(154, 187, 246);
		}

		.search-form .form-control {
			border-radius: 5px 0 0 5px;
			width: 450px;
			border-right: none;
			height: 38px;
		}

        .header {
            background-color: #32434e;
            color: rgb(17, 17, 17);
            font-size: 25px;
            font-weight: bold;
            display: flex;                  
            justify-content: space-between; 
            align-items: center;           
            padding: 15px 30px;            
        }

		.hello {
			background-image: url('https://images.unsplash.com/photo-1553448540-fe069f7c95bf?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
  			background-size: cover 110%;        
			background-position: center 52.5%;
			filter: brightness(1.2);
			padding: 2rem;
			margin-bottom: 30px;
		}

        .nav-links a {
            margin: 0 10px;
            color: rgb(11, 11, 11);
            text-decoration: none;
            font-size: 18px;
        }
        .nav-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-links a:hover {
            text-decoration: underline;
			color: rgb(154, 187, 246) !important;
        }

		.dashboard-card {
			max-width: 700px;
			flex: 1;
			margin: 0 auto;
            background-color: #314b5c;
            padding: 0px;
            border-radius: 20px;
			color: white;
			font-size: 18px;
			overflow: hidden;
		}
		.dashboard-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0,0,0,0.15);
		}

		.card-header {
			background-color: #ffffff;
    		color: #2a3a44;
    		font-size: 22px;
    		font-weight: bold;
    		text-align: center;
    		padding: 15px;
		}

		.btn-success {
			background-color: rgb(207, 224, 253);
			border-radius: 10px;
			color:rgb(2, 62, 82)
		}
		.btn-success:hover {
			background-color: #63dcc1;
			border-radius: 10px;
			color:rgb(23, 23, 23)
		}
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <a href="user_dashboard.php" style="color: white">
                Hệ thống LMS  
                <img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
            </a> 
        </div>
        <div class="nav-links">
            <ul class="navbar-nav ml-auto flex-row ">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="bookDropdown" role="button" data-toggle="dropdown" style="color: rgb(205, 203, 203)">Quản lý sách</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="borrow_book.php">Mượn sách</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="return_book.php">Trả sách</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" style="color: rgb(205, 203, 203)">Hồ sơ của tôi</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="view_profile.php">Xem hồ sơ</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="edit_profile.php">Chỉnh sửa hồ sơ</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="change_password.php">Đổi mật khẩu</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php" style="color: rgb(205, 203, 203)">Đăng xuất</a>
				</li>
			</ul>
        </div>
    </div>

	<!-- Banner -->
	<div class="hello">
		<h1 class="text-center"> 
			<img src="https://cdn-icons-png.flaticon.com/128/5849/5849203.png" width="50">
			<b>Chào mừng bạn đến với Hệ thống LMS</b>
		</h1>
		<h5 class="text-center"><b><i>Tìm kiếm và quản lý sách dễ dàng hơn bao giờ hết</i></b></h5>
	</div>

    <!-- Tìm kiếm sách -->
    <div class="d-flex justify-content-center">

			<form class="search-form" action="search_book.php" method="GET">
				<div class="input-group">
					<input class="form-control" type="search" name="query" placeholder="Nhập tên sách hoặc tên tác giả..." required>
					<div class="input-group-append">
						<button class="btn btn-outline-success" type="submit">
                            <img src="https://cdn-icons-png.flaticon.com/128/8915/8915520.png" width="20" style="vertical-align: middle; margin-right: 5px;">
                            <b>Tìm kiếm</b></button>
					</div>
				</div>
			</form>          
    </div>

    <?php if($message != "") { ?>
        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
            <strong>
                <?php if($alert_type == 'success') echo '<i class="fas fa-check-circle"></i> Thành công!'; 
                    elseif($alert_type == 'danger') echo '<i class="fas fa-exclamation-triangle"></i> Lỗi!'; 
                    else echo '<i class="fas fa-info-circle"></i> Thông báo!'; ?>
            </strong> <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    <?php } ?>
    <?php if(mysqli_num_rows($query_run) > 0) { ?>

    <div class="container">
    <h4 class="text-center mb-3">
        <img src="https://cdn-icons-png.flaticon.com/128/10433/10433053.png" width="30" style="vertical-align: middle; margin-right: 5px;">
        <b>Danh sách có thể mượn</b>
        <?php if($query_string != '') { ?>
            <small class="text-muted">- Kết quả tìm kiếm: "<?php echo htmlspecialchars($query_string); ?>"</small>
        <?php } ?>
    </h4>
    
<style>
    .high-stock {
        background-color: #e9f7ef;
    }

    .medium-stock {
        background-color: #fff9e6;
    }

    .low-stock {
        background-color: #fdecea;
    }

    .quantity-badge {
        font-size: 14px;
        padding: 6px 12px;
    }

    .book-title, .book-author {
        font-weight: 500;
    }

    .btn-borrow {
        background-color: #4a8bc2;
        border: none;
        border-radius: 10px;
        padding: 6px 12px;
        color: white;
    }

    .btn-borrow:hover {
        background-color: #63a6e1;
    }
</style>

<?php if (!empty($books_to_borrow)): ?>
    <div class="table-responsive">
        <table class="table table-hover shadow rounded bg-white">
            <thead class="thead-dark text-center">
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tên sách</th>
                    <th scope="col">Tác giả</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; ?>
                <?php foreach ($books_to_borrow as $row): ?>
                    <?php
                        $quantity = $row['book_quantity'];
                        if ($quantity > 5) {
                            $rowClass = 'high-stock';
                        } elseif ($quantity >= 2) {
                            $rowClass = 'medium-stock';
                        } else {
                            $rowClass = 'low-stock';
                        }
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td class="text-center font-weight-bold"><?php echo $stt++; ?></td>
                        <td><div class="book-title"><?php echo htmlspecialchars($row['book_name']); ?></div></td>
                        <td><div class="book-author"><?php echo htmlspecialchars($row['book_author']); ?></div></td>
                        <td class="text-center">
                            <span class="badge badge-info badge-pill quantity-badge"><?php echo $quantity; ?></span>
                        </td>
                        <td class="text-center">
                            <form method="post" style="display: inline;" onsubmit="return confirmBorrow('<?php echo addslashes($row['book_name']); ?>')">
                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                <button type="submit" name="borrow_book" class="btn btn-sm btn-borrow" title="Gửi yêu cầu mượn sách">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-muted">Không có sách nào có sẵn để mượn.</p>
<?php endif; ?>

<script>
    function confirmBorrow(bookName) {
        return confirm("Bạn có chắc chắn muốn gửi yêu cầu mượn sách: " + bookName + " không?");
    }
</script>

</body>
</html>