<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng xuất - LMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .logout-container {
            background-color: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .logout-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        .logout-title {
            color: #32434e;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .logout-message {
            color: #666;
            margin-bottom: 25px;
        }
        .btn-login {
            background-color: #32434e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #1a252b;
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="logout-container">
        <img src="https://cdn-icons-png.flaticon.com/128/1828/1828471.png" alt="Logout" class="logout-icon">
        <h1 class="logout-title">Đăng xuất thành công</h1>
        <p class="logout-message">Bạn đã đăng xuất khỏi hệ thống LMS. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
        <a href="../index.php" class="btn-login">Đăng nhập lại</a>
    </div>

    <?php
    session_start();
    session_destroy();
    ?>
</body>
</html>