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

// 2. Verificar que sea una solicitud POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: articulo.php");
    exit();
}

// 3. Validar código del artículo
$codigo = filter_var($_GET['codigo'] ?? null, FILTER_VALIDATE_INT);

if ($codigo === false || $codigo === null || $codigo < 1) {
    die("Código de artículo no válido.");
}

// 4. Recibir datos
$nombre = trim($_POST["nom"] ?? "");
$categoria = $_POST["cat"] ?? "";
$tipo = $_POST["tip"] ?? "";
$stock = filter_var($_POST["sto"] ?? null, FILTER_VALIDATE_INT);
$precio = filter_var($_POST["pre"] ?? null, FILTER_VALIDATE_FLOAT);

// 5. Validar datos
$categorias = ["Animal", "Religioso", "Flores", "Letras", "Deporte", "Otros"];
$tipos = ["Dije", "Anillo", "Arete", "Pulsera"];

if (
    $nombre === "" ||
    mb_strlen($nombre) > 50 ||
    !in_array($categoria, $categorias, true) ||
    !in_array($tipo, $tipos, true) ||
    $stock === false || $stock === null || $stock < 0 ||
    $precio === false || $precio === null ||
    $precio < 0 || $precio > 99999999.99
) {
    die("Error: los datos del artículo no son válidos.");
}

// 6. Obtener la imagen actual
$sql = "SELECT foto FROM articulo WHERE codigo = ?";
$stmt = mysqli_prepare($cnx, $sql);
mysqli_stmt_bind_param($stmt, "i", $codigo);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$articulo = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$articulo) {
    die("El artículo no existe.");
}

$rutaBD = $articulo['foto'];
$rutaFisicaNueva = null;

// 7. Comprobar si se seleccionó una imagen nueva
if (
    isset($_FILES["imagen"]) &&
    $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        die("Error al cargar la imagen.");
    }

    $archivoTemporal = $_FILES["imagen"]["tmp_name"];
    $tamano = $_FILES["imagen"]["size"];

    if ($tamano <= 0 || $tamano > 2 * 1024 * 1024) {
        die("La imagen debe pesar como máximo 2 MB.");
    }

    if (@getimagesize($archivoTemporal) === false) {
        die("El archivo no es una imagen válida.");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($archivoTemporal);

    $formatosPermitidos = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    if (!isset($formatosPermitidos[$mime])) {
        die("Formato no permitido. Utiliza JPG, PNG o WEBP.");
    }

    // Nombre aleatorio para evitar nombres peligrosos o repetidos
    $extension = $formatosPermitidos[$mime];
    $nombreArchivo = bin2hex(random_bytes(16)) . "." . $extension;

    $directorio = __DIR__ . "/img";

    if (!is_dir($directorio) && !mkdir($directorio, 0755, true)) {
        die("No se pudo crear el directorio de imágenes.");
    }

    $rutaFisicaNueva = $directorio . "/" . $nombreArchivo;
    $rutaBD = "img/" . $nombreArchivo;

    if (!move_uploaded_file($archivoTemporal, $rutaFisicaNueva)) {
        die("No se pudo guardar la imagen.");
    }
}

// 8. Actualizar artículo mediante consulta preparada
$sql = "UPDATE articulo
        SET foto = ?, nombre = ?, categoria = ?, tipo = ?,
            stock = ?, precio = ?
        WHERE codigo = ?";

$stmt = mysqli_prepare($cnx, $sql);

if (!$stmt) {
    if ($rutaFisicaNueva !== null) {
        unlink($rutaFisicaNueva);
    }
    die("Error al preparar la actualización.");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssidi",
    $rutaBD,
    $nombre,
    $categoria,
    $tipo,
    $stock,
    $precio,
    $codigo
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    // Eliminar imagen anterior solamente si se guardó una nueva
    if ($rutaFisicaNueva !== null && !empty($articulo['foto'])) {
        $imagenAnterior = __DIR__ . "/" . $articulo['foto'];

        if (is_file($imagenAnterior)) {
            unlink($imagenAnterior);
        }
    }

    header("Location: articulo.php");
    exit();
}

mysqli_stmt_close($stmt);

if ($rutaFisicaNueva !== null) {
    unlink($rutaFisicaNueva);
}

die("No se pudo actualizar el artículo.");
?>