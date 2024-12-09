<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/manage_students.css">
    <link href="../assets/fonts/font-awesome/css/all.min.css" rel="stylesheet">
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


        // JavaScript for opening and closing modals
        function openEditModal(id, name, email, contact) {
            const modal = document.getElementById("editModal");
            modal.style.display = "flex";

            // Populate modal inputs with existing student data
            document.getElementById("editId").value = id;
            document.getElementById("editName").value = name;
            document.getElementById("editEmail").value = email;
            document.getElementById("editContact").value = contact;
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }

        function openDeleteModal(id, name) {
            const modal = document.getElementById("deleteModal");
            modal.style.display = "flex";

            // Populate modal with student details
            document.getElementById("deleteId").value = id;
            document.getElementById("deleteName").textContent = name;
        }

        function closeDeleteModal() {
            document.getElementById("deleteModal").style.display = "none";
        }

        // Function to hide the success message after a timeout
        function hideSuccessMessage() {
            const message = document.getElementById("successMessage");
            if (message) {
                message.style.display = 'none';
            }
        }

        window.onload = function() {
            // Hide the success message after 3 seconds
            setTimeout(hideSuccessMessage, 3000);
        }
    </script>
</head>
<body>
    
<div class="container">
    <div class="left-panel">
        <h2>Admin Dashboard</h2>
        <nav>
        <div class="nav-item"><a href="dashboard.php">Home</a></div>
            <div class="nav-item"><a href="#">Manage students</a></div>
            <div class="nav-item"><a href="#" onclick="openLogoutModal(event)">Logout</a></div>
        </nav>
    </div>
    <div class="right-panel" id="rightPanel">
        <h2>Manage Students</h2>
        <div class="card1">
            <!-- Scrollable Student List -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../includes/db_connect.php'; // Database connection

                        // Fetch students
                        $result = $conn->query("SELECT * FROM students");

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $id = $row['id'];
                                $name = $row['name'];
                                $email = $row['email'];
                                $contact = $row['contact'];

                                echo "<tr>
                                        <td>$id</td>
                                        <td>$name</td>
                                        <td>$email</td>
                                        <td>$contact</td>
                                        <td>
                                            <button class='edit-btn' onclick=\"openEditModal('$id', '$name', '$email', '$contact')\">Edit</button>
                                            <button class='delete-btn' onclick=\"openDeleteModal('$id', '$name')\">Delete</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No students found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>            
        <div class="card">
            <!-- Add Student Form -->
            <h4>Add a New Student</h4>
            <form method="POST" action="add_student.php" class="add-student-form">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="contact" placeholder="Contact" required>
                <button type="submit">Add Student</button>
            </form>  
        </div>
        <?php
        if (isset($_GET['success'])) {
            echo "<p id='successMessage' class='slide-down-message'>{$_GET['success']}</p>";
        }
        if (isset($_GET['error'])) {
            echo "<p class='fade-in-message' style='color: red; text-align: center;'>{$_GET['error']}</p>";
        }
        ?> 
    </div>
</div>

<!-- Modal for editing student -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeEditModal()">×</span>
        <h3>Edit Student</h3>
        <form method="POST" action="edit_student.php">
            <input type="hidden" id="editId" name="id">
            <input type="text" id="editName" name="name" placeholder="Name" required>
            <input type="email" id="editEmail" name="email" placeholder="Email" required>
            <input type="text" id="editContact" name="contact" placeholder="Contact" required>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<!-- Modal for deleting student -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeDeleteModal()">×</span>
        <h3>Delete Student</h3>
        <p>Are you sure you want to delete <strong id="deleteName"></strong>?</p>
        <form method="POST" action="delete_student.php">
            <input type="hidden" id="deleteId" name="id">
            <button type="submit" class="delete-btn">Yes, Delete</button>
        </form>
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
