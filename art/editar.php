<?php 
include "conexion.php";

$c1=$_GET['codigo'];

$ruta="img";
$archivo=$_FILES["imagen"]['tmp_name'];
$nombreArchivo=$_FILES["imagen"]['name'];

move_uploaded_file($archivo,$ruta."/".$nombreArchivo);
$ruta=$ruta."/".$nombreArchivo;

$c2=$_POST["nom"];
$c3=$_POST["cat"];
$c4=$_POST["tip"];
$c5=$_POST["sto"];
$c6=$_POST["pre"];

if($ruta=="img/"){
$sql="update articulo set nombre='$c2', categoria='$c3', tipo='$c4', stock='$c5', precio='$c6' where codigo=$c1";
}else{
$sql="update articulo set foto='$ruta', nombre='$c2', categoria='$c3', tipo='$c4', stock='$c5', precio='$c6' where codigo=$c1";
}
$res=mysqli_query($cnx,$sql);

if($res){
	header("Location: articulo.php");
}
?>