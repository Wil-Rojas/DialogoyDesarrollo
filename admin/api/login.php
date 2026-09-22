<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../login.php?error=Complete todos los campos");
        exit();
    }

    $stmt = $conexion->prepare("SELECT id, nombres, ap_paterno, password_hash, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombres'] . ' ' . $user['ap_paterno'];
            $_SESSION['user_rol'] = $user['rol'];
            header("Location: ../index.php");
            exit();
        } else {
            header("Location: ../login.php?error=Contraseña incorrecta");
            exit();
        }
    } else {
        header("Location: ../login.php?error=Usuario no encontrado");
        exit();
    }
    $stmt->close();
    $conexion->close();
}
?>