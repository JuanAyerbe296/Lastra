<?php
session_start();
include "conexion.php";

// 1. Verificar permisos de administrador
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol']) ||
    $_SESSION['rol'] !== 'Admin'
) {
    header("Location: ../index.html");
    exit();
}

// 2. Verificar método de solicitud
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Location: articulo.php");
    exit();
}

// 3. Validar código del artículo
$codigo = filter_input(INPUT_GET, "codigo", FILTER_VALIDATE_INT);

if ($codigo === false || $codigo === null || $codigo < 1) {
    die("Código de artículo no válido.");
}

// 4. Ocultar artículo mediante consulta preparada
$sql = "UPDATE articulo SET visible = 0 WHERE codigo = ?";

$stmt = mysqli_prepare($cnx, $sql);

if (!$stmt) {
    die("Error al preparar la operación.");
}

mysqli_stmt_bind_param($stmt, "i", $codigo);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    die("No se pudo ocultar el artículo.");
}

mysqli_stmt_close($stmt);

// 5. Regresar al listado
header("Location: articulo.php");
exit();
?>