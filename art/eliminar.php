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

// 2. Verificar que sea una solicitud GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Location: articulo.php");
    exit();
}

// 3. Validar código del artículo
$codigo = filter_var($_GET['codigo'] ?? null, FILTER_VALIDATE_INT);

if ($codigo === false || $codigo === null || $codigo < 1) {
    die("Código de artículo no válido.");
}

// 4. Eliminar mediante consulta preparada
$sql = "DELETE FROM articulo WHERE codigo = ?";

$stmt = mysqli_prepare($cnx, $sql);

if (!$stmt) {
    die("Error al preparar la eliminación.");
}

mysqli_stmt_bind_param($stmt, "i", $codigo);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: articulo.php");
    exit();
}

mysqli_stmt_close($stmt);

die("No se pudo eliminar el artículo.");
?>