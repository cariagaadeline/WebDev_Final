<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data (you can add more validation as needed)
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Database connection
    $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL statement to update tenant information
    $sql = "UPDATE user SET 
            name = '$name', 
            gender = '$gender', 
            age = '$age', 
            address = '$address', 
            course = '$course', 
            year = '$year', 
            section = '$section' 
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        // Set success message in session
        $_SESSION['update'] = "SUCCESSFULLY UPDATE";

        // Redirect back to the original page
        header("Location: Tenants.php");
        exit;
    } else {
        // Set error message in session
        $_SESSION['update'] = "Error updating tenant information: " . $conn->error;

        // Redirect back to the original page
        header("Location: Tenants.php");
        exit;
    }

    // Close the database connection
    $conn->close();
}
?>
