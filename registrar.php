<?php 
include "conexion.php";

$c1 = $_POST["usuario"];
$c2 = $_POST["email"];
$c3 = $_POST["password"];

// Se asigna 'empleado' directamente por defecto como valor para el rol
$c4 = "Empleado";

// Consulta para insertar el usuario
$sql = "INSERT INTO usuarios VALUES('', '$c1', '$c2', '$c3', '$c4')";

$res = mysqli_query($cnx, $sql) or die(mysqli_error($cnx));

if ($res) {
    header("Location: index.html");
    exit();
}
?>