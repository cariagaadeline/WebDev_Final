<?php
require('../../Connection.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $paymentAmount = mysqli_real_escape_string($conn, $_POST['modalPaymentAmount']);
    $paymentDate = date('Y-m-d'); // Get today's date

    echo number_format($balance, 2);
    echo number_format($paymentAmount, 2);
    echo number_format($newPaymentPrice, $oldPaymentPrice, 2);
    // Fetch current balance and payment price
    $query = "SELECT balance, paymentprice FROM invoice WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentBalance = $row['balance'];
        $oldPaymentPrice = $row['paymentprice'];

        // Calculate new payment price
        $newPaymentPrice = $oldPaymentPrice + $paymentAmount;

        // Calculate new balance
        $newBalance = $currentBalance - $paymentAmount;

        // Update the invoice table
        $updateQuery = "UPDATE invoice SET balance = '$newBalance', paymentprice = '$newPaymentPrice', paymentdate = '$paymentDate', paymentstatus = '";

		// Determine payment status based on new balance
		if ($newBalance <= 0) {
			$updateQuery .= "Fully Paid'";
		} else {
			$updateQuery .= "Partially Paid'";
		}

        $updateQuery .= " WHERE id = '$id'";
        
        if ($conn->query($updateQuery) === TRUE) {
            $_SESSION['update'] = 'SUCCESSFULLY PAID BALANCE';
            header("Location: InvoiceList.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No record found for the given ID";
    }

    // Close the database connection
    $conn->close();
}

?>
