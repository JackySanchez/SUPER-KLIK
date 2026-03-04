<?php
// Persistencia.php - Motor de filtrado nativo en PHP
// Este script lee el criterio guardado en criterio.txt y filtra maestro.txt

if (file_exists("criterio.txt") && file_exists("maestro.txt")) {
    
    // 1. Leer el criterio de búsqueda
    $criterio = trim(file_get_contents("criterio.txt"));
    
    // 2. Abrir archivo maestro para lectura y filtrado para escritura
    $archivo_maestro = fopen("maestro.txt", "r");
    $archivo_filtrado = fopen("filtrado.txt", "w");
    
    // 3. Procesar línea por línea para no saturar la memoria (ideal para 10,000 registros)
    while (($linea = fgets($archivo_maestro)) !== false) {
        $linea = trim($linea);
        if (empty($linea)) continue;
        
        $datos = explode("|", $linea);
        
        // El estado está en la última columna (índice 5)
        if (isset($datos[5]) && trim($datos[5]) === $criterio) {
            fwrite($archivo_filtrado, $linea . "\n");
        }
    }
    
    fclose($archivo_maestro);
    fclose($archivo_filtrado);
}
?>