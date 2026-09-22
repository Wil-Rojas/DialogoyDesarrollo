<?php
require_once 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conexion->prepare("SELECT id, nombres, ap_paterno, password_hash, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<h3>Usuario encontrado:</h3>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        
        if (password_verify($password, $user['password_hash'])) {
            echo "<h2 style='color:green;'>✅ LOGIN EXITOSO</h2>";
            echo "<p>Bienvenido: " . $user['nombres'] . "</p>";
        } else {
            echo "<h2 style='color:red;'>❌ Contraseña incorrecta</h2>";
        }
    } else {
        echo "<h2 style='color:red;'>❌ Usuario no encontrado</h2>";
    }
    $stmt->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Login</title>
    <style>
        body { font-family: Arial; max-width: 500px; margin: 50px auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { background: #0d6efd; color: white; padding: 10px 20px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>🔐 Prueba de Login</h1>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" value="admin@dialogoydesarrollo.pe"><br>
        <label>Contraseña:</label>
        <input type="password" name="password" value="1234"><br><br>
        <button type="submit">Probar Login</button>
    </form>
</body>
</html>
