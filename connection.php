<?php
// Database configuration
$servername = "localhost"; // Change this to your database server name if it's different
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "frenchie"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
// Retrieve form data
$username = $_POST['username'];
$dob = $_POST['dob'];
$password = $_POST['password'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'];

// Prepare and bind statement
$stmt = $conn->prepare("INSERT INTO users (username, dob, password, phoneNumber, email) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("sssss", $username, $dob, $password, $phoneNumber, $email);

// Execute the statement
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error executing statement: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
?>
