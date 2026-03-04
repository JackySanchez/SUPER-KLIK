<?php
// Usamos require_once para evitar el error de "already in use"
require_once('fpdf/fpdf.php'); 

// Configuración de zona horaria para la hora correcta
date_default_timezone_set('America/Mexico_City');

session_start();
if (!isset($_SESSION['usuario'])) {
    die("Acceso denegado.");
}

class PDF extends FPDF {
    function Header() {
        if(file_exists('logo.jpeg')) {
            $this->Image('logo.jpeg', 10, 8, 20);
        }
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(0, 143, 17);
        $this->Cell(0, 10, utf8_decode('KLIK - REPORTE DE INVENTARIO'), 0, 1, 'C');
        $this->Ln(10);

        // Encabezado de la tabla
        $this->SetFillColor(0, 168, 150);
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 8, 'Codigo', 1, 0, 'C', true);
        $this->Cell(70, 8, 'Descripcion', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Cant.', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Precio U.', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Total', 1, 0, 'C', true);
        $this->Ln();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0);

$archivo = "maestro.txt";
$total_general = 0;
$hay_datos = false;

if (file_exists($archivo)) {
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $datos = explode("|", $linea);
        if (count($datos) >= 5) {
            $hay_datos = true;
            // Limpieza rigurosa de precios y cantidades
            $precio = (float)preg_replace('/[^0-9.]/', '', $datos[3]);
            $cantidad = (float)preg_replace('/[^0-9.]/', '', $datos[4]);
            $subtotal = $precio * $cantidad;
            $total_general += $subtotal;

            $pdf->Cell(30, 7, $datos[0], 1);
            $pdf->Cell(70, 7, utf8_decode(substr($datos[1], 0, 35)), 1);
            $pdf->Cell(30, 7, number_format($cantidad, 2), 1, 0, 'C');
            $pdf->Cell(30, 7, '$'.number_format($precio, 2), 1, 0, 'R');
            $pdf->Cell(30, 7, '$'.number_format($subtotal, 2), 1, 0, 'R');
            $pdf->Ln();
        }
    }
}

if (!$hay_datos) {
    $pdf->Cell(0, 10, utf8_decode("No se encontraron productos en el archivo maestro.txt"), 1, 0, 'C');
} else {
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(130);
    $pdf->Cell(60, 10, 'TOTAL GENERAL: $' . number_format($total_general, 2), 1, 0, 'C');
}

$pdf->Output('I', 'Reporte_Inventario_KLIK.pdf'); 

$carpeta_destino = "reportes/";
if (!file_exists($carpeta_destino)) { mkdir($carpeta_destino, 0777, true); }

$nombre_final = "Reporte_Inventario_" . date("His") . ".pdf";
$ruta_final = $carpeta_destino . $nombre_final;

// ESTO ES LO MÁS IMPORTANTE:
$pdf->Output('F', $ruta_final); // Lo guarda en la carpeta para que Reportes.php lo vea
$pdf->Output('I', $nombre_final); // Lo abre en tu pantalla