<?php 
include "conexion.php";

$id=$_GET['id_proveedor'];

$sql="delete from proveedores where id_proveedor=$id";

$res=mysqli_query($cnx,$sql);

if($res){
	header("Location: proveedor.php");
}
?>