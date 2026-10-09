<?php 
include "conexion.php";

$id=$_GET['id'];

$sql="delete from usuarios where id=$id";

$res=mysqli_query($cnx,$sql);

if($res){
	header("Location: empleado.php");
}
?>