<?php
session_start();

// Kết nối database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Lỗi kết nối database: " . $e->getMessage());
}

// Thêm cột status vào bảng user_request nếu chưa có
try {
    $pdo->exec("ALTER TABLE user_request ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
} catch(PDOException $e) {
    // Cột đã tồn tại, bỏ qua lỗi
}

// Đảm bảo cột status trong issued_books có thể chứa tiếng Việt
try {
    $pdo->exec("ALTER TABLE issued_books MODIFY COLUMN status VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
} catch(PDOException $e) {
    // Bỏ qua lỗi nếu cột đã tồn tại
}

// Đảm bảo cột return_date có thể chứa giá trị NULL
try {
    $pdo->exec("ALTER TABLE issued_books MODIFY COLUMN return_date DATE NULL");
} catch(PDOException $e) {
    // Bỏ qua lỗi nếu cột đã được cấu hình
}

// Xử lý phê duyệt/từ chối yêu cầu
if (isset($_POST['action']) && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    
    try {
        // Bắt đầu transaction
        $pdo->beginTransaction();
        
        if ($action === 'approve') {
            // Lấy thông tin yêu cầu
            $stmt = $pdo->prepare("SELECT * FROM user_request WHERE request_id = ?");
            $stmt->execute([$request_id]);
            $request_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($request_data) {
                // Phê duyệt yêu cầu
                $stmt = $pdo->prepare("UPDATE user_request SET status = 'approved' WHERE request_id = ?");
                $stmt->execute([$request_id]);
                
                // Lấy thông tin sách từ bảng books
                $book_stmt = $pdo->prepare("SELECT book_name, book_author FROM books WHERE book_id = ?");
                $book_stmt->execute([$request_data['book_id']]);
                $book_info = $book_stmt->fetch(PDO::FETCH_ASSOC);
                
                $book_name = $book_info ? $book_info['book_name'] : 'N/A';
                $book_author = $book_info ? $book_info['book_author'] : 'N/A';
                
                // Thêm vào bảng issued_books với trạng thái "Chưa trả"
                $insert_stmt = $pdo->prepare("INSERT INTO issued_books (book_name, book_author, student_id, issue_date, return_date, status) VALUES (?, ?, ?, ?, ?, ?)");
                
                $insert_stmt->execute([
                    $book_name,
                    $book_author, 
                    $request_data['student_id'],
                    date('Y-m-d'), // Ngày hiện tại
                    $request_data['return_date'], // Ngày trả dự kiến từ yêu cầu
                    'Chưa trả'
                ]);
                
                $success_message = "Đã phê duyệt yêu cầu và thêm vào danh sách mượn sách!";
            }
            
        } elseif ($action === 'reject') {
            // Lấy thông tin yêu cầu
            $stmt = $pdo->prepare("SELECT * FROM user_request WHERE request_id = ?");
            $stmt->execute([$request_id]);
            $request_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($request_data) {
                // Từ chối yêu cầu - cập nhật status trong user_request
                $stmt = $pdo->prepare("UPDATE user_request SET status = 'rejected' WHERE request_id = ?");
                $stmt->execute([$request_id]);
                
                // Lấy thông tin sách từ bảng books
                $book_stmt = $pdo->prepare("SELECT book_name, book_author FROM books WHERE book_id = ?");
                $book_stmt->execute([$request_data['book_id']]);
                $book_info = $book_stmt->fetch(PDO::FETCH_ASSOC);
                
                $book_name = $book_info ? $book_info['book_name'] : 'N/A';
                $book_author = $book_info ? $book_info['book_author'] : 'N/A';
                
                // Thêm vào bảng issued_books với trạng thái "Từ chối"
                $insert_stmt = $pdo->prepare("INSERT INTO issued_books (book_name, book_author, student_id, issue_date, return_date, status) VALUES (?, ?, ?, ?, ?, ?)");
                
                $insert_stmt->execute([
                    $book_name,
                    $book_author, 
                    $request_data['student_id'],
                    date('Y-m-d'), // Ngày hiện tại
                    null, // Không có ngày trả khi từ chối
                    'Từ chối'
                ]);
                
                $success_message = "Đã từ chối yêu cầu và cập nhật trạng thái!";
            }
        }
        
        // Commit transaction
        $pdo->commit();
        
    } catch(PDOException $e) {
        // Rollback nếu có lỗi
        $pdo->rollBack();
        $error_message = "Lỗi xử lý yêu cầu: " . $e->getMessage();
    }
}

// Lấy danh sách yêu cầu với thông tin chi tiết
$sql = "SELECT 
    ur.request_id,
    ur.student_id,
    ur.book_id,
    ur.request_date,
    ur.return_date,
    ur.action,
    COALESCE(ur.status, 'pending') as status,
    u.name as student_name,
    b.book_name,
    b.book_author
FROM user_request ur
LEFT JOIN users u ON ur.student_id = u.id
LEFT JOIN books b ON ur.book_id = b.book_id
ORDER BY ur.request_date DESC";

try {
    $stmt = $pdo->query($sql);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Lỗi truy vấn: " . $e->getMessage();
    $requests = [];
}

// Hàm format ngày (chỉ hiển thị ngày, không có giờ)
function formatDate($date) {
    if (!$date || $date == '0') return 'N/A';
    if (is_numeric($date)) {
        return $date . ' ngày';
    }
    return date('d/m/Y', strtotime($date));
}

// Hàm format ngày giờ
function formatDateTime($datetime) {
    if (!$datetime) return 'N/A';
    return date('d/m/Y H:i', strtotime($datetime));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý yêu cầu sách - LMS</title>
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
        .request-table {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow-x: auto; /* For responsiveness */
        }
        .table thead th {
            background-color: #32434e;
            color: white;
            border-bottom: none;
            font-weight: bold;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background-color: #f2f2f2;
        }
        .table td, .table th {
            vertical-align: middle; 
            text-align: center;
        }
        .status-badge {
            padding: .4em .6em;
            border-radius: .25rem;
            font-weight: bold;
            font-size: 0.8em;
        }
        .status-badge.pending {
            background-color: #ffc107;
            color: #856404;
        }
        .status-badge.approved {
            background-color: #28a745;
            color: white;
        }
        .status-badge.rejected {
            background-color: #dc3545;
            color: white;
        }
        .btn-action {
            padding: 5px 12px;
            font-size: 0.9em;
            border-radius: 5px;
            margin: 2px;
        }
        .btn-action:hover {
            opacity: 0.9;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        .btn-approve:hover {
            background-color: #218838;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        .btn-reject:hover {
            background-color: #c82333;
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
            <b>Quản lý yêu cầu mượn/trả sách</b>
        </h1>
        <h5 class="text-center"> <b><i>Kiểm tra và xử lý các yêu cầu mượn trả từ người dùng</i></b></h5>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="request-table">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID Yêu cầu</th>
                            <th>Tên người dùng</th>
                            <th>Tên sách</th>
                            <th>Ngày yêu cầu</th>
                            <th>Ngày trả</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Hàm để format ngày
                            function formatDate($date) {
                                return date("d/m/Y", strtotime($date));
                            }

                            $query = "SELECT ur.request_id, u.name as user_name, b.book_name, ur.request_date, ur.return_date, ur.status FROM user_request ur JOIN users u ON ur.user_id = u.id JOIN books b ON ur.book_id = b.book_id ORDER BY ur.request_date DESC";
                            $query_run = mysqli_query($connection, $query);
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                                <tr>
                                    <td><?php echo $row['request_id']; ?></td>
                                    <td><?php echo $row['user_name']; ?></td>
                                    <td><?php echo $row['book_name']; ?></td>
                                    <td><?php echo formatDate($row['request_date']); ?></td>
                                    <td><?php echo formatDate($row['return_date']); ?></td>
                                    <td>
                                        <?php
                                            $status_class = '';
                                            switch($row['status']) {
                                                case 'Đang chờ duyệt':
                                                    $status_class = 'pending';
                                                    break;
                                                case 'Đã duyệt':
                                                    $status_class = 'approved';
                                                    break;
                                                case 'Đã từ chối':
                                                    $status_class = 'rejected';
                                                    break;
                                                default:
                                                    $status_class = '';
                                            }
                                        ?>
                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($row['status'] == 'Đang chờ duyệt'): ?>
                                            <a href="approve_request.php?id=<?php echo $row['request_id']; ?>" class="btn btn-success btn-action btn-approve">Duyệt</a>
                                            <a href="reject_request.php?id=<?php echo $row['request_id']; ?>" class="btn btn-danger btn-action btn-reject">Từ chối</a>
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
    </div>
</body>
</html>