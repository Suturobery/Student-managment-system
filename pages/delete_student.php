<?php
// Include database connection
include '../includes/db_connect.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the student ID from the form
    $id = intval($_POST['id']); // Sanitize the input to ensure it's an integer

    // Prepare the DELETE query
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id); // Bind the student ID to the query

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to manage_students.php with a success message
            header("Location: manage_students.php?success=Student deleted successfully");
        } else {
            // Redirect to manage_students.php with an error message
            header("Location: manage_students.php?error=Failed to delete student");
        }

        // Close the statement
        $stmt->close();
    } else {
        // Redirect to manage_students.php with a database error message
        header("Location: manage_students.php?error=Database error: Unable to prepare statement");
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to manage_students.php if accessed without a POST request
    header("Location: manage_students.php");
    exit;
}
?>
