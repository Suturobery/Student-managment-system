<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $contact = $_POST['contact'] ?? null;
    $user_type = $_POST['user_type'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$username) {
        $error_message = "Username is required.";
    } elseif (!$user_type || ($user_type != 'student' && $user_type != 'admin')) {
        $error_message = "Invalid user type selected.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists in the `users` table
        $check_username = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $result = $check_username->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Username already exists. Please choose a different username.";
        } else {
            // Insert shared login info into `users` table
            $stmt_users = $conn->prepare("INSERT INTO users (user_type, username, password) VALUES (?, ?, ?)");
            $stmt_users->bind_param("sss", $user_type, $username, $hashed_password);

            if ($stmt_users->execute()) {
                $user_id = $conn->insert_id; // Get the auto-incremented `id` from `users`

                // Insert specific data into `admins` or `students` table based on user_type
                if ($user_type == 'admin') {
                    // Check if an admin already exists
                    $check_admin = $conn->prepare("SELECT COUNT(*) AS admin_count FROM admins");
                    $check_admin->execute();
                    $admin_result = $check_admin->get_result();
                    $admin_count = $admin_result->fetch_assoc()['admin_count'];

                    if ($admin_count > 0) {
                        $error_message = "An admin account already exists. Only one admin is allowed.";
                        // Rollback the `users` insert if admin fails
                        $conn->query("DELETE FROM users WHERE id = $user_id");
                    } else {
                        // Insert into `admins` table
                        $stmt_admin = $conn->prepare("INSERT INTO admins (id, user_type, username, password) VALUES (?, ?, ?, ?)");
                        $stmt_admin->bind_param("isss", $user_id, $user_type, $username, $hashed_password);

                        if ($stmt_admin->execute()) {
                            $success_message = "Admin registered successfully!<br>Username: $username";
                        } else {
                            $error_message = "Error: " . $conn->error;
                        }
                    }
                } elseif ($user_type == 'student') {
                    // Insert into `students` table
                    $stmt_student = $conn->prepare("INSERT INTO students (id, name, email, contact) VALUES (?, ?, ?, ?)");
                    $stmt_student->bind_param("isss", $user_id, $name, $email, $contact);

                    if ($stmt_student->execute()) {
                        $success_message = "Student registered successfully!<br>Username: $username";
                    } else {
                        $error_message = "Error: " . $conn->error;
                    }
                }
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="login-box">
                <h2>Register</h2>
                <p>Already have an account? <a href="login.php">Log in</a></p>
                <form method="POST" class="form">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="contact" placeholder="Contact" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="user_type" required>
                        <option value="student">Student</option>
                        <option value="admin">Admin1</option>
                    </select>
                    <input type="submit" value="Register">
                </form>
            </div>
        </div>
        <div class="right-panel">
            <?php
            if (isset($error_message)) {
                echo "<div class='error'>$error_message</div>";
            } elseif (isset($success_message)) {
                echo "<div class='success'>$success_message</div>";
            }
            ?>
        </div>
        <script>
            // Show and hide the popup message with slide effect
            document.addEventListener('DOMContentLoaded', () => {
                const message = document.querySelector('.error, .success');
                if (message) {
                    // Add the slide-down class
                    message.classList.add('message-visible');
                    
                    // Remove the message after 3 seconds
                    setTimeout(() => {
                        message.classList.remove('message-visible');
                    }, 3000);
                }
            });
        </script>
    </div>
</body>
</html>
