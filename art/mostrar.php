<?php
include "conexion.php";

$codigo = filter_input(INPUT_GET, "codigo", FILTER_VALIDATE_INT);

if ($codigo !== false && $codigo !== null && $codigo > 0) {
    $sql = "UPDATE articulo SET visible = 1 WHERE codigo = ?";
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "i", $codigo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: articulo.php");
exit();
?>