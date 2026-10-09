<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_producto = (int)$_POST['id_producto'];
    $cliente_nombre = htmlspecialchars($_POST['cliente_nombre']);
    $cliente_email = htmlspecialchars($_POST['cliente_email']);
    $cliente_celular = htmlspecialchars($_POST['cliente_celular']);
    $cliente_direccion = htmlspecialchars($_POST['cliente_direccion']);
    $cantidad = (int)$_POST['cantidad'];

    // 1. Obtener la información actual del producto para verificar stock y precio
    $sql_prod = "SELECT precio, stock FROM articulo WHERE codigo = ?";
    $stmt_prod = $cnx->prepare($sql_prod);
    $stmt_prod->bind_param("i", $id_producto);
    $stmt_prod->execute();
    $res_prod = $stmt_prod->get_result();
    $prod = $res_prod->fetch_assoc();
    $stmt_prod->close();

    if (!$prod || $prod['stock'] < $cantidad) {
        die("Error: No hay suficiente stock disponible para procesar este pedido.");
    }

    $precio = (float)$prod['precio'];
    $total = $precio * $cantidad;

    // 2. Transacción SQL para asegurar consistencia
    $cnx->autocommit(FALSE);
    $exito = true;

    // A. Registrar el Pedido
    $sql_pedido = "INSERT INTO pedidos (cliente_nombre, cliente_email, cliente_celular, cliente_direccion, id_producto, cantidad, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_pedido = $cnx->prepare($sql_pedido);
    $stmt_pedido->bind_param("ssssiid", $cliente_nombre, $cliente_email, $cliente_celular, $cliente_direccion, $id_producto, $cantidad, $total);

    if (!$stmt_pedido->execute()) {
        $exito = false;
    }
    $stmt_pedido->close();

    // B. Actualizar / Restar el Stock del Artículo
    $nuevo_stock = $prod['stock'] - $cantidad;
    $sql_stock = "UPDATE articulo SET stock = ? WHERE codigo = ?";
    $stmt_stock = $cnx->prepare($sql_stock);
    $stmt_stock->bind_param("ii", $nuevo_stock, $id_producto);

    if (!$stmt_stock->execute()) {
        $exito = false;
    }
    $stmt_stock->close();

    // 3. Confirmar o Revertir los cambios
    if ($exito) {
        $cnx->commit();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Pedido Confirmado</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 40px; }
                .box { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; }
                .success { color: #28a745; }
                .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 15px; }
            </style>
        </head>
        <body>
            <div class="box">
                <h2 class="success">¡Gracias por tu compra!</h2>
                <p>Hemos recibido tu pedido para <strong><?php echo $cliente_nombre; ?></strong>.</p>
                <p><strong>Total abonado:</strong> $<?php echo number_format($total, 2); ?></p>
                <div style="text-align: center;">
                    <a href="catalogo.php" class="btn">Volver a la Tienda</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        $cnx->rollback();
        echo "Error al procesar el pedido. Por favor inténtelo nuevamente.";
    }

    $cnx->autocommit(TRUE);
} else {
    header("Location: detalle.php");
    exit();
}
?>