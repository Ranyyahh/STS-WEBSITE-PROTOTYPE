<?php
$host = "localhost"; // Change this if your database is hosted elsewhere
$username = "root";  // Change if needed
$password = "";      // Change if your MySQL has a password
$database = "qcu_map";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if fields exist before accessing them
    $full_name = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
    $confirm_password = isset($_POST["confirm_password"]) ? trim($_POST["confirm_password"]) : "";

    // Check if any field is empty
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required!");
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Check if email is already registered
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Email already registered!");
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: http://localhost/STS/home.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
