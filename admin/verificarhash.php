<?php
require_once 'config/conexion.php';

$email = 'admin@dialogoydesarrollo.pe';
$password_prueba = '1234';

$stmt = $conexion->prepare("SELECT password_hash FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $hash = $row['password_hash'];
    
    echo "<h2>Verificación de Login</h2>";
    echo "<p><strong>Email:</strong> " . $email . "</p>";
    echo "<p><strong>Contraseña ingresada:</strong> " . $password_prueba . "</p>";
    echo "<p><strong>Hash guardado:</strong> " . $hash . "</p>";
    echo "<hr>";
    
    if (password_verify($password_prueba, $hash)) {
        echo "<p style='color:green; font-size:20px; font-weight:bold;'>✅ CONTRASEÑA CORRECTA</p>";
        echo "<p>El login DEBERÍA funcionar.</p>";
    } else {
        echo "<p style='color:red; font-size:20px; font-weight:bold;'>❌ CONTRASEÑA INCORRECTA</p>";
        echo "<p>El hash guardado NO coincide con la contraseña '1234'</p>";
        echo "<p>Necesitas ACTUALIZAR el hash con el correcto.</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Usuario NO encontrado</p>";
}

$conexion->close();
?>