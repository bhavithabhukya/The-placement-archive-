<?php
require_once '../config.php';
$user = requireLogin($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([$_SESSION['student_id']]);
    echo json_encode($stmt->fetch());
}
?>
