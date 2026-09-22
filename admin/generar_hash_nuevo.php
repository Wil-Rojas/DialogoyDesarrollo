<?php
// Generar un hash NUEVO para la contraseña '1234'
$password = '1234';
$nuevo_hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>🔑 NUEVO HASH GENERADO</h2>";
echo "<p><strong>Contraseña:</strong> " . $password . "</p>";
echo "<p><strong>Hash generado:</strong></p>";
echo "<p style='background:#f4f4f4; padding:15px; border-radius:5px; font-family:monospace; word-break:break-all; font-size:14px;'>" . $nuevo_hash . "</p>";

echo "<hr>";
echo "<h3>📋 SQL para actualizar (COPIA ESTO):</h3>";
echo "<pre style='background:#f4f4f4; padding:15px; border-radius:5px; font-size:14px;'>";
echo "UPDATE `usuarios` SET `password_hash` = '" . $nuevo_hash . "' WHERE `email` = 'admin@dialogoydesarrollo.pe';";
echo "</pre>";

// Verificar que el hash funciona
echo "<hr>";
echo "<h3>✅ Verificación del hash:</h3>";
if (password_verify('1234', $nuevo_hash)) {
    echo "<p style='color:green; font-size:18px; font-weight:bold;'>✅ El hash SÍ funciona correctamente</p>";
} else {
    echo "<p style='color:red; font-size:18px; font-weight:bold;'>❌ El hash NO funciona</p>";
}
?>