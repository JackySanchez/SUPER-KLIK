<?php
date_default_timezone_set('America/Mexico_City');
// ... resto del código
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }

// Carpeta donde se guardan los PDF
$carpeta = "reportes/";

// Crear la carpeta si no existe
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$archivos_pdf = [];
// Escanear la carpeta en busca de archivos .pdf
$files = glob($carpeta . "*.pdf");

if ($files) {
    foreach ($files as $ruta_completa) {
        $nombre_archivo = basename($ruta_completa);
        $timestamp = filemtime($ruta_completa);
        $archivos_pdf[] = [
            'nombre' => $nombre_archivo,
            'fecha' => date("d/m/Y", $timestamp),
            'hora' => date("H:i:s", $timestamp),
            'ruta' => $ruta_completa
        ];
    }
}

// Lógica para eliminar
if (isset($_GET['eliminar'])) {
    $file_to_delete = $carpeta . basename($_GET['eliminar']);
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
    }
    header("Location: Reportes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Reportes | KLIK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="panel-inventario">
    <header class="header-empresa">
        <div class="info-logo">
            <img src="logo.jpeg" alt="Logo" class="logo-small">
            <h2>Historial de Reportes</h2>
        </div>
        <a href="index.php" class="btn-regresar-top">VOLVER AL PANEL</a>
    </header>

    <table class="tabla-klik">
        <thead>
            <tr>
                <th>Nombre del Reporte</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($archivos_pdf)): ?>
                <tr><td colspan="4" style="text-align:center;">No hay reportes en la carpeta /reportes/</td></tr>
            <?php else: ?>
                <?php foreach ($archivos_pdf as $pdf): ?>
                <tr>
                    <td><strong><?php echo $pdf['nombre']; ?></strong></td>
                    <td><?php echo $pdf['fecha']; ?></td>
                    <td><?php echo $pdf['hora']; ?></td>
                    <td>
                        <a href="<?php echo $pdf['ruta']; ?>" target="_blank" style="margin-right:10px; color:green; text-decoration:none;">👁 Ver</a>
                        <a href="Reportes.php?eliminar=<?php echo $pdf['nombre']; ?>" onclick="return confirm('¿Eliminar?')" style="color:red; text-decoration:none;">🗑 Borrar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>