<?php 
include "conexion.php";

$id=$_GET['id_proveedor'];
$c1=$_POST["raz"];
$c2=$_POST["ruc"];
$c3=$_POST["tel"];
$c4=$_POST["dir"];
$c5=$_POST["con"];
$c6=$_POST["est"];

$sql="update proveedores set razon_social='$c1', ruc='$c2', telefono='$c3', direccion='$c4', contacto_nombre='$c5', estado='$c6' where id_proveedor=$id";

$res=mysqli_query($cnx,$sql);

if($res){
	header("Location: proveedor.php");
}
?>