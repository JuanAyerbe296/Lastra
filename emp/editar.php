<?php 
include "conexion.php";

$id=$_GET['id'];
$c1=$_POST["nombre"];
$c2=$_POST["usuario"];
$c3=$_POST["password"];
$c4=$_POST["rol"];

$sql="update usuarios set nombre='$c1', usuario='$c2', password='$c3', rol='$c4' where id=$id";

$res=mysqli_query($cnx,$sql);

if($res){
	header("Location: empleado.php");
}
?>