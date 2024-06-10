<?php
// Start the session
session_start();

// Check if room number is provided
if (isset($_GET['roomnumber'])) {
    // Database connection
    $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $roomnumber = mysqli_real_escape_string($conn, $_GET['roomnumber']);

    // Delete the room from the database
    $sql = "DELETE FROM rooms WHERE roomnumber = '$roomnumber'";

    if ($conn->query($sql) === TRUE) {
        // Set success message in session
        $_SESSION['remove'] = "SUCCESSFULLY REMOVED";
		
		 // Redirect back to the original page
        header("Location: Rooms.php");
        exit();
    } else {
        echo "Error deleting room: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Room number not provided";
	 // Redirect back to the original page
        header("Location: Rooms.php");
        exit();
}

// Redirect back to the original page
header("Location: Rooms.php");
exit();
?>