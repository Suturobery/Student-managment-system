<?php
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location:../pages/login.php");
    exit();
}

include '../includes/db_connect.php';

// Fetch all students from the students table
$students_query = $conn->prepare("SELECT id, name, email, contact FROM students");
$students_query->execute();
$students_result = $students_query->get_result();

?>


<script>
// JavaScript for opening and closing modals
function openLogoutModal(event) {
event.preventDefault(); // Prevent default link behavior
const modal = document.getElementById("logoutModal");
modal.style.display = "flex"; // Show the modal
}

function closeLogoutModal() {
const modal = document.getElementById("logoutModal");
modal.style.display = "none"; // Hide the modal
}

</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="../assets/fonts/font-awesome/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="left-panel">
        <h2>Admin Dashboard</h2>
        <nav>
            <div class="nav-item"><a href="dashboard.php">Home</a></div>
            <div class="nav-item"><a href="manage_students.php">Manage students</a></div>
            <div class="nav-item"><a href="#" onclick="openLogoutModal(event)">Logout</a></div>
        </nav>
    </div>
    <div class="right-panel" id="rightPanel">
        <h2> Welcome Admin, 
            <?php 
                if ($_SESSION['user_type'] == 'admin') {
                    echo isset($admin['name']) ? htmlspecialchars($admin['name']) : 'Admin';
                }
            ?>!
        </h2>
        <div class="content">
            <div class="card">
                <h3>Dashboard Overview</h3>
                <p>Total Students: <strong><?php echo $students_result->num_rows; ?></strong></p>
                <h3>Student List</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $students_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal for logout confirmation -->
<div class="modal" id="logoutModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeLogoutModal()">×</span>
        <h3>Confirm Logout</h3>
        <p class="logout-message">Are you sure you want to log out?</p>
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-btn">Yes, Logout</button>
        </form>
    </div>
</div>
</body>
</html>
