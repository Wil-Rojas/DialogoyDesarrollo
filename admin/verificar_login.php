<?php
require_once 'config/conexion.php';

$email = 'admin@dialogoydesarrollo.pe';
$password = '1234';

echo "<h1>🔐 VERIFICACIÓN DE LOGIN</h1>";

$stmt = $conexion->prepare("SELECT password_hash FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $hash = $row['password_hash'];
    
    echo "<p><strong>Email:</strong> " . $email . "</p>";
    echo "<p><strong>Contraseña:</strong> " . $password . "</p>";
    echo "<p><strong>Hash en BD:</strong> " . $hash . "</p>";
    echo "<hr>";
    
    if (password_verify($password, $hash)) {
        echo "<p style='color:green; font-size:24px; font-weight:bold;'>✅ CONTRASEÑA CORRECTA</p>";
        echo "<p><a href='login.php' style='font-size:18px;'>Ir al Login</a></p>";
    } else {
        echo "<p style='color:red; font-size:24px; font-weight:bold;'>❌ CONTRASEÑA INCORRECTA</p>";
        echo "<p>El hash en la BD NO coincide con la contraseña '$password'</p>";
        
        // Generar un hash nuevo para mostrar
        $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<hr>";
        echo "<h3>Nuevo hash generado para '$password':</h3>";
        echo "<p style='background:#f4f4f4; padding:10px; word-break:break-all;'>" . $nuevo_hash . "</p>";
        echo "<p><strong>Ejecuta este SQL:</strong></p>";
        echo "<pre style='background:#f4f4f4; padding:10px;'>";
        echo "UPDATE `usuarios` SET `password_hash` = '" . $nuevo_hash . "' WHERE `email` = 'admin@dialogoydesarrollo.pe';";
        echo "</pre>";
    }
} else {
    echo "<p style='color:red;'>❌ Usuario NO encontrado</p>";
}

$conexion->close();
?>