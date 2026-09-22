<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../config/conexion.php';

$modo_edicion = false;
$reportaje_editar = null;
$mostrar_formulario = false;

if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $titulo = $_POST['titulo'];
    $resumen = $_POST['resumen_corto'];
    $desarrollo = $_POST['desarrollo'];
    $fecha = $_POST['fecha_publicacion'];
    $destacado = isset($_POST['es_destacado']) ? 1 : 0;
    $usuario_id = $_SESSION['user_id'];
    
    $foto = '';
    if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] == 0) {
        $ext = pathinfo($_FILES['foto_principal']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '_principal.' . $ext;
        $ruta = '../../assets/images/reportajes/' . $nombre;
        if (!is_dir('../../assets/images/reportajes/')) {
            mkdir('../../assets/images/reportajes/', 0777, true);
        }
        move_uploaded_file($_FILES['foto_principal']['tmp_name'], $ruta);
        $foto = 'assets/images/reportajes/' . $nombre;
    }
    
    $pdf = '';
    if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] == 0) {
        $ext = pathinfo($_FILES['pdf_adjunto']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '.' . $ext;
        $ruta = '../../assets/pdfs/' . $nombre;
        if (!is_dir('../../assets/pdfs/')) {
            mkdir('../../assets/pdfs/', 0777, true);
        }
        move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $ruta);
        $pdf = 'assets/pdfs/' . $nombre;
    }
    
    $autor_id = 1;
    
    $sql = "INSERT INTO reportajes (titulo, resumen_corto, desarrollo, foto_principal, pdf_adjunto, fecha_publicacion, es_destacado, autor_id, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssiii", $titulo, $resumen, $desarrollo, $foto, $pdf, $fecha, $destacado, $autor_id, $usuario_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/reportajes.php?mensaje=creado");
    exit();
}

if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $id = (int)$_POST['id'];
    $titulo = $_POST['titulo'];
    $resumen = $_POST['resumen_corto'];
    $desarrollo = $_POST['desarrollo'];
    $fecha = $_POST['fecha_publicacion'];
    $destacado = isset($_POST['es_destacado']) ? 1 : 0;
    
    $q = $conexion->prepare("SELECT foto_principal, pdf_adjunto FROM reportajes WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $actual = $q->get_result()->fetch_assoc();
    $q->close();
    
    $foto = $actual['foto_principal'];
    $pdf = $actual['pdf_adjunto'];
    
    if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] == 0) {
        if (!empty($foto) && file_exists('../../' . $foto)) {
            @unlink('../../' . $foto);
        }
        $ext = pathinfo($_FILES['foto_principal']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '_principal.' . $ext;
        $ruta = '../../assets/images/reportajes/' . $nombre;
        if (!is_dir('../../assets/images/reportajes/')) {
            mkdir('../../assets/images/reportajes/', 0777, true);
        }
        move_uploaded_file($_FILES['foto_principal']['tmp_name'], $ruta);
        $foto = 'assets/images/reportajes/' . $nombre;
    }
    
    if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] == 0) {
        if (!empty($pdf) && file_exists('../../' . $pdf)) {
            @unlink('../../' . $pdf);
        }
        $ext = pathinfo($_FILES['pdf_adjunto']['name'], PATHINFO_EXTENSION);
        $nombre = time() . '.' . $ext;
        $ruta = '../../assets/pdfs/' . $nombre;
        if (!is_dir('../../assets/pdfs/')) {
            mkdir('../../assets/pdfs/', 0777, true);
        }
        move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $ruta);
        $pdf = 'assets/pdfs/' . $nombre;
    }
    
    $sql = "UPDATE reportajes SET titulo=?, resumen_corto=?, desarrollo=?, foto_principal=?, pdf_adjunto=?, fecha_publicacion=?, es_destacado=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssii", $titulo, $resumen, $desarrollo, $foto, $pdf, $fecha, $destacado, $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/reportajes.php?mensaje=editado");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    $q = $conexion->prepare("SELECT foto_principal, pdf_adjunto FROM reportajes WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $data = $q->get_result()->fetch_assoc();
    $q->close();
    
    if ($data) {
        if (!empty($data['foto_principal']) && file_exists('../../' . $data['foto_principal'])) {
            @unlink('../../' . $data['foto_principal']);
        }
        if (!empty($data['pdf_adjunto']) && file_exists('../../' . $data['pdf_adjunto'])) {
            @unlink('../../' . $data['pdf_adjunto']);
        }
    }
    
    $sql = "DELETE FROM reportajes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ../modules/reportajes.php?mensaje=eliminado");
    exit();
}

if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $q = $conexion->prepare("SELECT * FROM reportajes WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $reportaje_editar = $q->get_result()->fetch_assoc();
    $q->close();
    if ($reportaje_editar) {
        $modo_edicion = true;
    }
}

if (isset($_GET['nuevo'])) {
    $mostrar_formulario = true;
}

$reportajes = $conexion->query("SELECT * FROM reportajes ORDER BY fecha_publicacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <base href="/DD/admin/template/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Reportajes</title>
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: '#editor-desarrollo',
        height: 450,
        menubar: true,
        plugins: [
            'advlist','autolink','lists','link','image','charmap','preview','anchor',
            'searchreplace','visualblocks','code','fullscreen','insertdatetime',
            'media','table','help','wordcount','emoticons','forecolor','backcolor'
        ],
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
                 'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | link image media table | ' +
                 'removeformat code fullscreen preview',
        content_style: 'body { font-family: Arial, sans-serif; font-size: 15px; }',
        branding: false
    });
    </script>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php if (isset($_GET['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php 
                            if ($_GET['mensaje'] == 'creado') echo 'Reportaje creado exitosamente.';
                            elseif ($_GET['mensaje'] == 'editado') echo 'Reportaje actualizado exitosamente.';
                            elseif ($_GET['mensaje'] == 'eliminado') echo 'Reportaje eliminado exitosamente.';
                            ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Gestión de Reportajes</h3>
                                <a href="../modules/reportajes.php?nuevo=1" class="btn btn-success">
                                    <i class="fas fa-plus-circle"></i> Nuevo Reportaje
                                </a>
                            </div>
                            
                            <div id="formulario" style="display:<?= ($modo_edicion || $mostrar_formulario) ? 'block' : 'none' ?>;" class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5><?= $modo_edicion ? 'Editar Reportaje' : 'Crear Nuevo Reportaje' ?></h5>
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="accion" value="<?= $modo_edicion ? 'editar' : 'crear' ?>">
                                            <?php if ($modo_edicion): ?>
                                                <input type="hidden" name="id" value="<?= $reportaje_editar['id'] ?>">
                                            <?php endif; ?>
                                            
                                            <div class="form-group">
                                                <label>Título *</label>
                                                <input type="text" name="titulo" class="form-control" required
                                                       value="<?= $modo_edicion ? htmlspecialchars($reportaje_editar['titulo']) : '' ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Resumen Corto</label>
                                                <input type="text" name="resumen_corto" class="form-control" maxlength="500"
                                                       value="<?= $modo_edicion ? htmlspecialchars($reportaje_editar['resumen_corto'] ?? '') : '' ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Desarrollo *</label>
                                                <textarea id="editor-desarrollo" name="desarrollo" class="form-control" rows="10"><?= $modo_edicion ? htmlspecialchars($reportaje_editar['desarrollo']) : '' ?></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Foto Principal</label>
                                                <input type="file" name="foto_principal" class="form-control" accept="image/*">
                                                <?php if ($modo_edicion && $reportaje_editar['foto_principal']): ?>
                                                    <small class="text-muted d-block mt-2">
                                                        Actual: <img src="../../<?= $reportaje_editar['foto_principal'] ?>" width="80" style="object-fit:cover;">
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>PDF Adjunto</label>
                                                <input type="file" name="pdf_adjunto" class="form-control" accept=".pdf">
                                                <?php if ($modo_edicion && $reportaje_editar['pdf_adjunto']): ?>
                                                    <small class="text-muted d-block mt-2">
                                                        Actual: <a href="../../<?= $reportaje_editar['pdf_adjunto'] ?>" target="_blank">Ver PDF</a>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Fecha de Publicación *</label>
                                                <input type="date" name="fecha_publicacion" class="form-control" required
                                                       value="<?= $modo_edicion ? $reportaje_editar['fecha_publicacion'] : date('Y-m-d') ?>">
                                            </div>
                                            
                                            <div class="form-group form-check">
                                                <input type="checkbox" name="es_destacado" class="form-check-input" id="destacado"
                                                       <?= ($modo_edicion && $reportaje_editar['es_destacado']) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="destacado">Destacar este reportaje</label>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">
                                                <?= $modo_edicion ? 'Actualizar' : 'Guardar' ?>
                                            </button>
                                            <a href="../modules/reportajes.php" class="btn btn-secondary">Cancelar</a>
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
                                                    <th>Destacado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($reportaje = $reportajes->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= $reportaje['id'] ?></td>
                                                    <td>
                                                        <?php if($reportaje['foto_principal'] && file_exists('../../' . $reportaje['foto_principal'])): ?>
                                                            <img src="../../<?= $reportaje['foto_principal'] ?>" width="50" height="50" style="object-fit:cover;">
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin foto</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars(substr($reportaje['titulo'], 0, 50)) . (strlen($reportaje['titulo']) > 50 ? '...' : '') ?></td>
                                                    <td><?= date('d/m/Y', strtotime($reportaje['fecha_publicacion'])) ?></td>
                                                    <td>
                                                        <?php if($reportaje['es_destacado']): ?>
                                                            <span class="badge badge-success">Sí</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">No</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="../modules/reportajes.php?editar=<?= $reportaje['id'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="../modules/reportajes.php?eliminar=<?= $reportaje['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este reportaje?')" title="Eliminar">
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