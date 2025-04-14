const mysql = require('mysql2');

// Tạo kết nối đến MySQL
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',          // đổi nếu bạn dùng user khác
    password: '',          // nhập mật khẩu nếu có
    database: 'library_db' // database bạn vừa tạo
});

// Kết nối đến MySQL
connection.connect((err) => {
    if (err) {
        console.error('Kết nối thất bại: ', err);
        return;
    }
    console.log('✅ Kết nối MySQL thành công!');
});

module.exports = connection;
