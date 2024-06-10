<?php

// Database connection
$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer ID from POST request
$customerId = $_POST['customerId'];

// SQL query to fetch user data based on customer ID
$sql = "SELECT roomnumber, roomprice, downpayment, balance, paymentdate FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user data
$userData = $result->fetch_assoc();

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the user data as JSON
echo json_encode($userData);
?>
