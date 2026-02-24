<?php
// ============================================
// TCH Medical Center - Database Configuration
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tch_hospital_db');

// Create MySQLi connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    $error_msg = $conn->connect_error;
    die("
    <div style='
        font-family: Arial, sans-serif;
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 20px;
        margin: 20px;
        border-radius: 8px;
        text-align: center;
    '>
        <h3>&#9888; Database Connection Failed</h3>
        <p>Error: $error_msg</p>
        <p>Please make sure the database <strong>tch_hospital_db</strong> exists and XAMPP MySQL is running.</p>
        <p>Import <strong>hospital_db.sql</strong> via phpMyAdmin to set up the database.</p>
    </div>
    ");
}

// Set charset
$conn->set_charset("utf8");
?>
