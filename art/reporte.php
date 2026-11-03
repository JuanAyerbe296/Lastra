<?php
session_start();
require_once "conexion.php";
require_once "fpdf/fpdf.php";

// 1. Verificar permisos de administrador
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol']) ||
    $_SESSION['rol'] !== 'Admin'
) {
    header("Location: ../index.html");
    exit();
}

// 2. Evitar guardar el reporte en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// 3. Configurar conexión
mysqli_set_charset($cnx, "utf8");

// 4. Crear clase PDF
class PDF extends FPDF
{
    function Header()
    {
        $this->Ln(20);

        $this->SetFont('Arial', 'B', 20);
        $this->Cell(65);
        $this->Cell(70, 10, 'Reporte de Articulos', 0, 0, 'C');

        $this->Ln(30);
        $this->SetFont('Arial', 'B', 10);
        $this->SetX(8);

        $this->Cell(30, 10, 'Codigo', 1, 0, 'C');
        $this->Cell(40, 10, 'Nombre', 1, 0, 'C');
        $this->Cell(40, 10, 'Categoria', 1, 0, 'C');
        $this->Cell(40, 10, 'Tipo', 1, 0, 'C');
        $this->Cell(20, 10, 'Stock', 1, 0, 'C');
        $this->Cell(20, 10, 'Precio', 1, 1, 'C');
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);

        $this->Cell(
            0,
            10,
            'Pagina ' . $this->PageNo() . '/{nb}',
            0,
            0,
            'C'
        );
    }
}

// 5. Consultar artículos
$sql = "SELECT codigo, nombre, categoria, tipo, stock, precio
        FROM articulo";

$resultado = mysqli_query($cnx, $sql);

if (!$resultado) {
    die("Error al consultar los artículos.");
}

// 6. Generar PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// Convertir texto UTF-8 al formato compatible con FPDF
function convertirTexto($texto)
{
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 7. Mostrar registros
while ($row = mysqli_fetch_assoc($resultado)) {

    $pdf->SetX(8);

    $pdf->Cell(30, 10, $row['codigo'], 1, 0, 'C');
    $pdf->Cell(40, 10, convertirTexto($row['nombre']), 1, 0, 'C');
    $pdf->Cell(40, 10, convertirTexto($row['categoria']), 1, 0, 'C');
    $pdf->Cell(40, 10, convertirTexto($row['tipo']), 1, 0, 'C');
    $pdf->Cell(20, 10, $row['stock'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['precio'], 1, 1, 'C');
}

mysqli_free_result($resultado);

// 8. Mostrar reporte
$pdf->Output('I', 'reporte_articulos.pdf');
exit();
?>