<?php
require_once 'config/conexion.php';

// El hash CORRECTO para '1234'
$hash_correcto = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
$email = 'admin@dialogoydesarrollo.pe';

$stmt = $conexion->prepare("UPDATE usuarios SET password_hash = ? WHERE email = ?");
$stmt->bind_param("ss", $hash_correcto, $email);

if ($stmt->execute()) {
    echo "<h2 style='color:green;'>✅ Hash actualizado correctamente</h2>";
    echo "<p>Ahora prueba login con: <strong>admin@dialogoydesarrollo.pe</strong> / <strong>1234</strong></p>";
} else {
    echo "<h2 style='color:red;'>❌ Error al actualizar: " . $stmt->error . "</h2>";
}

$stmt->close();
$conexion->close();
?>