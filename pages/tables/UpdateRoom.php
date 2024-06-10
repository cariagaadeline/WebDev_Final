<?php
// Start the session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $roomnumber = $_POST['roomnumber'];
    $tenantmax = $_POST['tenantmax'];
    $price = $_POST['price'];

    // Update room details in the database
    $sql = "UPDATE rooms SET tenantmax = '$tenantmax', price = '$price' WHERE roomnumber = '$roomnumber'";

    if ($conn->query($sql) === TRUE) {
        // Set success message in session
        $_SESSION['update'] = "SUCCESSFULLY UPDATE";

        // Redirect back to the original page
        header("Location: Rooms.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to the original page if form is not submitted
    header("Location: Rooms.php");
    exit();
}
?>