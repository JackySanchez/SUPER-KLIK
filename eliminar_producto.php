<?php
session_start();
if (!isset($_SESSION['usuario'])) { exit; }

$archivo = "maestro.txt";
$id_borrar = $_GET['id'] ?? '';

if (file_exists($archivo) && !empty($id_borrar)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $nuevas_lineas = [];
    
    foreach ($lineas as $l) {
        $datos = explode("|", $l);
        if ($datos[0] != $id_borrar) {
            $nuevas_lineas[] = $l;
        }
    }
    
    file_put_contents($archivo, implode("\n", $nuevas_lineas) . "\n");
}

header("Location: InventarioCompleto.php?msg=eliminado");
exit;