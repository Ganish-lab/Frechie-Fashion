<?php
session_start();

// Function to check if the user is logged in
function check_session() {
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
}

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>
        <?php
        // Check if the user is logged in
        check_session();

        // Fetch user details from the database
        $user_id = $_SESSION["id"];
        $sql = "SELECT * FROM users WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
        }
        ?>
        <div class="profile">
            <div class="profile-picture">
                <img src="<?php echo $user['profile_picture'] ?? 'images/placeholder.png'; ?>" alt="Profile Picture">
            </div>
            <div class="profile-details">
                <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <!-- Add other user details here -->
            </div>
        </div>
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>

