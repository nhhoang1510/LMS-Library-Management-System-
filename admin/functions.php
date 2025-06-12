<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Tạo kết nối
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if (!$connection) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Thiết lập charset UTF-8
mysqli_set_charset($connection, "utf8");

// Hàm đếm số người dùng
function get_user_count() {
    global $connection;
    $query = "SELECT COUNT(*) as count FROM users";
    $result = mysqli_query($connection, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    return 0;
}

// Hàm đếm số sách
function get_book_count() {
    global $connection;
    $query = "SELECT COUNT(*) as count FROM books";
    $result = mysqli_query($connection, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    return 0;
}

// Hàm đếm số sách đang được mượn
function get_issue_book_count() {
    global $connection;
    $query = "SELECT COUNT(*) as count FROM issued_books WHERE status = 'Chưa trả'";
    $result = mysqli_query($connection, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    return 0;
}

// Hàm đếm số yêu cầu chờ xét duyệt
function get_pending_request_count() {
    global $connection;
    $query = "SELECT COUNT(*) as count FROM borrow_requests WHERE status = 'pending'";
    $result = mysqli_query($connection, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    return 0;
}

// Hàm lấy thông tin người dùng theo ID
function get_user_by_id($user_id) {
    global $connection;
    $query = "SELECT * FROM users WHERE id = " . intval($user_id);
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Hàm lấy thông tin sách theo ID
function get_book_by_id($book_id) {
    global $connection;
    $query = "SELECT * FROM books WHERE book_id = " . intval($book_id);
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Hàm kiểm tra admin đã đăng nhập
function check_admin_login() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }
}

// Hàm kiểm tra user đã đăng nhập
function check_user_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: user_login.php");
        exit();
    }
}

// Hàm làm sạch dữ liệu đầu vào
function clean_input($data) {
    global $connection;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($connection, $data);
}

// Hàm kiểm tra email hợp lệ
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hàm tạo thông báo
function create_message($type, $message) {
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>";
}
?>