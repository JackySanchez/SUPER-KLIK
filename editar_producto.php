<?php
session_start();
if (!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }

$archivo = "maestro.txt";
$id_editar = $_GET['id'] ?? '';
$producto_encontrado = null;

// 1. Buscar el producto actual
if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $l) {
        $datos = explode("|", $l);
        if ($datos[0] == $id_editar) {
            $producto_encontrado = $datos;
            break;
        }
    }
}

// 2. Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_nombre = $_POST['nombre'];
    $nueva_cat = $_POST['categoria'];
    $nuevo_precio = $_POST['precio'];
    $nueva_cant = $_POST['cantidad'];
    
    // Determinar nuevo estado basado en tu lógica de Genesis
    $estado = "Disponible";
    if ($nueva_cant <= 0) $estado = "Agotado";
    elseif ($nueva_cant <= 20) $estado = "Crítico";
    elseif ($nueva_cant <= 40) $estado = "Intermedio";

    $nuevas_lineas = [];
    foreach ($lineas as $l) {
        $datos = explode("|", $l);
        if ($datos[0] == $id_editar) {
            // Reemplazamos con los datos nuevos
            $nuevas_lineas[] = "$id_editar|$nuevo_nombre|$nueva_cat|$nuevo_precio|$nueva_cant|$estado";
        } else {
            $nuevas_lineas[] = $l;
        }
    }
    
    file_put_contents($archivo, implode("\n", $nuevas_lineas) . "\n");
    header("Location: InventarioCompleto.php?msg=actualizado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto | KLIK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="panel-inventario">
    <h2>Editar Producto: <?php echo $id_editar; ?></h2>
    <form method="POST" class="login-form" style="max-width: 500px; margin: auto;">
        <label>Nombre del Producto</label>
        <input type="text" name="nombre" value="<?php echo $producto_encontrado[1]; ?>" required>
        
        <label>Categoría</label>
        <input type="text" name="categoria" value="<?php echo $producto_encontrado[2]; ?>" required>
        
        <label>Precio Unitario</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $producto_encontrado[3]; ?>" required>
        
        <label>Cantidad en Stock</label>
        <input type="number" name="cantidad" value="<?php echo $producto_encontrado[4]; ?>" required>
        
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" class="btn-acceder">GUARDAR CAMBIOS</button>
            <a href="InventarioCompleto.php" class="btn-regresar-top" style="background: #888;">CANCELAR</a>
        </div>
    </form>
</div>
</body>
</html>