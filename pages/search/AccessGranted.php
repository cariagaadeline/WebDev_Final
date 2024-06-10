<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Prepare and execute the SQL query
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if a matching record was found
    if ($result->num_rows > 0) {
        // Start session and redirect to index.php
        $_SESSION['username'] = $username;
        header("Location: ../../index.php");
        exit();
    } else {
        // Set error message and redirect back to login page
        $_SESSION['error'] = "Invalid Username Or Password.";
        header("Location: SignIn.php");
        exit();
    }
}

// Close the database connection
$conn->close();
?>
