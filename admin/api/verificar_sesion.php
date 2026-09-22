<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

echo json_encode([
    'user_id' => $_SESSION['user_id'],
    'user_name' => $_SESSION['user_name'],
    'user_rol' => $_SESSION['user_rol']
]);
exit();
?>

