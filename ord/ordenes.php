<?php
include "conexion.php";

// Consulta SQL para obtener los detalles de las órdenes de compra
$sql = "SELECT 
            pr.razon_social AS proveedor,
            oc.fecha_registro,
            doc.descripcion AS producto,
            doc.cantidad,
            doc.precio_unitario,
            (doc.cantidad * doc.precio_unitario) AS subtotal,
            ((doc.cantidad * doc.precio_unitario) * 0.18) AS igv,
            ((doc.cantidad * doc.precio_unitario) * 1.18) AS precio_total
        FROM orden_items doc
        INNER JOIN ordenes_compra oc ON doc.id_orden = oc.id_orden
        INNER JOIN proveedores pr ON oc.id_proveedor = pr.id_proveedor
        ORDER BY oc.fecha_registro DESC";

$resultado = $cnx->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Órdenes de Compra</title>
    <!-- Estilos de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4 text-center">Listado de Órdenes de Compra</h2>

    <nav class="navbar bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid justify-content-center">
          <form class="d-flex" role="search" method="POST">
            <input name="buscador" placeholder="Buscar con JS" id="codigo" class="form-control me-2 light-table-filter" type="search" aria-label="Search" data-table="table_id">
            <button name="buscador" class="btn btn-outline-light" type="submit">Buscar</button>
          </form>
        </div>
    </nav><br>

    <div class="table-responsive shadow-sm bg-white p-3 rounded">
        <table class="table table-striped table-hover align-middle table_id" id="tblDatos">
            <thead class="table-dark">
                <tr>
                    <th>Proveedor</th>
                    <th>Fecha Registro</th>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Precio Unitario</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">IGV (18%)</th>
                    <th class="text-end">Precio Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['proveedor']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['fecha_registro'])); ?></td>
                            <td><?php echo htmlspecialchars($row['producto']); ?></td>
                            <td class="text-center"><?php echo $row['cantidad']; ?></td>
                            <td class="text-end">S/ <?php echo number_format($row['precio_unitario'], 2); ?></td>
                            <td class="text-end">S/ <?php echo number_format($row['subtotal'], 2); ?></td>
                            <td class="text-end">S/ <?php echo number_format($row['igv'], 2); ?></td>
                            <td class="text-end fw-bold text-success">S/ <?php echo number_format($row['precio_total'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay órdenes de compra registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div><br>

    <div id="paginador" class="pagination justify-content-center"></div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/page.js"></script>
    <script type="text/javascript" src="js/buscador.js"></script>

</body>
</html>