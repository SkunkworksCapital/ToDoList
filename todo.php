<?php
$servername = "xx";
$username = "xx";
$password = "xxx";
$dbname = "xxx";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addTask($conn, $task) {
    $stmt = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
    $stmt->bind_param("s", $task);
    $stmt->execute();
    $stmt->close();
}

function getTasks($conn) {
    $result = $conn->query("SELECT id, task FROM tasks");
    while($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" . 
             $row["task"] . 
             "<button class='btn btn-danger btn-sm' onclick='deleteTask(" . $row["id"] . ")'>Delete</button></li>";
    }
}

function deleteTask($conn, $task_id) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add' && isset($_POST['task'])) {
        addTask($conn, $_POST['task']);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['task_id'])) {
        deleteTask($conn, $_POST['task_id']);
    }
} else {
    getTasks($conn);
}

$conn->close();
?>
