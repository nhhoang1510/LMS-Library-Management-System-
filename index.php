<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập LMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f2f2f2;
        }
		/* Bảng đăng nhập */
        .login {
            margin-top: 50px;
			width: 100%;
            max-width: 480px;
            background-color: #fffdfd;
            padding: 60px;
            border-radius: 20px;
        }

		/* Màu của header */
        .header {
            background-color: #32434e;
            color: white;
            font-size: 25px;
            font-weight: bold;
            display: flex;                  
            justify-content: space-between; 
            align-items: center;           
            padding: 15px 30px;            
        }

        /* Chữ trong phần header */
        .nav-links a {
            margin: 0 10px;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        .nav-links {
            display: flex;
            gap: 10px;
            align-items: center;
        } 

        /* Nút đăng nhập ở header */
        .custom-button-login-header {
            padding: 8px 16px;
            background-color: #377cb0; 
            color: white;              
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .custom-button-login-header:hover {
            background-color: #93bee4;
            color: white;
        }

        /* Nút đăng ký ở header */
        .custom-button-header {
            padding: 8px 16px;
            background-color: #ffffff; 
            color: rgb(3, 0, 8);              
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .custom-button-header:hover {
            background-color: #96b0ff; 
            color: white;
        }

        /* Nút đăng nhập */
        .custom-button {
            padding: 8px 16px;
            background-color: #377cb0; 
            color: white;              
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .custom-button:hover {
            background-color: #a562e9; 
        }

        /* Gạch dưới chữ Admin */
        .nav-links a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <a href="index.php" style="color: white">
                Hệ thống LMS  
                <img src="https://cdn-icons-png.flaticon.com/128/14488/14488111.png" width="40" style="vertical-align: middle; margin-left: 10px;">
            </a> 
        </div>
        <div class="nav-links">
            <a href="index.php"><button class="custom-button-login-header"><b>Đăng nhập</b></button></a>
            <a href="signup.php"><button class="custom-button-header"><b>Đăng ký</b></button></a>
            <a href="admin_login.php"><b>Admin</b></a>
        </div>
    </div>

    <!-- Đăng nhập người dùng -->
    <div class="d-flex justify-content-center">
        <div class="login card shadow">
            <h3 class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/128/7542/7542114.png" width="40">
                <b>Đăng nhập</b>
            </h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">
                        <img src="https://cdn-icons-png.flaticon.com/128/61/61205.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Tài khoản người dùng:
                    </label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email*">
                </div>
                <div class="form-group">
                    <label for="password">
                        <img src="https://cdn-icons-png.flaticon.com/128/483/483408.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Mật khẩu:
                    </label>
                    <input type="password" name="password" class="form-control" id="password" required placeholder="Nhập mật khẩu*">
                </div>
                <div style="text-align: center;">
                    <button class="custom-button" name="login"><b>Đăng nhập</b></button>
                </div>
                <div class="form-group text-center mt-3">
                    Nếu bạn chưa có tài khoản, vui lòng <a href="signup.php">Đăng ký</a>
                </div>
            </form>
            <?php 
                if (isset($_POST['email']) && isset($_POST['password'])) {
                    $connection = mysqli_connect("localhost", "root", "", "lms");

                    if (!$connection) {
                        die("Kết nối thất bại: " . mysqli_connect_error());
                    }

                    $email = mysqli_real_escape_string($connection, $_POST['email']);
                    $password = $_POST['password'];

                    $query = "SELECT * FROM users WHERE email = '$email'";
                    $result = mysqli_query($connection, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        if (password_verify($password, $row['password'])) {
                            $_SESSION['id'] = $row['id'];
                            $_SESSION['email'] = $row['email'];
                            $_SESSION['name'] = $row['name'];
                            header("Location: user_dashboard.php");
                            exit();
                        } else {
                            echo "<div class='alert alert-danger text-center mt-3'>Mật khẩu không đúng</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger text-center mt-3'>Tài khoản không tồn tại</div>";
                    }
                }
            ?>
        </div>
    </div>
    <div style="height: 50px;"></div>
</body>
</html>
