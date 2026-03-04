<?php
// Usamos require_once para evitar el error de "already in use"
require_once('fpdf/fpdf.php'); 

// Configuración de zona horaria para la hora correcta
date_default_timezone_set('America/Mexico_City');

session_start();

$filtro = $_GET['criterio'] ?? 'todos';
$titulo_reporte = "REPORTE: " . strtoupper($filtro);

class PDF extends FPDF {
    function Header() {
        global $titulo_reporte;
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode("KLIK SISTEMAS - $titulo_reporte"), 0, 1, 'C');
        $this->Ln(5);
        $this->SetFillColor(0, 168, 150);
        $this->SetTextColor(255);
        $this->Cell(40, 8, 'Codigo', 1, 0, 'C', true);
        $this->Cell(100, 8, 'Descripcion', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Cantidad', 1, 1, 'C', true);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0);

$archivo = "maestro.txt";
if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $datos = explode("|", $linea);
        $cant = (float)$datos[4];
        
        $mostrar = false;
        if ($filtro == 'agotados' && $cant <= 0) $mostrar = true;
        if ($filtro == 'criticos' && $cant > 0 && $cant <= 20) $mostrar = true;
        if ($filtro == 'todos') $mostrar = true;

        if ($mostrar) {
            $pdf->Cell(40, 7, $datos[0], 1);
            $pdf->Cell(100, 7, utf8_decode($datos[1]), 1);
            $pdf->Cell(50, 7, $cant, 1, 1, 'C');
        }
    }
}

$pdf->Output('I', "Reporte_$filtro.pdf");

$carpeta_destino = "reportes/";
if (!file_exists($carpeta_destino)) { mkdir($carpeta_destino, 0777, true); }

$nombre_final = "Reporte_Inventario_" . date("His") . ".pdf";
$ruta_final = $carpeta_destino . $nombre_final;

// ESTO ES LO MÁS IMPORTANTE:
$pdf->Output('F', $ruta_final); // Lo guarda en la carpeta para que Reportes.php lo vea
$pdf->Output('I', $nombre_final); // Lo abre en tu pantalla