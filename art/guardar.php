<?php 
include "conexion.php";

$ruta="img";
$archivo=$_FILES["imagen"]['tmp_name'];
$nombreArchivo=$_FILES["imagen"]['name'];

move_uploaded_file($archivo,$ruta."/".$nombreArchivo);
$ruta=$ruta."/".$nombreArchivo;

$c1=$_POST["nom"];
$c2=$_POST["cat"];
$c3=$_POST["tip"];
$c4=$_POST["sto"];
$c5=$_POST["pre"];

$sql="insert into articulo values('','$ruta','$c1','$c2','$c3','$c4','$c5')";

$res=mysqli_query($cnx,$sql);

if($res){
    header("Location: articulo.php");
}
?>