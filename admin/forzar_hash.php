<?php
require_once 'config/conexion.php';

$email = 'admin@dialogoydesarrollo.pe';
$nuevo_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

// Forzar actualización
$sql = "UPDATE usuarios SET password_hash = '$nuevo_hash' WHERE email = '$email'";
$conexion->query($sql);

echo "<h2>✅ Hash actualizado</h2>";

// Verificar
$result = $conexion->query("SELECT email, password_hash FROM usuarios WHERE email = '$email'");
if ($row = $result->fetch_assoc()) {
    echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
    echo "<p><strong>Hash guardado:</strong> " . $row['password_hash'] . "</p>";
    echo "<hr>";
    if (password_verify('1234', $row['password_hash'])) {
        echo "<p style='color:green; font-size:20px; font-weight:bold;'>✅ CONTRASEÑA CORRECTA AHORA</p>";
        echo "<p>Prueba login: <a href='login.php'>http://localhost/DD/admin/login.php</a></p>";
    } else {
        echo "<p style='color:red; font-size:20px; font-weight:bold;'>❌ Sigue incorrecta</p>";
    }
}
?>