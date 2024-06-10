<?php
require("../../Connection.php");
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $roomNumber = $_POST['RoomNum'];
    $maxTenant = $_POST['MaxTenant'];
    $monthlyPrice = $_POST['MonthlyPrice'];

    // Set default room status
    $roomStatus = 'available';

    //float format
    echo number_format($monthlyPrice, 2);

    // Insert data into the database
    $sql = "INSERT INTO rooms (roomnumber, tenantmax, price, roomstatus) VALUES (?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("iiis", $roomNumber, $maxTenant, $monthlyPrice, $roomStatus);

    if ($stmt->execute()) {
        // Redirect to the form with a success message
		$_SESSION['created'] = 'NEW ROOM SUCCESSFULLY CREATED';
        header("Location: Rooms.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
