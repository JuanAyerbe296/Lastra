<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evitar acceso mediante caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Permitir solamente Administrador y Empleado
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol']) ||
    !in_array($_SESSION['rol'], ['Admin', 'Empleado'], true)
) {
    header("Location: ../index.html");
    exit();
}

// Solo aceptar solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: compra.php");
    exit();
}

include "conexion.php";

mysqli_set_charset($cnx, "utf8");

// Función para mostrar datos de forma segura
function e($valor) {
    return htmlspecialchars(
        (string)($valor ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

// Variables iniciales
$error = "";
$id_orden_generado = 0;
$num_orden = "";
$nombre_proveedor = "";
$documento_proveedor = "";
$fecha = "";
$descripciones = [];
$cantidades = [];
$precios = [];
$subtotal_general = 0;
$iva = 0;
$total_final = 0;

try {

    // 1. Validar proveedor
    $id_proveedor = filter_var(
        $_POST['id_proveedor'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id_proveedor || $id_proveedor < 1) {
        throw new Exception("Debes seleccionar un proveedor válido.");
    }

    // 2. Validar fecha
    $fecha = trim($_POST['fecha'] ?? '');

    $fecha_validada = DateTime::createFromFormat('!Y-m-d', $fecha);

    if (
        !$fecha_validada ||
        $fecha_validada->format('Y-m-d') !== $fecha
    ) {
        throw new Exception("La fecha de emisión no es válida.");
    }

    // 3. Validar arreglos recibidos
    if (
        !isset($_POST['descripcion'], $_POST['cantidad'], $_POST['precio']) ||
        !is_array($_POST['descripcion']) ||
        !is_array($_POST['cantidad']) ||
        !is_array($_POST['precio'])
    ) {
        throw new Exception("Los datos de los productos no son válidos.");
    }

    $descripciones = $_POST['descripcion'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio'];

    $cantidad_filas = count($descripciones);

    if (
        $cantidad_filas < 1 ||
        $cantidad_filas > 3 ||
        count($cantidades) !== $cantidad_filas ||
        count($precios) !== $cantidad_filas
    ) {
        throw new Exception("La cantidad de productos enviados no es válida.");
    }

    // 4. Validar cada producto y calcular subtotal
    $productos_validos = [];
    $subtotal_general = 0;

    for ($i = 0; $i < $cantidad_filas; $i++) {

        $desc = trim((string)$descripciones[$i]);
        $cant_original = trim((string)$cantidades[$i]);
        $precio_original = trim((string)$precios[$i]);

        // Permitir filas completamente vacías
        if (
            $desc === '' &&
            $cant_original === '' &&
            $precio_original === ''
        ) {
            continue;
        }

        // No permitir filas incompletas
        if (
            $desc === '' ||
            $cant_original === '' ||
            $precio_original === ''
        ) {
            throw new Exception(
                "Debes completar todos los datos de cada producto."
            );
        }

        // Validar cantidad
        $cant = filter_var(
            $cant_original,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($cant === false) {
            throw new Exception(
                "La cantidad de los productos debe ser un número entero mayor que cero."
            );
        }

        // Validar precio
        if (
            !is_numeric($precio_original) ||
            !is_finite((float)$precio_original) ||
            (float)$precio_original < 0
        ) {
            throw new Exception("El precio de un producto no es válido.");
        }

        $precio = round((float)$precio_original, 2);

        // Validar descripción
        if (strlen($desc) > 255) {
            throw new Exception(
                "La descripción de un producto es demasiado extensa."
            );
        }

        $total_linea = round($cant * $precio, 2);

        $subtotal_general += $total_linea;

        $productos_validos[] = [
            'descripcion' => $desc,
            'cantidad' => $cant,
            'precio' => $precio,
            'total_linea' => $total_linea
        ];
    }

    if (count($productos_validos) === 0) {
        throw new Exception("Debes ingresar al menos un producto.");
    }

    $subtotal_general = round($subtotal_general, 2);

    // 5. Verificar que el proveedor exista y esté activo
    $sql_prov = "SELECT razon_social, ruc
                 FROM proveedores
                 WHERE id_proveedor = ?
                 AND estado = 'activo'";

    $stmt_prov = $cnx->prepare($sql_prov);

    if (!$stmt_prov) {
        throw new Exception("No se pudo verificar el proveedor.");
    }

    $stmt_prov->bind_param("i", $id_proveedor);
    $stmt_prov->execute();

    $res_prov = $stmt_prov->get_result();
    $datos_proveedor = $res_prov->fetch_assoc();

    $stmt_prov->close();

    if (!$datos_proveedor) {
        throw new Exception(
            "El proveedor seleccionado no existe o se encuentra inactivo."
        );
    }

    $nombre_proveedor = $datos_proveedor['razon_social'];
    $documento_proveedor = $datos_proveedor['ruc'];

    // 6. Calcular IGV y total
    $tasa_igv = 0.18;

    $iva = round($subtotal_general * $tasa_igv, 2);

    $total_final = round($subtotal_general + $iva, 2);

    // 7. Generar código de orden
    $num_orden = "OC-" . random_int(1000, 9999);

    // 8. Iniciar transacción
    $cnx->begin_transaction();

    // A. Insertar encabezado de la orden
    $sql_orden = "INSERT INTO ordenes_compra
                  (codigo_orden, id_proveedor, fecha_emision, subtotal, igv, total)
                  VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $cnx->prepare($sql_orden);

    if (!$stmt) {
        throw new Exception("No se pudo preparar el registro de la orden.");
    }

    $stmt->bind_param(
        "siiddd",
        $num_orden,
        $id_proveedor,
        $fecha,
        $subtotal_general,
        $iva,
        $total_final
    );

    if (!$stmt->execute()) {
        throw new Exception("No se pudo guardar el encabezado de la orden.");
    }

    $id_orden_generado = $cnx->insert_id;

    $stmt->close();

    // B. Insertar los productos de la orden
    $sql_item = "INSERT INTO orden_items
                 (id_orden, descripcion, cantidad, precio_unitario, total_linea)
                 VALUES (?, ?, ?, ?, ?)";

    $stmt_item = $cnx->prepare($sql_item);

    if (!$stmt_item) {
        throw new Exception("No se pudieron preparar los detalles de la orden.");
    }

    foreach ($productos_validos as $producto) {

        $desc = $producto['descripcion'];
        $cant = $producto['cantidad'];
        $precio = $producto['precio'];
        $total_linea = $producto['total_linea'];

        $stmt_item->bind_param(
            "isidd",
            $id_orden_generado,
            $desc,
            $cant,
            $precio,
            $total_linea
        );

        if (!$stmt_item->execute()) {
            throw new Exception("No se pudo guardar un producto de la orden.");
        }
    }

    $stmt_item->close();

    // C. Confirmar la transacción
    $cnx->commit();

} catch (Throwable $ex) {

    // Revertir todos los cambios si algo falla
    try {
        $cnx->rollback();
    } catch (Throwable $rollback_error) {
        error_log("Error al revertir transacción: " . $rollback_error->getMessage());
    }

    error_log("Error al generar orden de compra: " . $ex->getMessage());

    $error = $ex->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $error ? 'Error en Orden de Compra' : 'Orden de Compra Guardada'; ?>
    </title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php if ($error !== ''): ?>

    <div class="factura-box">

        <div class="alert-danger">
            No se pudo generar la orden de compra.
            <br><br>
            <?php echo e($error); ?>
        </div>

        <a href="compra.php"
           style="text-decoration:none; color:#007bff;">
            Volver al formulario
        </a>

    </div>

<?php else: ?>

    <div class="factura-box">

        <div class="no-print" style="margin-bottom: 20px;">

            <div class="alert-success">
                ✓ Orden guardada correctamente en la base de datos
                (ID: <?php echo (int)$id_orden_generado; ?>)
            </div>

            <button onclick="window.print()" class="btn-imprimir">
                Imprimir Orden
            </button>

            <a href="compra.php"
               style="margin-left: 10px; text-decoration: none; color: #007bff;">
                Crear otra orden
            </a>

        </div>

        <table class="header-table">

            <tr>

                <td>
                    <h2>Platería Lastra S.A.</h2>

                    <p>
                        <strong>RUC:</strong> 20345678901
                        <br>
                        <strong>Dirección:</strong>
                        Lima, Jr. Ica #242 / Oficina #303 Camana Cailloma
                    </p>
                </td>

                <td class="text-right">

                    <h3 style="color: #007bff; margin-top:0;">
                        ORDEN DE COMPRA
                    </h3>

                    <p>
                        <strong>N°:</strong> <?php echo e($num_orden); ?>
                        <br>

                        <strong>Fecha:</strong>
                        <?php echo e(date("d/m/Y", strtotime($fecha))); ?>
                    </p>

                </td>

            </tr>

            <tr>

                <td colspan="2" style="padding-top: 15px; border-top: 1px solid #ccc;">

                    <strong>Proveedor:</strong>
                    <?php echo e($nombre_proveedor); ?>

                    <br>

                    <strong>N° RUC:</strong>
                    <?php echo e($documento_proveedor); ?>

                </td>

            </tr>

        </table>

        <br>

        <table class="items-table">

            <thead>

                <tr>
                    <th>Cant.</th>
                    <th>Descripción</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Total</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($productos_validos as $producto): ?>

                    <tr>

                        <td>
                            <?php echo (int)$producto['cantidad']; ?>
                        </td>

                        <td>
                            <?php echo e($producto['descripcion']); ?>
                        </td>

                        <td class="text-right">
                            $<?php echo number_format($producto['precio'], 2); ?>
                        </td>

                        <td class="text-right">
                            $<?php echo number_format($producto['total_linea'], 2); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="totals">

            <p>
                <strong>Subtotal:</strong>
                $<?php echo number_format($subtotal_general, 2); ?>
            </p>

            <p>
                <strong>IGV (18%):</strong>
                $<?php echo number_format($iva, 2); ?>
            </p>

            <h3>
                <strong>Total General:</strong>
                $<?php echo number_format($total_final, 2); ?>
            </h3>

        </div>

    </div>

<?php endif; ?>

</body>
</html>