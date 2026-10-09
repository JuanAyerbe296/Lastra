<?php
require_once ('fpdf/fpdf.php');
class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    //$this->image('../img/logo.png', 150, 1, 60); // X, Y, Tamaño
    $this->Ln(20);
    // Arial bold 15
    $this->SetFont('Arial','B',20);
  
    // Movernos a la derecha
    $this->Cell(65);

    // Título
    $this->Cell(70,10,'Reporte de Articulos',0,0,'C');
    // Salto de línea
   
    $this->Ln(30);
    $this->SetFont('Arial','B',10);
    $this->SetX(8);
    $this->Cell(30,10,'Codigo',1,0,'C',0);
    $this->Cell(40,10,'Nombre',1,0,'C',0);
    $this->Cell(40,10,'Categoria',1,0,'C',0);
    $this->Cell(40,10,'Tipo',1,0,'C',0);
    $this->Cell(20,10,'Stock',1,0,'C',0);
    $this->Cell(20,10,'Precio',1,1,'C',0);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
  
    $this->Cell(0,10,utf8_decode('Página') .$this->PageNo().'/{nb}',0,0,'C');
   //$this->SetFillColor(223, 229,235);
    //$this->SetDrawColor(181, 14,246);
    //$this->Ln(0.5);
}
}

$conexion=mysqli_connect("localhost","root","","lastra");
$consulta = "SELECT articulo.codigo, articulo.nombre, articulo.categoria, articulo.tipo, articulo.stock, articulo.precio FROM articulo";
$resultado = mysqli_query($conexion, $consulta);

$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);
//$pdf->SetWidths(array(10, 30, 27, 27, 20, 20, 20, 20, 22));
while ($row=$resultado->fetch_assoc()) {

    $pdf->SetX(8);

    $pdf->Cell(30,10,$row['codigo'],1,0,'C',0);
	$pdf->Cell(40,10,$row['nombre'],1,0,'C',0);
    $pdf->Cell(40,10,$row['categoria'],1,0,'C',0);
    $pdf->Cell(40,10,$row['tipo'],1,0,'C',0);
    $pdf->Cell(20,10,$row['stock'],1,0,'C',0);
    $pdf->Cell(20,10,$row['precio'],1,1,'C',0);

} 

	$pdf->Output();

?>