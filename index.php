<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); // Si NO hay sesión, mándalo al login
    exit;
}
// Aquí iría tu lógica para contar los productos del maestro.txt
$total = 10000; 
$criticos = 2495;
$agotados = 139;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control | KLIK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="panel-container">
    <header class="panel-header">
        <h1>SISTEMA DE GESTIÓN KLIK</h1>
        <p>Bienvenido, <strong><?php echo $_SESSION['usuario']; ?></strong></p>
    </header>

    <div class="grid-acciones">
        <a href="InventarioCompleto.php" class="card-accion">
            <div class="card-icon">📦</div>
            <div class="card-text">
                <h3>INVENTARIO COMPLETO</h3>
                <p>Base de datos maestra</p>
            </div>
        </a>

        <a href="filtrar.php" class="card-accion">
            <div class="card-icon">🔍</div>
            <div class="card-text">
                <h3>FILTRAR PRODUCTOS</h3>
                <p>Búsqueda inteligente</p>
            </div>
        </a>

        <a href="reportes.php" class="card-accion">
            <div class="card-icon">📊</div>
            <div class="card-text">
                <h3>REPORTES GENERADOS</h3>
                <p>Archivos y estadísticas</p>
            </div>
        </a>

        <a href="logout.php" class="card-accion">
            <div class="card-icon">🚪</div>
            <div class="card-text">
                <h3>CERRAR SESIÓN</h3>
                <p>Salir del sistema</p>
            </div>
        </a>
    </div>

    <div class="panel-info">
        <h3>Estado del Inventario</h3>
        <div class="grid-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo $total; ?></span>
                <span class="stat-label">Total de Productos</span>
            </div>
            <div class="stat-item critico">
                <span class="stat-number"><?php echo $criticos; ?></span>
                <span class="stat-label">Productos Críticos</span>
            </div>
            <div class="stat-item agotado">
                <span class="stat-number"><?php echo $agotados; ?></span>
                <span class="stat-label">Productos Agotados</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>