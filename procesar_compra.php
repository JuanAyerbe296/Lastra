<?php
include "conexion.php";

// Activar excepciones para detectar errores de MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_producto = (int)($_POST['id_producto'] ?? 0);
    $cliente_nombre = trim($_POST['cliente_nombre'] ?? '');
    $cliente_email = trim($_POST['cliente_email'] ?? '');
    $cliente_celular = trim($_POST['cliente_celular'] ?? '');
    $cliente_direccion = trim($_POST['cliente_direccion'] ?? '');
    $cantidad = (int)($_POST['cantidad'] ?? 0);

    if ($cantidad < 1) {
        die("Error: La cantidad debe ser como mínimo 1.");
    }

    if ($id_producto < 1) {
        die("Error: Producto no válido.");
    }

    $transaccion_iniciada = false;

    try {

        // 1. Iniciar la transacción
        $cnx->begin_transaction();
        $transaccion_iniciada = true;

        // 2. Consultar y bloquear el producto
        $sql_prod = "SELECT precio, stock
                     FROM articulo
                     WHERE codigo = ?
                     FOR UPDATE";

        $stmt_prod = $cnx->prepare($sql_prod);
        $stmt_prod->bind_param("i", $id_producto);
        $stmt_prod->execute();

        $res_prod = $stmt_prod->get_result();
        $prod = $res_prod->fetch_assoc();
        $stmt_prod->close();

        // 3. Verificar existencia y stock
        if (!$prod) {
            throw new Exception("El producto no existe.");
        }

        if ($prod['stock'] < $cantidad) {
            throw new Exception(
                "No hay suficiente stock disponible para procesar este pedido."
            );
        }

        // 4. Calcular el total
        $precio = (float)$prod['precio'];
        $total = round($precio * $cantidad, 2);

        // 5. Registrar el pedido
        $sql_pedido = "INSERT INTO pedidos
            (cliente_nombre, cliente_email, cliente_celular,
             cliente_direccion, id_producto, cantidad, total)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt_pedido = $cnx->prepare($sql_pedido);

        $stmt_pedido->bind_param(
            "ssssiid",
            $cliente_nombre,
            $cliente_email,
            $cliente_celular,
            $cliente_direccion,
            $id_producto,
            $cantidad,
            $total
        );

        $stmt_pedido->execute();
        $stmt_pedido->close();

        // 6. Descontar el inventario
        $sql_stock = "UPDATE articulo
                      SET stock = stock - ?
                      WHERE codigo = ?
                      AND stock >= ?";

        $stmt_stock = $cnx->prepare($sql_stock);

        $stmt_stock->bind_param(
            "iii",
            $cantidad,
            $id_producto,
            $cantidad
        );

        $stmt_stock->execute();

        if ($stmt_stock->affected_rows !== 1) {
            $stmt_stock->close();
            throw new Exception("No fue posible actualizar el inventario.");
        }

        $stmt_stock->close();

        // 7. Confirmar ambas operaciones
        $cnx->commit();
        $transaccion_iniciada = false;

        // 8. Mostrar confirmación
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Pedido Confirmado</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f6f9;
                    padding: 40px;
                }

                .box {
                    max-width: 500px;
                    margin: auto;
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    text-align: center;
                }

                .success { color: #28a745; }

                .btn {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    margin-top: 15px;
                }
            </style>
        </head>
        <body>
            <div class="box">
                <h2 class="success">¡Gracias por tu compra!</h2>

                <p>
                    Hemos recibido tu pedido para
                    <strong><?php
                    echo htmlspecialchars(
                        $cliente_nombre,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?></strong>.
                </p>

                <p>
                    <strong>Total del pedido:</strong>
                    S/ <?php echo number_format($total, 2); ?>
                </p>

                <a href="catalogo.php" class="btn">
                    Volver a la Tienda
                </a>
            </div>
        </body>
        </html>
        <?php

    } catch (Throwable $e) {

        // Revertir cualquier cambio si ocurre un error
        if ($transaccion_iniciada) {
            $cnx->rollback();
        }

        // Mostrar el error esperado sin revelar detalles internos
        if ($e instanceof Exception &&
            $e->getMessage() ===
            "No hay suficiente stock disponible para procesar este pedido.") {

            die("Error: " . $e->getMessage());
        }

        if ($e instanceof Exception &&
            $e->getMessage() === "El producto no existe.") {

            die("Error: " . $e->getMessage());
        }

        error_log("Error al procesar pedido: " . $e->getMessage());

        echo "Error al procesar el pedido. Por favor, inténtelo nuevamente.";
    }

} else {
    header("Location: detalle.php");
    exit();
}
?>