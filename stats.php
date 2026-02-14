<?php
require_once '../config.php';

$stmt = $pdo->query('
    SELECT 
        COUNT(*) as total_experiences,
        COUNT(DISTINCT company_id) as total_companies,
        COUNT(*) FILTER (WHERE DATE(created_at) >= CURDATE() - INTERVAL 30 DAY) as recent
    FROM experiences WHERE is_approved = 1
');
echo json_encode($stmt->fetch());
?>
