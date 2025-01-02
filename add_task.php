<?php
require_once 'config.php';

if (isset($_POST['task'])) {
    $task = trim($_POST['task']);
    
    $stmt = $db->prepare("INSERT INTO tasks (task) VALUES (?)");
    $result = $stmt->execute([$task]);
    
    echo json_encode(['success' => $result]);
}
?> 