<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("Location: user_login.php");
    exit();
}
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");

$message = "";
$alert_type = "";

if(isset($_POST['borrow_book'])) {
    $book_id = $_POST['book_id'];
    $student_id = $_SESSION['id'];
    
    $book_query = "SELECT * FROM books WHERE book_id = '$book_id'";
    $book_result = mysqli_query($connection, $book_query);
    
    if(mysqli_num_rows($book_result) > 0) {
        $book = mysqli_fetch_assoc($book_result);
        
        if($book['book_quantity'] > 0) {
            $issue_date = date('Y-m-d');
            $return_date = 'NULL'; 
            
            $insert_query = "INSERT INTO issued_books (book_name, book_author, student_id, status, issue_date, return_date) 
                           VALUES ('" . mysqli_real_escape_string($connection, $book['book_name']) . "', 
                                   '" . mysqli_real_escape_string($connection, $book['book_author']) . "', 
                                   '$student_id', 'Chưa trả', '$issue_date', '$return_date')";
                                   
            if(mysqli_query($connection, $insert_query)) {
                $new_quantity = $book['book_quantity'] - 1;
                $update_query = "UPDATE books SET book_quantity = '$new_quantity' WHERE book_id = '$book_id'";
                
                if(mysqli_query($connection, $update_query)) {
                    $message = "Mượn sách thành công! ";
                    $alert_type = "success";
                } else {
                    $message = "Có lỗi xảy ra khi cập nhật số lượng sách!";
                    $alert_type = "danger";
                }
            } else {
                $message = "Có lỗi xảy ra khi mượn sách!";
                $alert_type = "danger";
            }
        } else {
            $message = "Xin lỗi, sách này hiện đã hết!";
            $alert_type = "warning";
        }
    } else {
        $message = "Không tìm thấy sách!";
        $alert_type = "danger";
    }
}

// Lấy danh sách sách có sẵn để hiển thị
$query_string = isset($_GET['query']) ? trim($_GET['query']) : '';
if($query_string != '') {
    $query_string_escaped = mysqli_real_escape_string($connection, $query_string);
    $query = "SELECT * FROM books WHERE (book_name LIKE '%$query_string_escaped%' OR book_author LIKE '%$query_string_escaped%') AND book_quantity > 0 ORDER BY book_name ASC";
} else {
    $query = "SELECT * FROM books WHERE book_quantity > 0 ORDER BY book_name ASC";
}
$query_run = mysqli_query($connection, $query);

// Khởi tạo các biến thống kê
$total_available = 0;
$high_stock = 0;
$medium_stock = 0;
$low_stock = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mượn sách - LMS</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <style>
        .high-stock {
            background-color: #e8f5e8 !important;
            color: #2e7d32;
        }
        .medium-stock {
            background-color: #fff3e0 !important;
            color: #ef6c00;
        }
        .low-stock {
            background-color: #ffebee !important;
            color: #c62828;
        }
        .search-form {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
        }
        .borrowing-info {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
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
                <h4 class="text-center mb-4">Mượn sách</h4>
                <div class="search-form mb-4">
                    <form method="GET" action="borrow_book.php">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="query" 
                                       placeholder="Tìm kiếm theo tên sách hoặc tác giả..." 
                                       value="<?php echo htmlspecialchars($query_string); ?>">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-block" type="submit">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>

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
                    <div class="row mb-4">
                        <?php 
                            mysqli_data_seek($query_run, 0); 
                            
                            while($stat_row = mysqli_fetch_assoc($query_run)) {
                                $total_available++;
                                if($stat_row['book_quantity'] > 5) {
                                    $high_stock++;
                                } elseif($stat_row['book_quantity'] > 2) {
                                    $medium_stock++;
                                } else {
                                    $low_stock++;
                                }
                            }
                            mysqli_data_seek($query_run, 0); 
                        ?>
                        
                        <div class="col-md-3">
                            <div class="card text-center bg-primary text-white">
                                <div class="card-body">
                                    <h5><?php echo $total_available; ?></h5>
                                    <p>Tổng đầu sách có sẵn</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-success text-white">
                                <div class="card-body">
                                    <h5><?php echo $high_stock; ?></h5>
                                    <p>Số lượng nhiều</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-warning text-white">
                                <div class="card-body">
                                    <h5><?php echo $medium_stock; ?></h5>
                                    <p>Số lượng vừa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-danger text-white">
                                <div class="card-body">
                                    <h5><?php echo $low_stock; ?></h5>
                                    <p>Số lượng ít</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Danh sách sách có thể mượn</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên sách</th>
                                            <th>Tác giả</th>
                                            <th>Số lượng có sẵn</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $stt = 1;
                                        while($row = mysqli_fetch_assoc($query_run)) {
                                            $stock_class = '';
                                            if($row['book_quantity'] > 5) {
                                                $stock_class = 'high-stock';
                                            } elseif($row['book_quantity'] > 2) {
                                                $stock_class = 'medium-stock';
                                            } else {
                                                $stock_class = 'low-stock';
                                            }
                                        ?>
                                        <tr class="<?php echo $stock_class; ?>">
                                            <td><?php echo $stt++; ?></td>
                                            <td><strong><?php echo htmlspecialchars($row['book_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['book_author']); ?></td>
                                            <td>
                                                <span class="badge badge-info"><?php echo $row['book_quantity']; ?></span>
                                            </td>
                                            <td>
                                                <?php if($row['book_quantity'] > 5) { ?>
                                                    <span class="badge badge-success">Còn nhiều</span>
                                                <?php } elseif($row['book_quantity'] > 2) { ?>
                                                    <span class="badge badge-warning">Còn ít</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger">Sắp hết</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                    <button type="submit" name="borrow_book" 
                                                            class="btn btn-sm btn-primary"
                                                            onclick="return confirm('Bạn có chắc chắn muốn mượn sách này?')">
                                                        <i class="fas fa-book"></i> Mượn
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
                    <div class="alert alert-info text-center">
                        <h5><i class="fas fa-info-circle"></i> Không có sách nào</h5>
                        <p class="mb-0">
                            <?php if($query_string != '') { ?>
                                Không tìm thấy sách nào phù hợp với từ khóa "<strong><?php echo htmlspecialchars($query_string); ?></strong>".
                                <br><a href="borrow_book.php" class="btn btn-primary mt-2">Xem tất cả sách</a>
                            <?php } else { ?>
                                Hiện tại không có sách nào có sẵn để mượn.
                            <?php } ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>