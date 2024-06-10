<?php
// Start the session
session_start();

// Check if the user ID is provided via GET method
if (isset($_GET['id'])) {
    // Sanitize the user ID to prevent SQL injection
    $id = $_GET['id'];

    // Database connection
    $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the room number of the user to be removed
    $sqlFetchRoom = "SELECT roomnum FROM user WHERE id = ?";
    $stmtFetchRoom = $conn->prepare($sqlFetchRoom);
    $stmtFetchRoom->bind_param("i", $id);
    $stmtFetchRoom->execute();
    $stmtFetchRoom->bind_result($roomNumber);
    $stmtFetchRoom->fetch();
    $stmtFetchRoom->close();

    // Prepare and bind SQL statement to delete the user
    $sqlDeleteUser = "DELETE FROM user WHERE id = ?";
    $stmtDeleteUser = $conn->prepare($sqlDeleteUser);
    $stmtDeleteUser->bind_param("i", $id);

    if ($stmtDeleteUser->execute()) {
        // Increment the tenantoccupied count for the corresponding room
        $sqlUpdateRoom = "UPDATE rooms SET tenantoccupied = tenantoccupied - 1 WHERE roomnumber = ?";
        $stmtUpdateRoom = $conn->prepare($sqlUpdateRoom);
        $stmtUpdateRoom->bind_param("s", $roomNumber);
        $stmtUpdateRoom->execute();
        $stmtUpdateRoom->close();

        // Set success message in session
        $_SESSION['remove'] = "SUCCESSFULLY REMOVED.";
		// Redirect back to the original page
        header("Location: Tenants.php");
        exit;
    } else {
        // Set error message in session
        $_SESSION['remove'] = "Error deleting user: " . $conn->error;
    }

    // Close the prepared statement
    $stmtDeleteUser->close();

    // Close the database connection
    $conn->close();

    // Redirect back to the original page
    header("Location: Tenants.php");
    exit;
} else {
    // If no user ID is provided, redirect back to the original page
    header("Location: Tenants.php");
    exit;
}

?>
