<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($full_name) || empty($password)) {
        die("Both fields are required!");
    }

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "qcu_map"; // Ensure this matches your signup database

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Corrected query to match `full_name`
        $query = "SELECT full_name, password FROM users WHERE full_name = :full_name";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":full_name", $full_name);
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user["password"] === $password) {
            $_SESSION['full_name'] = $full_name;
            header("Location: http://localhost/STS/home.html");
            exit();
        } else {
            echo '<script>alert("Incorrect username or password.");</script>';
            echo '<script>window.location.href = "http://localhost/STS/login.html";</script>';
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
} else {
    echo "Invalid request method!";
}
?>
