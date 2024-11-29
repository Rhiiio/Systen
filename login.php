<?php
session_start();

// Include database connection file
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if 'email' and 'password' are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email']; // Get Institutional Email (IE)
        $password = $_POST['password']; // Get password

        // Prepare SQL to check user existence
        $sql = "SELECT id, email, password, name FROM users WHERE email = ?"; // Ensure we only select the necessary fields

        if ($stmt = $conn->prepare($sql)) {
            // Bind the email parameter
            $stmt->bind_param("s", $email);

            // Execute the query
            $stmt->execute();

            // Store the result
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // Bind result to variables (make sure these match the SELECT columns)
                $stmt->bind_result($id, $db_email, $db_password, $db_name);

                // Fetch data
                $stmt->fetch();

                // Verify password
                if (password_verify($password, $db_password)) {
                    // Password matches, start session
                    $_SESSION['logged_in'] = true;
                    $_SESSION['email'] = $db_email;
                    $_SESSION['name'] = $db_name;

                    // Redirect to dashboard
                    header("Location: dashboard.html");
                    exit;
                } else {
                    echo "Invalid password.";
                }
            } else {
                echo "No user found with that email.";
            }
            $stmt->close();
        } else {
            echo "Database error: Unable to prepare query.";
        }
    } else {
        echo "Institutional Email and password are required.";
    }
}
?>