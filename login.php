<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    exit(json_encode(['error' => 'Email and password required']));
}

if (!str_ends_with($email, '@cbit.ac.in')) {
    http_response_code(401);
    exit(json_encode(['error' => 'CBIT email required']));
}

$stmt = $pdo->prepare('SELECT * FROM students WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['student_id'] = $user['id'];
    $_SESSION['student_name'] = $user['full_name'];
    $_SESSION['student_email'] = $user['email'];
    
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'name' => $user['full_name'],
            'email' => $user['email'],
            'branch' => $user['branch'],
            'year' => $user['year']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}
?>
