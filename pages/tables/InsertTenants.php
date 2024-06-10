<?php
require('../../Connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $roomNumber = mysqli_real_escape_string($conn, $_POST['roomNumber']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $balance = mysqli_real_escape_string($conn, $_POST['balance']);
    $roomPrice = mysqli_real_escape_string($conn, $_POST['roomPrice']);
    $downPayment = mysqli_real_escape_string($conn, $_POST['downPayment']);

    // Determine payment status
    if ($downPayment >= $roomPrice) {
        $paymentStatus = 'Fully Paid';
    } else {
        $paymentStatus = 'Partially Paid';
    }

    // Current date and time
    $paymentDate = date('Y-m-d H:i:s');

    // Insert data into the user table
    $userInsertQuery = "INSERT INTO user (roomnum, name, age, gender, address, course, year, section, balance, paymentstatus, paymentprice, roomprice, paymentdate, startingdate) 
                        VALUES ('$roomNumber', '$name', '$age', '$gender', '$address', '$course', '$year', '$section', '$balance', '$paymentStatus', '$downPayment', '$roomPrice', '$paymentDate', '$paymentDate')";

    if ($conn->query($userInsertQuery) === TRUE) {
        // Insert data into the invoice table
        $invoiceInsertQuery = "INSERT INTO invoice (roomnumber, name, balance, paymentprice, paymentstatus, paymentdate) 
                               VALUES ('$roomNumber', '$name', '$balance', '$downPayment', '$paymentStatus', '$paymentDate')";

        if ($conn->query($invoiceInsertQuery) === TRUE) {
            // Update tenantoccupied in rooms table
            $updateRoomQuery = "UPDATE rooms SET tenantoccupied = tenantoccupied + 1 WHERE roomnumber = '$roomNumber'";
            if ($conn->query($updateRoomQuery) === TRUE) {
                // Check if the tenantoccupied has reached tenantmax
                $checkOccupiedQuery = "SELECT tenantoccupied, tenantmax FROM rooms WHERE roomnumber = '$roomNumber'";
                $result = $conn->query($checkOccupiedQuery);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tenantOccupied = $row['tenantoccupied'];
                    $tenantMax = $row['tenantmax'];
                    if ($tenantOccupied >= $tenantMax) {
                        // Update roomstatus to "Unavailable"
                        $updateRoomStatusQuery = "UPDATE rooms SET roomstatus = 'Unavailable' WHERE roomnumber = '$roomNumber'";
                        if ($conn->query($updateRoomStatusQuery) === TRUE) {
                            $_SESSION['unavailable'] = "UNAVAILBLE ROOM.";
                        } else {
                            echo "Error updating room status: " . $conn->error;
                        }
                    }
                }
				$_SESSION['created'] = "SUCCESSFULLY OCCUPIED.";
                header("Location: Tenants.php");
                exit();
            } else {
                echo "Error updating room occupancy: " . $conn->error;
            }
        } else {
            echo "Error inserting into invoice table: " . $conn->error;
        }
    } else {
        echo "Error inserting into user table: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
