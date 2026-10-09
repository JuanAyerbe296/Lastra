<?php 
include "conexion.php";

$c1=$_GET['codigo'];

$sql="delete from articulo where codigo=$c1";

$res=mysqli_query($cnx,$sql);

if($res){
	header("Location: articulo.php");
}
?>