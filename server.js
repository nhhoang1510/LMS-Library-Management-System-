const express = require('express');
const mysql = require('mysql2');
const bcrypt = require('bcrypt');
const path = require('path');
const app = express();

// Create a connection to MySQL
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root', // Replace with your MySQL username
  password: '123456', // Replace with your MySQL password
  database: 'library_system'
});

// Connect to MySQL
connection.connect((err) => {
  if (err) {
    console.error('Error connecting to MySQL:', err);
    return;
  }
  console.log('Connected to MySQL database');
});

// Serve static files from the 'public' directory
app.use(express.static(path.join(__dirname, 'public')));

// Middleware to parse JSON requests
app.use(express.json());

// API endpoint for database connection
app.post('/api/connect', (req, res) => {
  res.json({ success: true, message: 'Connected to database' });
});

// API endpoint for login
app.post('/api/login', (req, res) => {
  const { username, password, type } = req.body;

  // Query to get user by username and type
  const query = 'SELECT * FROM users WHERE username = ? AND type = ?';
  connection.query(query, [username, type], async (err, results) => {
    if (err) {
      console.error('Error during login:', err);
      return res.json({ success: false, message: 'Đăng nhập thất bại' });
    }

    if (results.length === 0) {
      return res.json({ success: false, message: 'Tên đăng nhập hoặc mật khẩu không đúng' });
    }

    const user = results[0];
    // Compare the provided password with the hashed password
    const match = await bcrypt.compare(password, user.password);
    if (match) {
      res.json({ success: true, message: 'Đăng nhập thành công', token: 'user_token' });
    } else {
      res.json({ success: false, message: 'Tên đăng nhập hoặc mật khẩu không đúng' });
    }
  });
});

// API endpoint for registration
app.post('/api/register', async (req, res) => {
  const { username, email, password } = req.body;

  // Hash the password before storing it
  const saltRounds = 10;
  const hashedPassword = await bcrypt.hash(password, saltRounds);

  // Query to insert a new user
  const query = 'INSERT INTO users (username, email, password, type) VALUES (?, ?, ?, ?)';
  connection.query(query, [username, email, hashedPassword, 'user'], (err, results) => {
    if (err) {
      console.error('Error during registration:', err);
      if (err.code === 'ER_DUP_ENTRY') {
        return res.json({ success: false, message: 'Tên đăng nhập hoặc email đã tồn tại' });
      }
      return res.json({ success: false, message: 'Đăng ký thất bại' });
    }

    res.json({ success: true, message: 'Đăng ký thành công' });
  });
});

// Serve index.html for the root route
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Start the server
const PORT = 3000;
app.get('/test', (req, res) => {
    res.send('Server is working!');
  });
app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});