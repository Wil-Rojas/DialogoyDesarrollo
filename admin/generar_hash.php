<?php
$password = '1234';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Hash generado para la contraseña <strong>$password</strong></h2>";
echo "<p style='background:#f4f4f4; padding:15px; border-radius:5px; font-family:monospace; word-break:break-all;'>" . $hash . "</p>";
echo "<hr>";
echo "<h3>SQL para actualizar:</h3>";
echo "<pre style='background:#f4f4f4; padding:15px; border-radius:5px;'>";
echo "UPDATE `usuarios` SET `password_hash` = '" . $hash . "' WHERE `email` = 'admin@dialogoydesarrollo.pe';";
echo "</pre>";
?>