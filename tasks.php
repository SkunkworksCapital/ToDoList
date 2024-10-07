<?php
// Database credentials
$servername = "localhost:3306";
$username = "sagejyou_admin";
$password = "Bali744aide664";
$dbname = "sagejyou_Todo";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX requests based on the action passed
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Add a new task
    if ($action == 'add') {
        if (isset($_POST['task'])) {
            $task = $_POST['task'];
            $sql = "INSERT INTO tasks (task) VALUES ('$task')";
            if ($conn->query($sql) === TRUE) {
                echo "Task added successfully";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    // Delete a task
    if ($action == 'delete') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM tasks WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo "Task deleted successfully";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    // Mark task as completed
    if ($action == 'complete') {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "UPDATE tasks SET status='completed' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo "Task marked as completed";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    exit();
}

// Fetch tasks to display on the frontend
$sql = "SELECT * FROM tasks WHERE status='pending'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li>' . htmlspecialchars($row['task']) . 
             ' <button onclick="deleteTask(' . $row['id'] . ')">Delete</button>' .
             ' <button onclick="completeTask(' . $row['id'] . ')">Complete</button></li>';
    }
} else {
    echo '<li>No tasks available</li>';
}

$conn->close();
?>
