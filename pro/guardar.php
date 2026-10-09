<?php 
include "conexion.php";

$c1 = $_POST["raz"];
$c2 = $_POST["ruc"];
$c3 = $_POST["tel"];
$c4 = $_POST["dir"];
$c5 = $_POST["con"];
$c6 = $_POST["est"];

// 1. Consultar si el RUC ya existe
$buscarRuc = "SELECT * FROM proveedores WHERE ruc = '$c2'";
$resultado = mysqli_query($cnx, $buscarRuc);

if (mysqli_num_rows($resultado) > 0) {
    // Si encuentra registros, muestra alerta y regresa a la página anterior
    echo "<script>
            alert('El RUC $c2 ya se encuentra registrado.');
            window.history.back();
          </script>";
    exit();
}

// 2. Si no existe, realiza el registro
$sql = "INSERT INTO proveedores VALUES('', '$c1', '$c2', '$c3', '$c4', '$c5', '$c6')";
$res = mysqli_query($cnx, $sql);

if ($res) {
    header("Location: proveedor.php");
    exit();
} else {
    echo "Error al registrar: " . mysqli_error($cnx);
}
?>