<?php
require_once 'config/conexion.php';

$email = 'admin@dialogoydesarrollo.pe';

$result = $conexion->query("SELECT id, nombres, email, password_hash, rol FROM usuarios WHERE email = '$email'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<h2>✅ Usuario encontrado</h2>";
    echo "<p><strong>ID:</strong> " . $user['id'] . "</p>";
    echo "<p><strong>Nombre:</strong> " . $user['nombres'] . "</p>";
    echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
    echo "<p><strong>Rol:</strong> " . $user['rol'] . "</p>";
    echo "<p><strong>Hash guardado:</strong> " . $user['password_hash'] . "</p>";
    echo "<hr>";
    echo "<h3>Prueba de verificación:</h3>";
    
    $password_prueba = '1234';
    if (password_verify($password_prueba, $user['password_hash'])) {
        echo "<p style='color:green; font-weight:bold;'>✅ La contraseña '1234' es CORRECTA</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>❌ La contraseña '1234' es INCORRECTA</p>";
    }
} else {
    echo "<h2>❌ Usuario NO encontrado</h2>";
    echo "<p>Ejecuta el SQL para insertar el usuario admin</p>";
}

$conexion->close();
?>