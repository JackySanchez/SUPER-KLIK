<?php
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }

$filtro = $_GET['criterio'] ?? 'todos';
$archivo = "maestro.txt";
$productos_filtrados = [];

if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $datos = explode("|", $linea);
        if (count($datos) >= 5) {
            $cantidad = (float)str_replace(',', '', $datos[4]);
            
            // Lógica de filtrado unificada con Genesis.php
if ($filtro == 'agotados' && $cantidad <= 0) {
    $productos_filtrados[] = $datos;
} elseif ($filtro == 'criticos' && $cantidad > 0 && $cantidad <= 20) { // Cambiado de 10 a 20
    $productos_filtrados[] = $datos;
} elseif ($filtro == 'todos') {
    $productos_filtrados[] = $datos;
}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Filtrar Inventario | KLIK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="panel-inventario">
    <header class="header-empresa">
        <div class="info-logo">
            <img src="logo.jpeg" alt="Logo" class="logo-small">
            <h2>Filtrado Inteligente</h2>
        </div>
        <a href="index.php" class="btn-regresar-top">VOLVER AL PANEL</a>
    </header>

    <section class="resumen-valores">
        <form method="GET" action="filtrar.php" style="display:flex; align-items:center; gap:15px; flex:1;">
            <label><strong>MOSTRAR:</strong></label>
            <select name="criterio" onchange="this.form.submit()" style="padding:10px; border-radius:5px; border:1px solid #ccc;">
                <option value="todos" <?php echo $filtro == 'todos' ? 'selected' : ''; ?>>Todo el Inventario</option>
                <option value="criticos" <?php echo $filtro == 'criticos' ? 'selected' : ''; ?>>Stock Crítico (1-20)</option>
                <option value="agotados" <?php echo $filtro == 'agotados' ? 'selected' : ''; ?>>Agotados (0)</option>
            </select>
        </form>

        <div class="contenedor-boton-pdf">
            <a href="generar_reporte_filtrado.php?criterio=<?php echo $filtro; ?>" class="btn-generar-reporte">
                📄 EXPORTAR ESTA LISTA A PDF
            </a>
        </div>
    </section>

    <table class="tabla-klik">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th style="text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($productos_filtrados)): ?>
                <tr><td colspan="5" style="text-align:center; padding:20px;">No se encontraron productos con este filtro.</td></tr>
            <?php else: ?>
                <?php foreach ($productos_filtrados as $p): 
                    $cant = (float)$p[4];
                    // Colores según el estado
                    $estilo_status = '';
                    if ($cant <= 0) $estilo_status = 'color: #e31d2b; font-weight: bold;'; // Rojo Agotado
                    elseif ($cant <= 20) $estilo_status = 'color: #f39c12; font-weight: bold;'; // Naranja Crítico
                ?>
                <tr>
                    <td><strong><?php echo $p[0]; ?></strong></td>
                    <td><?php echo $p[1]; ?></td>
                    <td style="<?php echo $estilo_status; ?>"><?php echo $cant; ?></td>
                    <td>
                        <span style="font-size: 0.85rem; padding: 2px 8px; border-radius: 4px; border: 1px solid #ccc;">
                            <?php echo $p[5]; ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 15px;">
                            <a href="editar_producto.php?id=<?php echo $p[0]; ?>" title="Editar Producto" style="text-decoration: none; font-size: 1.2rem;">✏️</a>
                            
                            <a href="eliminar_producto.php?id=<?php echo $p[0]; ?>" 
                               onclick="return confirm('¿Estás seguro de eliminar este producto del inventario?')" 
                               title="Eliminar Producto" style="text-decoration: none; font-size: 1.2rem;">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>

</html>
