<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../config/conexion.php';

$modo_edicion = false;
$usuario_editar = null;
$mostrar_formulario = false;

// ============== CREAR USUARIO ==============
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombres = $_POST['nombres'];
    $ap_paterno = $_POST['ap_paterno'];
    $ap_materno = $_POST['ap_materno'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    
    // Verificar email único
    $check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        header("Location: ../modules/usuarios.php?mensaje=email_existe");
        exit();
    }
    $check->close();
    
    // Cifrar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (nombres, ap_paterno, ap_materno, email, password_hash, rol) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssss", $nombres, $ap_paterno, $ap_materno, $email, $password_hash, $rol);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/usuarios.php?mensaje=creado");
    exit();
}

// ============== EDITAR USUARIO ==============
if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $id = (int)$_POST['id'];
    $nombres = $_POST['nombres'];
    $ap_paterno = $_POST['ap_paterno'];
    $ap_materno = $_POST['ap_materno'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = $_POST['password'] ?? '';
    
    // Verificar email único (excepto el mismo usuario)
    $check = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
    $check->bind_param("si", $email, $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        header("Location: ../modules/usuarios.php?mensaje=email_existe");
        exit();
    }
    $check->close();
    
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombres=?, ap_paterno=?, ap_materno=?, email=?, password_hash=?, rol=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssssi", $nombres, $ap_paterno, $ap_materno, $email, $password_hash, $rol, $id);
    } else {
        $sql = "UPDATE usuarios SET nombres=?, ap_paterno=?, ap_materno=?, email=?, rol=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssi", $nombres, $ap_paterno, $ap_materno, $email, $rol, $id);
    }
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/usuarios.php?mensaje=editado");
    exit();
}

// ============== ELIMINAR USUARIO ==============
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    // No permitir eliminar el propio usuario
    if ($id == $_SESSION['user_id']) {
        header("Location: ../modules/usuarios.php?mensaje=no_auto_eliminar");
        exit();
    }
    
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/usuarios.php?mensaje=eliminado");
    exit();
}

// ============== CARGAR PARA EDITAR ==============
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $q = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $usuario_editar = $q->get_result()->fetch_assoc();
    $q->close();
    if ($usuario_editar) $modo_edicion = true;
}

if (isset($_GET['nuevo'])) {
    $mostrar_formulario = true;
}

$usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/DD/admin/template/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php if (isset($_GET['mensaje'])): ?>
                        <div class="alert alert-<?= $_GET['mensaje'] == 'email_existe' || $_GET['mensaje'] == 'no_auto_eliminar' ? 'danger' : 'success' ?> alert-dismissible fade show">
                            <?php 
                            if ($_GET['mensaje'] == 'creado') echo 'Usuario creado exitosamente.';
                            elseif ($_GET['mensaje'] == 'editado') echo 'Usuario actualizado exitosamente.';
                            elseif ($_GET['mensaje'] == 'eliminado') echo 'Usuario eliminado exitosamente.';
                            elseif ($_GET['mensaje'] == 'email_existe') echo 'El email ya está registrado por otro usuario.';
                            elseif ($_GET['mensaje'] == 'no_auto_eliminar') echo 'No puedes eliminar tu propio usuario.';
                            ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Gestión de Usuarios</h3>
                                <a href="../modules/usuarios.php?nuevo=1" class="btn btn-warning">
                                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                                </a>
                            </div>
                            
                            <div id="formulario" style="display:<?= ($modo_edicion || $mostrar_formulario) ? 'block' : 'none' ?>;" class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5><?= $modo_edicion ? 'Editar Usuario' : 'Crear Nuevo Usuario' ?></h5>
                                        <form method="POST">
                                            <input type="hidden" name="accion" value="<?= $modo_edicion ? 'editar' : 'crear' ?>">
                                            <?php if ($modo_edicion): ?>
                                                <input type="hidden" name="id" value="<?= $usuario_editar['id'] ?>">
                                            <?php endif; ?>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nombres *</label>
                                                        <input type="text" name="nombres" class="form-control" required
                                                               value="<?= $modo_edicion ? htmlspecialchars($usuario_editar['nombres']) : '' ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Apellido Paterno *</label>
                                                        <input type="text" name="ap_paterno" class="form-control" required
                                                               value="<?= $modo_edicion ? htmlspecialchars($usuario_editar['ap_paterno']) : '' ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Apellido Materno</label>
                                                        <input type="text" name="ap_materno" class="form-control"
                                                               value="<?= $modo_edicion ? htmlspecialchars($usuario_editar['ap_materno'] ?? '') : '' ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email *</label>
                                                        <input type="email" name="email" class="form-control" required
                                                               value="<?= $modo_edicion ? htmlspecialchars($usuario_editar['email']) : '' ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contraseña <?= $modo_edicion ? '(dejar en blanco para no cambiar)' : '*' ?></label>
                                                        <input type="password" name="password" class="form-control" <?= $modo_edicion ? '' : 'required' ?>>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Rol *</label>
                                                        <select name="rol" class="form-control" required>
                                                            <option value="redactor" <?= ($modo_edicion && $usuario_editar['rol'] == 'redactor') ? 'selected' : '' ?>>Redactor</option>
                                                            <option value="admin" <?= ($modo_edicion && $usuario_editar['rol'] == 'admin') ? 'selected' : '' ?>>Administrador</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">
                                                <?= $modo_edicion ? 'Actualizar' : 'Guardar' ?>
                                            </button>
                                            <a href="../modules/usuarios.php" class="btn btn-secondary">Cancelar</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombres</th>
                                                    <th>Email</th>
                                                    <th>Rol</th>
                                                    <th>Creado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($usuario = $usuarios->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $usuario['id'] ?></td>
                                                    <td><?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['ap_paterno'] . ' ' . ($usuario['ap_materno'] ?? '')) ?></td>
                                                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                                                    <td>
                                                        <?php if($usuario['rol'] == 'admin'): ?>
                                                            <span class="badge badge-danger">Admin</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-info">Redactor</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($usuario['created_at'])) ?></td>
                                                    <td>
                                                        <a href="../modules/usuarios.php?editar=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if($usuario['id'] != $_SESSION['user_id']): ?>
                                                            <a href="../modules/usuarios.php?eliminar=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')" title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-secondary" disabled title="No puedes eliminarte a ti mismo">
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="vendors/js/vendor.bundle.base.js"></script>
</body>
</html>