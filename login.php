<?php 
include "conexion.php";

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// 1. Buscamos al usuario sin restringir el rol en el SQL
$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$res = mysqli_query($cnx, $sql) or die(mysqli_error($cnx));

if (mysqli_num_rows($res) > 0) {
    session_start();
    
    // 2. Obtenemos los datos del usuario en un arreglo
    $row = mysqli_fetch_assoc($res);
    
    // Guardamos variables de sesión útiles
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['rol'] = $row['rol'];

    // 3. Redireccionamos según el valor del rol registrado
    switch ($row['rol']) {
        case 'Admin':
            header("Location: admin_panel.php");
            break;
            
        case 'Empleado':
            header("Location: empleado_panel.php");
            break;
            
        default:
            // Por si existe otro rol no contemplado
            header("Location: logout.php?error=rol_invalido");
            break;
    }
    exit(); // Detiene la ejecución del script tras redireccionar
} else {
    // Si las credenciales no son correctas
    header("Location: logout.php?error=datos_incorrectos");
    exit();
}
?>