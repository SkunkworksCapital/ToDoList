<?php
require_once 'config.php';

if (isset($_POST['id']) && isset($_POST['task'])) {
    $id = (int)$_POST['id'];
    $task = trim($_POST['task']);
    
    $stmt = $db->prepare("UPDATE tasks SET task = ? WHERE id = ?");
    $result = $stmt->execute([$task, $id]);
    
    echo json_encode(['success' => $result]);
}
?> 