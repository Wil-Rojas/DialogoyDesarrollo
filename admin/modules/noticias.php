<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../config/conexion.php';

$modo_edicion = false;
$noticia_editar = null;
$mostrar_formulario = false;

// ============== CREAR ==============
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha_publicacion'];
    $link = $_POST['link_externo'];
    $usuario_id = $_SESSION['user_id'];
    
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '.' . $ext;
        $ruta = '../../assets/images/noticias/' . $nombre;
        if (!is_dir('../../assets/images/noticias/')) {
            mkdir('../../assets/images/noticias/', 0777, true);
        }
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        $foto = 'assets/images/noticias/' . $nombre;
    }
    
    $sql = "INSERT INTO noticias (titulo, foto, link_externo, fecha_publicacion, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $titulo, $foto, $link, $fecha, $usuario_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/noticias.php?mensaje=creado");
    exit();
}

// ============== EDITAR ==============
if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $id = (int)$_POST['id'];
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha_publicacion'];
    $link = $_POST['link_externo'];
    
    $q = $conexion->prepare("SELECT foto FROM noticias WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $actual = $q->get_result()->fetch_assoc();
    $q->close();
    
    $foto = $actual['foto'];
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        if (!empty($foto) && file_exists('../../' . $foto)) {
            @unlink('../../' . $foto);
        }
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '.' . $ext;
        $ruta = '../../assets/images/noticias/' . $nombre;
        if (!is_dir('../../assets/images/noticias/')) {
            mkdir('../../assets/images/noticias/', 0777, true);
        }
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        $foto = 'assets/images/noticias/' . $nombre;
    }
    
    $sql = "UPDATE noticias SET titulo=?, foto=?, link_externo=?, fecha_publicacion=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $titulo, $foto, $link, $fecha, $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/noticias.php?mensaje=editado");
    exit();
}

// ============== ELIMINAR ==============
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    $q = $conexion->prepare("SELECT foto FROM noticias WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $data = $q->get_result()->fetch_assoc();
    $q->close();
    
    if ($data && !empty($data['foto']) && file_exists('../../' . $data['foto'])) {
        @unlink('../../' . $data['foto']);
    }
    
    $stmt = $conexion->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/noticias.php?mensaje=eliminado");
    exit();
}

// ============== CARGAR PARA EDITAR ==============
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $q = $conexion->prepare("SELECT * FROM noticias WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $noticia_editar = $q->get_result()->fetch_assoc();
    $q->close();
    if ($noticia_editar) $modo_edicion = true;
}

if (isset($_GET['nuevo'])) {
    $mostrar_formulario = true;
}

$noticias = $conexion->query("SELECT * FROM noticias ORDER BY fecha_publicacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/DD/admin/template/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Noticias</title>
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
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php 
                            if ($_GET['mensaje'] == 'creado') echo 'Noticia creada exitosamente.';
                            elseif ($_GET['mensaje'] == 'editado') echo 'Noticia actualizada exitosamente.';
                            elseif ($_GET['mensaje'] == 'eliminado') echo 'Noticia eliminada exitosamente.';
                            ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Gestión de Noticias</h3>
                                <a href="../modules/noticias.php?nuevo=1" class="btn btn-success">
                                    <i class="fas fa-plus-circle"></i> Nueva Noticia
                                </a>
                            </div>
                            
                            <div id="formulario" style="display:<?= ($modo_edicion || $mostrar_formulario) ? 'block' : 'none' ?>;" class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5><?= $modo_edicion ? 'Editar Noticia' : 'Crear Nueva Noticia' ?></h5>
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="accion" value="<?= $modo_edicion ? 'editar' : 'crear' ?>">
                                            <?php if ($modo_edicion): ?>
                                                <input type="hidden" name="id" value="<?= $noticia_editar['id'] ?>">
                                            <?php endif; ?>
                                            
                                            <div class="form-group">
                                                <label>Título *</label>
                                                <input type="text" name="titulo" class="form-control" required
                                                       value="<?= $modo_edicion ? htmlspecialchars($noticia_editar['titulo']) : '' ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Foto</label>
                                                <input type="file" name="foto" class="form-control" accept="image/*">
                                                <?php if ($modo_edicion && $noticia_editar['foto']): ?>
                                                    <small class="text-muted d-block mt-2">
                                                        Actual: <img src="../../<?= $noticia_editar['foto'] ?>" width="80" style="object-fit:cover;">
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Link Externo</label>
                                                <input type="url" name="link_externo" class="form-control" placeholder="https://..."
                                                       value="<?= $modo_edicion ? htmlspecialchars($noticia_editar['link_externo'] ?? '') : '' ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Fecha de Publicación *</label>
                                                <input type="date" name="fecha_publicacion" class="form-control" required
                                                       value="<?= $modo_edicion ? $noticia_editar['fecha_publicacion'] : date('Y-m-d') ?>">
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">
                                                <?= $modo_edicion ? 'Actualizar' : 'Guardar' ?>
                                            </button>
                                            <a href="../modules/noticias.php" class="btn btn-secondary">Cancelar</a>
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
                                                    <th>Foto</th>
                                                    <th>Título</th>
                                                    <th>Fecha</th>
                                                    <th>Link</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($noticia = $noticias->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $noticia['id'] ?></td>
                                                    <td>
                                                        <?php if($noticia['foto'] && file_exists('../../' . $noticia['foto'])): ?>
                                                            <img src="../../<?= $noticia['foto'] ?>" width="50" height="50" style="object-fit:cover;">
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin foto</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars(substr($noticia['titulo'], 0, 50)) . (strlen($noticia['titulo']) > 50 ? '...' : '') ?></td>
                                                    <td><?= date('d/m/Y', strtotime($noticia['fecha_publicacion'])) ?></td>
                                                    <td>
                                                        <?php if($noticia['link_externo']): ?>
                                                            <a href="<?= htmlspecialchars($noticia['link_externo']) ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="../modules/noticias.php?editar=<?= $noticia['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="../modules/noticias.php?eliminar=<?= $noticia['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta noticia?')" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
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