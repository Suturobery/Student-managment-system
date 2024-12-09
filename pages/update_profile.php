<?php
session_start();
include '../includes/db_connect.php'; // Database connection

// Redirect to login page if user is not logged in or not a student
if ($_SESSION['user_type'] != 'student') {
    header("Location:../pages/login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['contact'])) {
        // Get the data from the form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        
        // Sanitize the input to prevent SQL injection
        $name = htmlspecialchars($name);
        $email = htmlspecialchars($email);
        $contact = htmlspecialchars($contact);
        
        // Get the user ID
        $id = $_SESSION['id'];

        // Update the student details in the database
        $query = "UPDATE students SET name = ?, email = ?, contact = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $contact, $id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully!";
        } else {
            $_SESSION['error_message'] = "An error occurred while updating your profile.";
        }
    }
}

// Redirect back to the profile page after updating
header("Location: student_info.php");
exit();
