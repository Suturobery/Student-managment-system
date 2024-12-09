<?php
session_start();
include '../includes/db_connect.php'; // Database connection

// Redirect to login page if user is not logged in or not a student
if ($_SESSION['user_type'] != 'student') {
    header("Location:../pages/login.php");
    exit();
}

// Fetch student data
if (isset($_SESSION['id'])) {  
    $id = $_SESSION['id'];
    $query = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); // "i" stands for integer
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

?>
<script>
// JavaScript for opening and closing the logout modal
function openLogout(event) {
    event.preventDefault();
    const modal = document.getElementById("logout");
    modal.style.display = "flex";
}

function closeLogout() {
    const modal = document.getElementById("logout");
    modal.style.display = "none";
}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../assets/css/student_info.css">
    <link href="../assets/fonts/font-awesome/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="left-panel">
        <h2>User Dashboard</h2>
        <nav>
            <div class="nav-item"><a href="">Profile</a></div>
            <div class="nav-item"><a href="#" onclick="openLogout(event)">Logout</a></div>
        </nav>
    </div>

    <div class="right-panel" id="rightPanel">
        <p class="user"><span class="icon-circle"><i class="fa-solid fa-user"></i></span> Welcome, 
            <?php 
                if ($_SESSION['user_type'] == 'student') {
                    echo isset($student['name']) ? htmlspecialchars($student['name']) : 'User';
                }
            ?>!
        </p>

        <div class="profile-info">
            <h3>User Dashboard Overview</h3>
            <?php if ($_SESSION['user_type'] == 'student'): ?>
                <p><strong>Name:</strong> <?php echo isset($student['name']) ? htmlspecialchars($student['name']) : 'Not Available'; ?></p>
                <p><strong>Email:</strong> <?php echo isset($student['email']) ? htmlspecialchars($student['email']) : 'Not Available'; ?></p>
                <p><strong>Contact:</strong> <?php echo isset($student['contact']) ? htmlspecialchars($student['contact']) : 'Not Available'; ?></p>
            <?php elseif ($_SESSION['user_type'] == 'admin'): ?>
                <p><strong>Username:</strong> <?php echo isset($admin['username']) ? htmlspecialchars($admin['username']) : 'Not Available'; ?></p>
                <p><strong>Email:</strong> <?php echo isset($admin['email']) ? htmlspecialchars($admin['email']) : 'Not Available'; ?></p>
            <?php endif; ?>
        </div>

        <div class="update-form">
            <h3>Update Your Profile</h3>
            <form method="POST" action="update_profile.php">

                <?php if ($_SESSION['user_type'] == 'student'): ?>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($student['name']) ? htmlspecialchars($student['name']) : ''; ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($student['email']) ? htmlspecialchars($student['email']) : ''; ?>" required>
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" value="<?php echo isset($student['contact']) ? htmlspecialchars($student['contact']) : ''; ?>" required>
                <?php elseif ($_SESSION['user_type'] == 'admin'): ?>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($admin['username']) ? htmlspecialchars($admin['username']) : ''; ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($admin['email']) ? htmlspecialchars($admin['email']) : ''; ?>" required>
                <?php endif; ?>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal" id="logout">
    <div class="modal-content">
        <span class="close-modal" onclick="closeLogout()">×</span>
        <h3>Confirm Logout</h3>
        <p class="logout-message">Are you sure you want to log out?</p>
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-btn">Yes, Logout</button>
        </form>
    </div>
</div>
</body>
</html>
<script>
    // JavaScript for showing success animation after profile update
function showUpdateMessage() {
    const successMessage = document.createElement("p");
    successMessage.textContent = "Profile updated successfully!";
    successMessage.classList.add("success-message");
    
    const updateForm = document.querySelector(".update-form");
    updateForm.appendChild(successMessage);

    // Remove the success message after 3 seconds
    setTimeout(() => {
        successMessage.remove();
    }, 3000);
}
</script>
