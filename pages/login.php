<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['id'] = $user['id'];  // Change 'user_id' to 'id' here
            $_SESSION['user_type'] = $user['user_type'];

            if ($user['user_type'] == 'admin') {
                header("Location:../pages/dashboard.php");
                exit;
            } else {
                header("Location:../pages/student_info.php");
                exit;
            }
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link href="../assets/fonts/font-awesome/css/all.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <div class="login-box">
            <h2>Log in</h2>
            <p>Don't have an account? <a href="register.php">Register</a></p>
            <form method="POST" class="form">
                <div class="input-container">
                    <input type="text" name="username" placeholder="Username">

                <div class="input-container">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <input type="submit" value="Log in">
            </form>
        </div>
    </div>
</div>


        <div class="right-panel">
            <!-- Display error message -->
            <?php if (isset($error_message)): ?>
                    <div id="error" class="error"><?= $error_message; ?></div>
                <?php endif; ?>
        </div>
    </div>

    <script>
        // Show and hide the error message with slide effect
        document.addEventListener('DOMContentLoaded', () => {
            const errorMessage = document.getElementById('error');
            
            if (errorMessage) {
                // Add the class to trigger the slide-down effect
                errorMessage.classList.add('message-visible');
                
                // Remove the class after 3 seconds to hide the error message
                setTimeout(() => {
                    errorMessage.classList.remove('message-visible');
                }, 3000); // 3 seconds
            }
        });
    </script>

</body>
</html>
