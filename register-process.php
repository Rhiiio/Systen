<?php
// Include database connection file
include 'db_connection.php';  // Make sure to set your DB connection properly

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email and password
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];  // Get Institutional Email
        $password = $_POST['password'];  // Get password

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert user into the database
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ss", $email, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Institutional Email and Password are required.";
    }
}

// Close the connection
$conn->close();
?>
