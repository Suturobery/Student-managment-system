<?php
include '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM students WHERE id = $id");
    $student = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, contact = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $contact, $id);

    if ($stmt->execute()) {
        header("Location: manage_students.php?success=Student updated successfully!");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
