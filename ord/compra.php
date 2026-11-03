<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evitar que se muestre la página desde la caché después de cerrar sesión
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Permitir únicamente Administrador y Empleado
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol']) ||
    !in_array($_SESSION['rol'], ['Admin', 'Empleado'], true)
) {
    header("Location: ../index.html");
    exit();
}

// Determinar el panel al que regresará cada usuario
$panelRegreso = ($_SESSION['rol'] === 'Admin')
    ? '../admin_panel.php'
    : '../empleado_panel.php';

// Conexión a la base de datos
include "conexion.php";

mysqli_set_charset($cnx, "utf8");

// Consultar proveedores activos
$sql_prov = "SELECT id_proveedor, razon_social
             FROM proveedores
             WHERE estado = 'activo'
             ORDER BY razon_social ASC";

$res_prov = mysqli_query($cnx, $sql_prov);

if (!$res_prov) {
    error_log("Error al consultar proveedores: " . mysqli_error($cnx));
    die("No se pudieron cargar los proveedores.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Orden de Compra</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<div class="container">

    <a href="<?php echo htmlspecialchars($panelRegreso, ENT_QUOTES, 'UTF-8'); ?>"
       class="btn btn-dark mb-2">
        Regresar
    </a>

    <a href="ordenes.php" class="btn btn-secondary mb-2">
        Listado
    </a>

    <a href="logout.php" class="btn btn-dark mb-2">
        Cerrar Sesión
    </a>

    <hr>

    <h3>Crear Orden de Compra</h3>

    <form action="generar_orden.php" method="POST">

        <!-- Datos Generales -->
        <div class="form-group">

            <label for="id_proveedor">Nombre del Proveedor:</label>

            <select name="id_proveedor"
                    id="id_proveedor"
                    required
                    class="form-control">

                <option value="">-- Seleccione un proveedor --</option>

                <?php while ($prov = mysqli_fetch_assoc($res_prov)): ?>

                    <option value="<?php echo (int)$prov['id_proveedor']; ?>">
                        <?php echo htmlspecialchars(
                            $prov['razon_social'],
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>
                    </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="form-group">

            <label for="fecha">Fecha de Emisión:</label>

            <input type="date"
                   id="fecha"
                   name="fecha"
                   required>

        </div>

        <!-- Detalle de Productos -->
        <h4>Detalle de Items</h4>

        <table>

            <thead>
                <tr>
                    <th>Descripción del Producto</th>
                    <th width="120">Cantidad</th>
                    <th width="160">Precio Unitario ($)</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>
                        <input type="text"
                               name="descripcion[]"
                               required
                               placeholder="Producto 1">
                    </td>

                    <td>
                        <input type="number"
                               name="cantidad[]"
                               min="1"
                               required
                               placeholder="1">
                    </td>

                    <td>
                        <input type="number"
                               name="precio[]"
                               step="0.01"
                               min="0"
                               required
                               placeholder="0.00">
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="text"
                               name="descripcion[]"
                               placeholder="Producto 2">
                    </td>

                    <td>
                        <input type="number"
                               name="cantidad[]"
                               min="1"
                               placeholder="1">
                    </td>

                    <td>
                        <input type="number"
                               name="precio[]"
                               step="0.01"
                               min="0"
                               placeholder="0.00">
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="text"
                               name="descripcion[]"
                               placeholder="Producto 3">
                    </td>

                    <td>
                        <input type="number"
                               name="cantidad[]"
                               min="1"
                               placeholder="1">
                    </td>

                    <td>
                        <input type="number"
                               name="precio[]"
                               step="0.01"
                               min="0"
                               placeholder="0.00">
                    </td>
                </tr>

            </tbody>

        </table>

        <button type="submit" class="btn btn-primary mb-2">
            Generar Orden de Compra
        </button>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>