<?php 
include "conexion.php";

$c1=$_POST["nombre"];
$c2=$_POST["usuario"];
$c3=$_POST["password"];
$c4=$_POST["rol"];

$sql="insert into usuarios values('','$c1','$c2','$c3','$c4')";

$res=mysqli_query($cnx,$sql);

if($res){
    header("Location: empleado.php");
}
?>