<?php
session_start();

// INICIALIZACIÓN (Esto borra los errores de las líneas 37, 42 y 65)
$archivo = "maestro.txt";
$productos = [];
$valor_total = 0;
$total_articulos = 0;

if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $datos = explode("|", $linea);
        if (count($datos) >= 5) {
            $productos[] = $datos;
            $precio = (float)str_replace(['$', ','], '', $datos[3]);
            $cantidad = (float)str_replace(',', '', $datos[4]);
            $valor_total += ($precio * $cantidad);
            $total_articulos += $cantidad;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario | KLIK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="panel-inventario">
    <header class="header-empresa">
        <div class="info-logo">
            <img src="logo.jpeg" alt="Logo" class="logo-small">
            <div class="texto-logo">
                <h2>KLIK Sistemas Web</h2>
                <p>Gestión de Inventarios v2.0</p>
            </div>
        </div>

        <div class="acciones-header">
            <a href="index.php" class="btn-regresar-top">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                VOLVER AL PANEL
            </a>
        </div>
    </header>

    <section class="resumen-valores">
        <div class="dato-resumen">
            <span class="titulo-dato">VALOR TOTAL DEL INVENTARIO:</span>
            <span class="valor-dato">$ <?php echo number_format($valor_total, 2); ?></span>
        </div>
        
        <div class="separador-v"></div>
        
        <div class="dato-resumen">
            <span class="titulo-dato">ARTÍCULOS EN EXISTENCIA:</span>
            <span class="valor-dato"><?php echo number_format($total_articulos, 2); ?></span>
        </div>

        <div class="contenedor-boton-pdf">
            <a href="generar_reporte.php" class="btn-generar-reporte">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 16l4-4h-3V4h-2v8H8l4 4zm9 4H3v-2h18v2z"/>
                </svg>
                DESCARGAR REPORTE PDF
            </a>
        </div>
    </section>

    <table class="tabla-klik">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Costo Unit.</th>
                <th>Costo Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $p): 
                    $cu = (float)str_replace(['$', ','], '', $p[3]);
                    $ca = (float)str_replace(',', '', $p[4]);
                ?>
                <tr>
    <td><strong><?php echo htmlspecialchars($p[0]); ?></strong></td>
    <td><?php echo htmlspecialchars($p[1]); ?></td>
    <td><?php echo htmlspecialchars($p[2]); ?></td>
    <td><?php echo number_format($ca, 0); ?></td>
    <td>$<?php echo number_format($cu, 2); ?></td>
    <td>$<?php echo number_format($cu * $ca, 2); ?></td>
    <td>
        <div style="display: flex; gap: 10px;">
            <a href="editar_producto.php?id=<?php echo $p[0]; ?>" title="Editar" style="text-decoration:none;">✏️</a>
            
            <a href="eliminar_producto.php?id=<?php echo $p[0]; ?>" 
               onclick="return confirm('¿Seguro que deseas eliminar este producto?')" 
               title="Eliminar" style="text-decoration:none;">🗑️</a>
        </div>
    </td>
</tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">No hay datos en maestro.txt</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <a href="index.php" class="btn-volver">← Volver al Panel</a>
    </div>
</div>

</body>
</html>