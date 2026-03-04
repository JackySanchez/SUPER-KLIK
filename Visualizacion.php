<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados del Filtro | Supermercado</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

<div class="container">
    <h1>📊 Resultados del Filtro</h1>
    <p class="subtitle">Visualización de datos filtrados (Solo Lectura)</p>

<?php
// Validar que el archivo exista
if (file_exists("filtrado.txt")) {
    $archivo = fopen("filtrado.txt", "r");
    $filas = [];
    $criticos = 0;

    // Procesar archivo línea a línea para alta eficiencia con 10,000+ registros
    while (($linea = fgets($archivo)) !== false) {
        $datos = explode("|", trim($linea));
        // Contar críticos para el aviso visual
        if (isset($datos[5]) && $datos[5] === "CRITICO") {
            $criticos++;
        }
        $filas[] = $datos;
    }
    fclose($archivo);

    if (count($filas) > 0) {
        // Alerta visual de atención
        echo "<div class='info-box' style='background: #fee2e2; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;'>
                <strong>⚠️ Productos que requieren atención: $criticos</strong>
              </div>";
?>
        <table>
            <tr>
                <th>ID</th><th>Producto</th><th>Categoría</th>
                <th>Precio</th><th>Stock</th><th>Estado</th>
            </tr>
            <?php
            foreach ($filas as $fila) {
                echo "<tr>";
                foreach ($fila as $dato) {
                    echo "<td>" . htmlspecialchars($dato) . "</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
<?php
    } else {
        echo "<p style='text-align:center;'>No se encontraron resultados para este filtro.</p>";
    }
} else {
    echo "<p style='text-align:center;'>No hay datos filtrados. Por favor, realiza una búsqueda.</p>";
}
?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="index.php" class="btn-volver">← Regresar al Inicio</a>
    </div>
</div>

</body>
</html>