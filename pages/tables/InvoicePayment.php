<?php

require("../../Connection.php");
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST['customerName'];
    $roomNumber = $_POST['roomNumber'];
    $roomPrice = $_POST['roomPrice'];
    $paymentAmount = $_POST['paymentAmount'];

    // Check if any field is empty
    if (!empty($customerName) && !empty($roomNumber) && !empty($roomPrice) && !empty($paymentAmount)) {
        // Calculate the payment status and new balance
        if ($roomPrice == $paymentAmount) {
            $paymentStatus = "Fully Paid";
            $newBalance = 0;
        } else {
            $paymentStatus = "Partially Paid";
            $newBalance = $roomPrice - $paymentAmount;
        }

        // Insert data into the invoice table
        $stmt = $conn->prepare("INSERT INTO invoice (name, roomnumber, paymentprice, paymentstatus, paymentdate, balance) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("siisi", $customerName, $roomNumber, $paymentAmount, $paymentStatus, $newBalance);

        if ($stmt->execute()) {
            $_SESSION['created'] = "SUCCESSFULLY PAID";
            header("Location: InvoiceList.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

// Close the database connection
$conn->close();
?>
