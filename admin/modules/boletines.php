<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../config/conexion.php';

$modo_edicion = false;
$boletin_editar = null;
$mostrar_formulario = false;

// ============== CREAR ==============
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $numero = $_POST['numero_boletin'];
    $resumen = $_POST['resumen'];
    $fecha = $_POST['fecha_publicacion'];
    $usuario_id = $_SESSION['user_id'];
    
    $foto = '';
    if (isset($_FILES['foto_portada']) && $_FILES['foto_portada']['error'] == 0) {
        $ext = pathinfo($_FILES['foto_portada']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '_portada.' . $ext;
        $ruta = '../../assets/images/boletines/' . $nombre;
        if (!is_dir('../../assets/images/boletines/')) {
            mkdir('../../assets/images/boletines/', 0777, true);
        }
        move_uploaded_file($_FILES['foto_portada']['tmp_name'], $ruta);
        $foto = 'assets/images/boletines/' . $nombre;
    }
    
    $pdf = '';
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] == 0) {
        $ext = pathinfo($_FILES['archivo_pdf']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '.' . $ext;
        $ruta = '../../assets/pdfs/boletines/' . $nombre;
        if (!is_dir('../../assets/pdfs/boletines/')) {
            mkdir('../../assets/pdfs/boletines/', 0777, true);
        }
        move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $ruta);
        $pdf = 'assets/pdfs/boletines/' . $nombre;
    }
    
    $sql = "INSERT INTO boletines (numero_boletin, resumen, foto_portada, archivo_pdf, fecha_publicacion, usuario_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $numero, $resumen, $foto, $pdf, $fecha, $usuario_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/boletines.php?mensaje=creado");
    exit();
}

// ============== EDITAR ==============
if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $id = (int)$_POST['id'];
    $numero = $_POST['numero_boletin'];
    $resumen = $_POST['resumen'];
    $fecha = $_POST['fecha_publicacion'];
    
    $q = $conexion->prepare("SELECT foto_portada, archivo_pdf FROM boletines WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $actual = $q->get_result()->fetch_assoc();
    $q->close();
    
    $foto = $actual['foto_portada'];
    $pdf = $actual['archivo_pdf'];
    
    if (isset($_FILES['foto_portada']) && $_FILES['foto_portada']['error'] == 0) {
        if (!empty($foto) && file_exists('../../' . $foto)) {
            @unlink('../../' . $foto);
        }
        $ext = pathinfo($_FILES['foto_portada']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '_portada.' . $ext;
        $ruta = '../../assets/images/boletines/' . $nombre;
        if (!is_dir('../../assets/images/boletines/')) {
            mkdir('../../assets/images/boletines/', 0777, true);
        }
        move_uploaded_file($_FILES['foto_portada']['tmp_name'], $ruta);
        $foto = 'assets/images/boletines/' . $nombre;
    }
    
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] == 0) {
        if (!empty($pdf) && file_exists('../../' . $pdf)) {
            @unlink('../../' . $pdf);
        }
        $ext = pathinfo($_FILES['archivo_pdf']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '.' . $ext;
        $ruta = '../../assets/pdfs/boletines/' . $nombre;
        if (!is_dir('../../assets/pdfs/boletines/')) {
            mkdir('../../assets/pdfs/boletines/', 0777, true);
        }
        move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $ruta);
        $pdf = 'assets/pdfs/boletines/' . $nombre;
    }
    
    $sql = "UPDATE boletines SET numero_boletin=?, resumen=?, foto_portada=?, archivo_pdf=?, fecha_publicacion=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $numero, $resumen, $foto, $pdf, $fecha, $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/boletines.php?mensaje=editado");
    exit();
}

// ============== ELIMINAR ==============
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    $q = $conexion->prepare("SELECT foto_portada, archivo_pdf FROM boletines WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $data = $q->get_result()->fetch_assoc();
    $q->close();
    
    if ($data) {
        if (!empty($data['foto_portada']) && file_exists('../../' . $data['foto_portada'])) {
            @unlink('../../' . $data['foto_portada']);
        }
        if (!empty($data['archivo_pdf']) && file_exists('../../' . $data['archivo_pdf'])) {
            @unlink('../../' . $data['archivo_pdf']);
        }
    }
    
    $stmt = $conexion->prepare("DELETE FROM boletines WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/boletines.php?mensaje=eliminado");
    exit();
}

// ============== CARGAR PARA EDITAR ==============
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $q = $conexion->prepare("SELECT * FROM boletines WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $boletin_editar = $q->get_result()->fetch_assoc();
    $q->close();
    if ($boletin_editar) $modo_edicion = true;
}

if (isset($_GET['nuevo'])) {
    $mostrar_formulario = true;
}

$boletines = $conexion->query("SELECT * FROM boletines ORDER BY fecha_publicacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/DD/admin/template/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Boletines</title>
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
                            if ($_GET['mensaje'] == 'creado') echo 'Boletín creado exitosamente.';
                            elseif ($_GET['mensaje'] == 'editado') echo 'Boletín actualizado exitosamente.';
                            elseif ($_GET['mensaje'] == 'eliminado') echo 'Boletín eliminado exitosamente.';
                            ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Gestión de Boletines</h3>
                                <a href="../modules/boletines.php?nuevo=1" class="btn btn-info">
                                    <i class="fas fa-plus-circle"></i> Nuevo Boletín
                                </a>
                            </div>
                            
                            <div id="formulario" style="display:<?= ($modo_edicion || $mostrar_formulario) ? 'block' : 'none' ?>;" class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5><?= $modo_edicion ? 'Editar Boletín' : 'Crear Nuevo Boletín' ?></h5>
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="accion" value="<?= $modo_edicion ? 'editar' : 'crear' ?>">
                                            <?php if ($modo_edicion): ?>
                                                <input type="hidden" name="id" value="<?= $boletin_editar['id'] ?>">
                                            <?php endif; ?>
                                            
                                            <div class="form-group">
                                                <label>Número de Boletín *</label>
                                                <input type="text" name="numero_boletin" class="form-control" placeholder="Ej: NTEP-40" required
                                                       value="<?= $modo_edicion ? htmlspecialchars($boletin_editar['numero_boletin']) : '' ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Resumen</label>
                                                <input type="text" name="resumen" class="form-control" maxlength="500"
                                                       value="<?= $modo_edicion ? htmlspecialchars($boletin_editar['resumen'] ?? '') : '' ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Foto de Portada</label>
                                                <input type="file" name="foto_portada" class="form-control" accept="image/*">
                                                <?php if ($modo_edicion && $boletin_editar['foto_portada']): ?>
                                                    <small class="text-muted d-block mt-2">
                                                        Actual: <img src="../../<?= $boletin_editar['foto_portada'] ?>" width="80" style="object-fit:cover;">
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Archivo PDF <?= $modo_edicion ? '' : '*' ?></label>
                                                <input type="file" name="archivo_pdf" class="form-control" accept=".pdf" <?= $modo_edicion ? '' : 'required' ?>>
                                                <?php if ($modo_edicion && $boletin_editar['archivo_pdf']): ?>
                                                    <small class="text-muted d-block mt-2">
                                                        Actual: <a href="../../<?= $boletin_editar['archivo_pdf'] ?>" target="_blank">Ver PDF</a>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Fecha de Publicación *</label>
                                                <input type="date" name="fecha_publicacion" class="form-control" required
                                                       value="<?= $modo_edicion ? $boletin_editar['fecha_publicacion'] : date('Y-m-d') ?>">
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">
                                                <?= $modo_edicion ? 'Actualizar' : 'Guardar' ?>
                                            </button>
                                            <a href="../modules/boletines.php" class="btn btn-secondary">Cancelar</a>
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
                                                    <th>Portada</th>
                                                    <th>Número</th>
                                                    <th>Fecha</th>
                                                    <th>PDF</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($boletin = $boletines->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $boletin['id'] ?></td>
                                                    <td>
                                                        <?php if($boletin['foto_portada'] && file_exists('../../' . $boletin['foto_portada'])): ?>
                                                            <img src="../../<?= $boletin['foto_portada'] ?>" width="50" height="50" style="object-fit:cover;">
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin foto</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($boletin['numero_boletin']) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($boletin['fecha_publicacion'])) ?></td>
                                                    <td>
                                                        <?php if($boletin['archivo_pdf'] && file_exists('../../' . $boletin['archivo_pdf'])): ?>
                                                            <a href="../../<?= $boletin['archivo_pdf'] ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin PDF</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="../modules/boletines.php?editar=<?= $boletin['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="../modules/boletines.php?eliminar=<?= $boletin['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este boletín?')" title="Eliminar">
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