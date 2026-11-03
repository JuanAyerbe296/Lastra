<?php
session_start();
include "conexion.php";

// 1. Verificar permisos
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol']) ||
    $_SESSION['rol'] !== 'Admin'
) {
    header("Location: ../index.html");
    exit();
}

// 2. Verificar método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: articulo.php");
    exit();
}

// 3. Recibir datos
$nombre = trim($_POST["nom"] ?? "");
$categoria = $_POST["cat"] ?? "";
$tipo = $_POST["tip"] ?? "";
$stock = filter_var($_POST["sto"] ?? null, FILTER_VALIDATE_INT);
$precio = filter_var($_POST["pre"] ?? null, FILTER_VALIDATE_FLOAT);

// 4. Validar datos
$categorias = ["Animal", "Religioso", "Flores", "Letras", "Deporte", "Otros"];
$tipos = ["Dije", "Anillo", "Arete", "Pulsera"];

if (
    $nombre === "" ||
    mb_strlen($nombre) > 50 ||
    !in_array($categoria, $categorias, true) ||
    !in_array($tipo, $tipos, true) ||
    $stock === false || $stock === null || $stock < 1 ||
    $precio === false || $precio === null ||
    $precio < 0 || $precio > 99999999.99
) {
    die("Error: los datos del artículo no son válidos.");
}

// 5. Validar imagen
if (
    !isset($_FILES["imagen"]) ||
    $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK
) {
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

// 6. Comprobar formato real
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

// 7. Crear nombre aleatorio
$extension = $formatosPermitidos[$mime];
$nombreArchivo = bin2hex(random_bytes(16)) . "." . $extension;

$directorio = __DIR__ . "/img";

if (!is_dir($directorio) && !mkdir($directorio, 0755, true)) {
    die("No se pudo crear el directorio de imágenes.");
}

$rutaFisica = $directorio . "/" . $nombreArchivo;
$rutaBD = "img/" . $nombreArchivo;

// 8. Guardar imagen
if (!move_uploaded_file($archivoTemporal, $rutaFisica)) {
    die("No se pudo guardar la imagen.");
}

// 9. Insertar artículo
$sql = "INSERT INTO articulo
        (foto, nombre, categoria, tipo, stock, precio, visible)
        VALUES (?, ?, ?, ?, ?, ?, 1)";

$stmt = mysqli_prepare($cnx, $sql);

if (!$stmt) {
    unlink($rutaFisica);
    die("Error al preparar el registro.");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssid",
    $rutaBD,
    $nombre,
    $categoria,
    $tipo,
    $stock,
    $precio
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: articulo.php");
    exit();
}

mysqli_stmt_close($stmt);
unlink($rutaFisica);

die("No se pudo registrar el artículo.");
?>