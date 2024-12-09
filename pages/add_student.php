<?php
// Include database connection
include '../includes/db_connect.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get student data from the form
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';

    // Check if required fields are filled
    if (empty($name) || empty($email) || empty($contact)) {
        // Redirect with error if any field is missing
        header("Location: manage_students.php?error=All fields are required!");
        exit();
    } else {
        // Prepare the INSERT query
        $stmt = $conn->prepare("INSERT INTO students (name, email, contact) VALUES (?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $contact); // Bind parameters

            // Execute the query
            if ($stmt->execute()) {
                // Redirect to manage_students.php with success message
                header("Location: manage_students.php?success=Student added successfully");
                exit();
            } else {
                // Redirect with error if query fails
                header("Location: manage_students.php?error=" . urlencode($stmt->error));
                exit();
            }

            // Close the statement
            $stmt->close();
        } else {
            // Handle database errors
            header("Location: manage_students.php?error=" . urlencode($conn->error));
            exit();
        }
    }

    // Close the database connection
    $conn->close();
}
?>
