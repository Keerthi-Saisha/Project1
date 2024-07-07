<?php
session_start(); // Start session for storing user data

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection credentials
    $servername = "localhost";
    $db_username = "your_username";
    $db_password = "your_password";
    $dbname = "your_database";

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data (you can add more validation if needed)
    if (empty($username) || empty($password)) {
        $error = "Username and Password are required";
        header("Location: login.html?error=" . urlencode($error));
        exit();
    }

    // SQL query to fetch user from database
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // User found, store user data in session
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; // Example: If your users table has an 'id' field
        // Redirect to dashboard or any other authenticated page
        header("Location: dashboard.php");
        exit();
    } else {
        // If no user found, display error message
        $error = "Invalid username or password";
        header("Location: login.html?error=" . urlencode($error));
        exit();
    }

    // Close database connection
    $conn->close();
}
?>

