<?php
// Start session
session_start();

// Include config file
require_once "connection.php";

// Define variables and initialize with empty values
$username = $password = $confirmPassword = $dob = $phoneNumber = $email = "";
$username_err = $password_err = $confirmPassword_err = $dob_err = $phoneNumber_err = $email_err = "";
$success_message = ""; // Initialize success message

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Validate date of birth
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter your date of birth.";
    } else {
        $dob = trim($_POST["dob"]);
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirmPassword"]))) {
        $confirmPassword_err = "Please confirm your password.";
    } else {
        $confirmPassword = trim($_POST["confirmPassword"]);
        if (empty($password_err) && ($password != $confirmPassword)) {
            $confirmPassword_err = "Passwords did not match.";
        }
    }
    
    // Validate phone number
    if (empty(trim($_POST["phoneNumber"]))) {
        $phoneNumber_err = "Please enter your phone number.";
    } else {
        $phoneNumber = trim($_POST["phoneNumber"]);
    }
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Check input errors before inserting into database
    if (empty($username_err) && empty($dob_err) && empty($password_err) && empty($confirmPassword_err) && empty($phoneNumber_err) && empty($email_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, dob, password, phoneNumber, email) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_dob, $param_password, $param_phoneNumber, $param_email);
            
            // Set parameters
            $param_username = $username;
            $param_dob = $dob;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_phoneNumber = $phoneNumber;
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                $success_message = "User registered successfully.";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            text-align: center;
            color: white;
            background-color: #59788E;
            background-image: url("background.jpg"); /* Provide a valid image path */
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #022D36;
            padding: 20px;
            border-radius: 10px;
        }

        main {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: black;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="tel"],
        input[type="email"],
        input[type="submit"],
        input[type="date"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #007bff;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
    <script>
        function validateForm() {
            var username = document.getElementById('username').value.trim();
            var dob = document.getElementById('dob').value.trim();
            var password = document.getElementById('password').value.trim();
            var confirmPassword = document.getElementById('confirmPassword').value.trim();
            var phoneNumber = document.getElementById('phoneNumber').value.trim();
            var email = document.getElementById('email').value.trim();

            // Validate username (only alphabetic characters, no spaces or numbers)
            var usernameRegex = /^[a-zA-Z]+$/;
            if (!username.match(usernameRegex)) {
                alert("Username should contain only alphabetic characters");
                return false;
            }

            // Validate date of birth
            if (dob == "") {
                alert("Please enter your date of birth");
                return false;
            }

            // Validate password strength
            var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/;
            if (!password.match(passwordRegex)) {
                alert("Password should contain at least 8 characters including uppercase, lowercase letters, numbers, and special characters");
                return false;
            }

            // Confirm password match
            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            // Validate phone number
            var phoneNumberRegex = /^\d{10}$/;
            if (!phoneNumber.match(phoneNumberRegex)) {
                alert("Invalid phone number");
                return false;
            }

            // Validate email address
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.match(emailRegex)) {
                alert("Invalid email address");
                return false;
            }

            // All validations passed
            alert("Form submitted successfully!");
            return true;
        }
    </script>
</head>
<body>

<h1>Registration Form</h1>
<main>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br>
        <span class="error"><?php echo $username_err; ?></span><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required><br>
        <span class="error"><?php echo $dob_err; ?></span><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <span class="error"><?php echo $password_err; ?></span><br>
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required><br>
        <span class="error"><?php echo $confirmPassword_err; ?></span><br>
        <label for="phoneNumber">Phone Number:</label>
        <input type="tel" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($phoneNumber); ?>" required><br>
        <span class="error"><?php echo $phoneNumber_err; ?></span><br>
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
        <span class="error"><?php echo $email_err; ?></span><br>
        <input type="submit" value="Submit">
    </form>
    <p class="success"><?php echo $success_message; ?></p>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</main>

</body>
</html>
