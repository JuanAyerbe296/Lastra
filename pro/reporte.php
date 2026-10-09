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
    $this->Cell(70,10,'Reporte de Proveedores',0,0,'C');
    // Salto de línea
   
    $this->Ln(30);
    $this->SetFont('Arial','B',10);
    $this->SetX(8);
    $this->Cell(15,10,'Codigo',1,0,'C',0);
    //$this->Image(40,10,'Foto',1,0,'C',0,);
    $this->Cell(50,10,utf8_decode('Razon Social'),1,0,'C',0);
    $this->Cell(30,10,'N° RUC',1,0,'C',0);
    $this->Cell(30,10,'Telefono',1,0,'C',0);
    $this->Cell(40,10,'Contacto',1,0,'C',0);
    $this->Cell(25,10,'Estado',1,1,'C',0);
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
$consulta = "SELECT proveedores.id_proveedor, proveedores.razon_social, proveedores.ruc, proveedores.telefono, proveedores.contacto_nombre, proveedores.estado FROM proveedores";
$resultado = mysqli_query($conexion, $consulta);

$pdf = new PDF();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);
//$pdf->SetWidths(array(10, 30, 27, 27, 20, 20, 20, 20, 22));
while ($row=$resultado->fetch_assoc()) {

    $pdf->SetX(8);

    $pdf->Cell(15,10,$row['id_proveedor'],1,0,'C',0);
    //$pdf->Image(40,10,$row['Foto'],1,0,'C',0);
	$pdf->Cell(50,10,$row['razon_social'],1,0,'C',0);
    $pdf->Cell(30,10,$row['ruc'],1,0,'C',0);
    $pdf->Cell(30,10,$row['telefono'],1,0,'C',0);
    $pdf->Cell(40,10,$row['contacto_nombre'],1,0,'C',0);
    $pdf->Cell(25,10,$row['estado'],1,1,'C',0);

} 

	$pdf->Output();

?>