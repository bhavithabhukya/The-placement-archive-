<?php
require_once '../config.php';
$user = requireLogin($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($method) {
    case 'GET':
        // Get experiences
        $page = max(1, $input['page'] ?? 1);
        $limit = 9;
        $offset = ($page - 1) * $limit;
        
        $stmt = $pdo->prepare('
            SELECT e.*, s.full_name, c.name as company_name 
            FROM experiences e 
            JOIN students s ON e.student_id = s.id 
            JOIN companies c ON e.company_id = c.id 
            WHERE e.is_approved = 1 
            ORDER BY e.created_at DESC 
            LIMIT ? OFFSET ?
        ');
        $stmt->execute([$limit, $offset]);
        $experiences = $stmt->fetchAll();
        
        echo json_encode(['experiences' => $experiences]);
        break;
        
    case 'POST':
        // Add experience
        $company = trim($input['company'] ?? '');
        $role = trim($input['role'] ?? '');
        $difficulty = $input['difficulty'] ?? 'medium';
        $rounds = $input['rounds'] ?? [];
        $tips = trim($input['tips'] ?? '');
        
        $stmt = $pdo->prepare('INSERT INTO companies (name) VALUES (?) ON DUPLICATE KEY UPDATE name = name');
        $stmt->execute([$company]);
        $company_id = $pdo->lastInsertId() ?: $pdo->query("SELECT id FROM companies WHERE name = '$company'")->fetch()['id'];
        
        $stmt = $pdo->prepare('
            INSERT INTO experiences (student_id, company_id, role, difficulty, rounds, tips) 
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $_SESSION['student_id'],
            $company_id,
            $role,
            $difficulty,
            json_encode($rounds),
            $tips
        ]);
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        break;
}
?>
