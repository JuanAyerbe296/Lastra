<?php
include "conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if ($usuario === '' || $password === '') {
    header("Location: logout.php?error=datos_incorrectos");
    exit();
}

// Buscar al usuario mediante una consulta preparada
$sql = "SELECT id, usuario, password, rol
        FROM usuarios
        WHERE usuario = ?";

$stmt = mysqli_prepare($cnx, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario);
mysqli_stmt_execute($stmt);

$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);

mysqli_stmt_close($stmt);

// Verificar la contraseña usando su hash
if ($row && password_verify($password, $row['password'])) {

    session_regenerate_id(true);

    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['rol'] = $row['rol'];

    switch ($row['rol']) {
        case 'Admin':
            header("Location: admin_panel.php");
            break;

        case 'Empleado':
            header("Location: empleado_panel.php");
            break;

        default:
            header("Location: logout.php?error=rol_invalido");
            break;
    }

    exit();

} else {
    header("Location: logout.php?error=datos_incorrectos");
    exit();
}
?>