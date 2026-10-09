<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_proveedor = htmlspecialchars($_POST['id_proveedor']);
    $fecha = $_POST['fecha'];
    $num_orden = "OC-" . rand(1000, 9999);

    $descripciones = $_POST['descripcion'];
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio'];

    // 1. Obtener el Nombre / Razón Social del proveedor desde la Base de Datos
    $sql_prov = "SELECT razon_social, ruc, telefono FROM proveedores WHERE id_proveedor = ?";
    $stmt_prov = $cnx->prepare($sql_prov);
    $stmt_prov->bind_param("i", $id_proveedor);
    $stmt_prov->execute();
    $res_prov = $stmt_prov->get_result();
    $datos_proveedor = $res_prov->fetch_assoc();
    $stmt_prov->close();

    // Guardar el nombre comercial o razón social en una variable para la boleta
    $nombre_proveedor = $datos_proveedor ? $datos_proveedor['razon_social'] : "Proveedor no encontrado";
    $documento_proveedor = $datos_proveedor ? $datos_proveedor['ruc'] : "";

    // 2. Calcular Totales
    $subtotal_general = 0;
    $tasa_iva = 0.18; // 18% IVA

    for ($i = 0; $i < count($descripciones); $i++) {
        if (!empty($descripciones[$i]) && !empty($cantidades[$i])) {
            $cant = (int)$cantidades[$i];
            $precio = (float)$precios[$i];
            $subtotal_general += ($cant * $precio);
        }
    }

    $iva = $subtotal_general * $tasa_iva;
    $total_final = $subtotal_general + $iva;

    // 3. Iniciar Transacción en MySQL
    $cnx->autocommit(FALSE);
    $exito = true;

    // A. Insertar Encabezado de la Orden
    $sql_orden = "INSERT INTO ordenes_compra (codigo_orden, id_proveedor, fecha_emision, subtotal, igv, total) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $cnx->prepare($sql_orden);
    $stmt->bind_param("siiddd", $num_orden, $id_proveedor, $fecha, $subtotal_general, $iva, $total_final);

    if ($stmt->execute()) {
        // Obtener el ID autonumérico generado para esta orden
        $id_orden_generado = $cnx->insert_id;
        $stmt->close();

        // B. Insertar cada uno de los Ítems/Productos
        $sql_item = "INSERT INTO orden_items (id_orden, descripcion, cantidad, precio_unitario, total_linea) 
                     VALUES (?, ?, ?, ?, ?)";
        $stmt_item = $cnx->prepare($sql_item);

        for ($i = 0; $i < count($descripciones); $i++) {
            if (!empty($descripciones[$i]) && !empty($cantidades[$i])) {
                $desc = htmlspecialchars($descripciones[$i]);
                $cant = (int)$cantidades[$i];
                $precio = (float)$precios[$i];
                $total_linea = $cant * $precio;

                $stmt_item->bind_param("isidd", $id_orden_generado, $desc, $cant, $precio, $total_linea);
                
                if (!$stmt_item->execute()) {
                    $exito = false;
                    break;
                }
            }
        }
        $stmt_item->close();
    } else {
        $exito = false;
    }

    // Confirmar o Revertir la Operación
    if ($exito) {
        $cnx->commit(); // Se guarda todo permanentemente
    } else {
        $cnx->rollback(); // Ocurrió un error, se cancela todo
        die("Error al guardar la orden de compra: " . $cnx->error);
    }

    $cnx->autocommit(TRUE); // Restaurar autocommit
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Orden de Compra Guardada - <?php echo $num_orden; ?></title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

    <div class="factura-box">
        <div class="no-print" style="margin-bottom: 20px; text-align: space-between;">
            <div class="alert-success">✓ Orden guardada correctamente en la base de datos (ID: <?php echo $id_orden_generado; ?>)</div>
            <button onclick="window.print()" class="btn-imprimir">Imprimir Orden</button>
            <a href="index.php" style="margin-left: 10px; text-decoration: none; color: #007bff;">Crear otra orden</a>
        </div>

        <table class="header-table">
            <tr>
                <td>
                    <h2>Platería Lastra S.A.</h2>
                    <p><strong>RUC:</strong> 20345678901<br><strong>Dirección:</strong> Lima, Jr. Ica #242 / Oficina #303 Camana Cailloma</p>
                </td>
                <td class="text-right">
                    <h3 style="color: #007bff; margin-top:0;">ORDEN DE COMPRA</h3>
                    <p><strong>N°:</strong> <?php echo $num_orden; ?><br>
                    <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($fecha)); ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 15px; border-top: 1px solid #ccc;">
                    <!-- AQUÍ SE MUESTRA EL NOMBRE Y RUC DEL PROVEEDOR EN LUGAR DEL ID -->
                    <strong>Proveedor:</strong> <?php echo htmlspecialchars($nombre_proveedor); ?><br>
                    <strong>N° RUC:</strong> <?php echo htmlspecialchars($documento_proveedor); ?>
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
                <?php
                for ($i = 0; $i < count($descripciones); $i++) {
                    if (!empty($descripciones[$i]) && !empty($cantidades[$i])) {
                        $cant = (int)$cantidades[$i];
                        $precio = (float)$precios[$i];
                        $total_linea = $cant * $precio;
                        ?>
                        <tr>
                            <td><?php echo $cant; ?></td>
                            <td><?php echo htmlspecialchars($descripciones[$i]); ?></td>
                            <td class="text-right">$<?php echo number_format($precio, 2); ?></td>
                            <td class="text-right">$<?php echo number_format($total_linea, 2); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="totals">
            <p><strong>Subtotal:</strong> $<?php echo number_format($subtotal_general, 2); ?></p>
            <p><strong>IGV (18%):</strong> $<?php echo number_format($iva, 2); ?></p>
            <h3><strong>Total General:</strong> $<?php echo number_format($total_final, 2); ?></h3>
        </div>
    </div>

    </body>
    </html>

    <?php
} else {
    header("Location: index.php");
    exit();
}
?>