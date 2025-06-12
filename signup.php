<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản LMS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f2f2f2;
        }

        .signup {
            margin-top: 50px;
            width: 100%;
            max-width: 480px;
            background-color: #fffdfd;
            padding: 60px;
            border-radius: 20px;
        }

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

        .nav-links a:hover {
            text-decoration: underline;
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

    <!-- Đăng kí tài khoản -->
    <div class="d-flex justify-content-center">
        <div class="signup card shadow">
            <h3 class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/128/748/748137.png" width="35">
                <b>Đăng ký</b>
            </h3>
            <form method="POST" action="signup.php">
                <div class="form-group">
                    <label for="name">
                        <img src="https://cdn-icons-png.flaticon.com/128/3596/3596097.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Họ và tên:
                    </label>
                    <input type="text" name="name" class="form-control" id="text" placeholder="Nhập họ và tên*">
                </div>
                <div class="form-group">
                    <label for="email">
                        <img src="https://cdn-icons-png.flaticon.com/128/2669/2669570.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Email:
                    </label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email*">
                </div>
                <div class="form-group">
                    <label for="password">
                        <img src="https://cdn-icons-png.flaticon.com/128/483/483408.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Mật khẩu:
                    </label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Nhập mật khẩu*">
                </div>
                <div class="form-group">
                    <label for="mobile">
                        <img src="https://cdn-icons-png.flaticon.com/128/18472/18472845.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Số điện thoại:
                    </label>
                    <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Nhập số điện thoại*">
                </div>
                <div class="form-group">
                    <label for="address">
                        <img src="https://cdn-icons-png.flaticon.com/128/535/535188.png" width="20" style="margin-right: 5px; vertical-align: text-bottom;">
                        Địa chỉ:
                    </label>
                    <textarea name="address" class="form-control" id="address" placeholder="Nhập địa chỉ*"></textarea>
                </div>

                <div style="text-align: center;">
                    <button class="custom-button" name="login"><b>Đăng ký</b></button>
                </div>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $connection = mysqli_connect("localhost", "root", "");
                $db = mysqli_select_db($connection, "lms");
                $query = "INSERT INTO users VALUES('', '$_POST[name]', '$_POST[email]', '$_POST[password]', '$_POST[mobile]', '$_POST[address]')";
                $query_run = mysqli_query($connection, $query);

                if ($query_run) {
                    echo '<script type="text/javascript">
                            alert("Bạn đã đăng ký thành công!");
                            window.location.href = "index.php";
                          </script>';
                    exit();
                }
            }
            ?>
        </div>
    </div>
    <div style="height: 100px;"></div>
</body>
</html>
