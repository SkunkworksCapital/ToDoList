<?php
require_once 'config.php';

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    $stmt = $db->prepare("UPDATE tasks SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?");
    $result = $stmt->execute([$id]);
    
    echo json_encode(['success' => $result]);
}
?> 