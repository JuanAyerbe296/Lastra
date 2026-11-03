<?php
// Iniciar y proteger la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Permitir únicamente Administrador y Empleado
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol']) ||
    !in_array($_SESSION['rol'], ['Admin', 'Empleado'], true)
) {
    header("Location: index.html");
    exit();
}

include "conexion.php";

// Configurar caracteres de la conexión
mysqli_set_charset($cnx, "utf8");

// Consulta para obtener los pedidos
$sql = "SELECT 
            ar.nombre AS producto,
            pe.cliente_nombre,
            pe.cliente_celular,
            pe.cliente_direccion,
            pe.cantidad,
            pe.total,
            pe.fecha_pedido
        FROM pedidos pe
        INNER JOIN articulo ar ON pe.id_producto = ar.codigo
        ORDER BY pe.fecha_pedido DESC";

$resultado = $cnx->query($sql);

if (!$resultado) {
    error_log("Error al consultar pedidos: " . $cnx->error);
    die("No se pudo cargar el listado de pedidos.");
}

// Función para mostrar datos de forma segura
function e($valor) {
    return htmlspecialchars(
        (string)($valor ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>

    <!-- Estilos de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container my-5">

    <h2 class="mb-4 text-center">Listado de Pedidos</h2>

    <nav class="navbar bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid justify-content-center">
            <form class="d-flex" role="search" method="POST">
                <input
                    name="buscador"
                    placeholder="Buscar con JS"
                    id="codigo"
                    class="form-control me-2 light-table-filter"
                    type="search"
                    aria-label="Buscar"
                    data-table="table_id"
                >

                <button name="buscador" class="btn btn-outline-light" type="submit">
                    Buscar
                </button>
            </form>
        </div>
    </nav>

    <br>

    <div class="table-responsive shadow-sm bg-white p-3 rounded">

        <table class="table table-striped table-hover align-middle table_id" id="tblDatos">

            <thead class="table-dark">
                <tr>
                    <th class="text-center">Nombres y Apellidos</th>
                    <th class="text-center">Num. Celular</th>
                    <th class="text-center">Dirección</th>
                    <th class="text-center">Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Fecha Pedido</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($resultado->num_rows > 0): ?>

                    <?php while ($row = $resultado->fetch_assoc()): ?>

                        <tr>
                            <td class="text-center">
                                <?php echo e($row['cliente_nombre']); ?>
                            </td>

                            <td class="text-center">
                                <?php echo e($row['cliente_celular']); ?>
                            </td>

                            <td class="text-center">
                                <?php echo e($row['cliente_direccion']); ?>
                            </td>

                            <td class="text-center">
                                <?php echo e($row['producto']); ?>
                            </td>

                            <td class="text-center">
                                <?php echo (int)$row['cantidad']; ?>
                            </td>

                            <td class="text-center fw-bold text-success">
                                S/ <?php echo number_format((float)$row['total'], 2); ?>
                            </td>

                            <td class="text-center">
                                <?php
                                $fecha = strtotime($row['fecha_pedido']);

                                echo $fecha
                                    ? date('d/m/Y h:i:s A', $fecha)
                                    : 'Fecha no válida';
                                ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No hay pedidos registrados.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <br>

    <div id="paginador" class="pagination justify-content-center"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0t0X3lHB60NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>

<script type="text/javascript" src="js/page.js"></script>
<script type="text/javascript" src="js/buscador.js"></script>

</body>
</html>