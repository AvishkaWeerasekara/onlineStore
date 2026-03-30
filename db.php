<?php
// =============================================
//  db.php — Database Connection
//  Change the values below to match your setup
// =============================================

$host     = "localhost";
$username = "root";       // your MySQL username
$password = "";           // your MySQL password (blank for XAMPP default)
$database = "shopnest";

// Connect to MySQL
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection worked
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
