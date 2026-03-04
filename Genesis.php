<?php
/* ==========================================
   Genesis.php - VERSIÓN FINAL KLIK
   Genera archivo maestro.txt con 10,000 registros
   y calcula automáticamente el estado según stock
========================================== */

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

/* ==========================================
   CONFIGURACIÓN INICIAL
========================================== */
$productos = ["Leche", "Pan", "Arroz", "Frijol", "Aceite", "Jugo", "Cereal", "Café", "Azúcar", "Sal"];
$categorias = ["Lácteos", "Panadería", "Granos", "Granos", "Despensa", "Bebidas", "Desayuno", "Despensa", "Despensa", "Despensa"];

$archivo_nombre = "maestro.txt";
$archivo = fopen($archivo_nombre, "w");

if (!$archivo) {
    die("Error: No se pudo crear el archivo maestro.txt");
}

/* ==========================================
   FUNCIÓN PARA DETERMINAR ESTADO
   (Sincronizada con filtrar.php y editar.php)
========================================== */
function determinarEstado($stock) {
    $stock = (int)$stock;

    if ($stock <= 0) {
        return "Agotado";
    }
    elseif ($stock <= 20) {
        return "Crítico";
    }
    else {
        return "Disponible";
    }
}

/* ==========================================
   GENERACIÓN DE REGISTROS (10,000)
========================================== */
echo "Generando datos, por favor espere...<br>";

for ($i = 1; $i <= 10000; $i++) {

    $id = str_pad($i, 5, "0", STR_PAD_LEFT);
    
    // Elegir producto y categoría aleatoria
    $indice = array_rand($productos);
    $nombre = $productos[$indice] . "_" . $i;
    $cat = $categorias[$indice];
    
    // Precio aleatorio entre 10.00 y 200.99
    $precio = number_format(rand(10, 200) + (rand(0, 99)/100), 2, '.', '');

    // Stock aleatorio entre 0 y 100 para que haya de todos los estados
    $stock = rand(0, 100);

    // Determinar estado basado en la nueva regla (Crítico hasta 20)
    $estado = determinarEstado($stock);

    // Escribir línea en el archivo
    fwrite($archivo, "$id|$nombre|$cat|$precio|$stock|$estado\n");
}

fclose($archivo);

echo "<strong>¡Éxito!</strong> El archivo <strong>maestro.txt</strong> ha sido generado con 10,000 registros.<br>";
echo "<a href='index.php'>Volver al Panel Principal</a>";
?>