<?php
include "conexion.php";

date_default_timezone_set('America/Lima');

// Filtros
$buscar = trim($_GET['buscar'] ?? '');
$desde  = $_GET['desde'] ?? '';
$hasta  = $_GET['hasta'] ?? '';

$buscarSQL = $cnx->real_escape_string($buscar);
$desdeSQL  = $cnx->real_escape_string($desde);
$hastaSQL  = $cnx->real_escape_string($hasta);

$where = " WHERE 1=1 ";

if ($buscar !== '') {
    $where .= " AND (
        pe.cliente_nombre LIKE '%$buscarSQL%'
        OR pe.cliente_celular LIKE '%$buscarSQL%'
        OR ar.nombre LIKE '%$buscarSQL%'
    ) ";
}

if ($desde !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)) {
    $where .= " AND pe.fecha_pedido >= '$desdeSQL 00:00:00' ";
}

if ($hasta !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) {
    $where .= " AND pe.fecha_pedido <= '$hastaSQL 23:59:59' ";
}

// Pedidos filtrados
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
        $where
        ORDER BY pe.fecha_pedido DESC";

$resultado = $cnx->query($sql);

// Indicadores
$sqlStats = "SELECT
                COUNT(*) AS pedidos,
                COALESCE(SUM(pe.cantidad), 0) AS unidades,
                COALESCE(SUM(pe.total), 0) AS ventas,
                COALESCE(AVG(pe.total), 0) AS promedio
             FROM pedidos pe
             INNER JOIN articulo ar ON pe.id_producto = ar.codigo
             $where";

$stats = $cnx->query($sqlStats)->fetch_assoc();

// Ventas por mes (últimos 6 meses)
$sqlMes = "SELECT
            DATE_FORMAT(fecha_pedido, '%Y-%m') AS mes,
            SUM(total) AS ventas
           FROM pedidos
           WHERE fecha_pedido >= DATE_SUB(
               CURDATE(), INTERVAL 5 MONTH
           )
           GROUP BY DATE_FORMAT(fecha_pedido, '%Y-%m')
           ORDER BY mes";

$resMes = $cnx->query($sqlMes);
$meses = [];
$ventasMes = [];

while ($r = $resMes->fetch_assoc()) {
    $meses[] = $r['mes'];
    $ventasMes[] = (float)$r['ventas'];
}

// Productos más vendidos
$sqlTop = "SELECT ar.nombre,
                  SUM(pe.cantidad) AS unidades,
                  SUM(pe.total) AS ventas
           FROM pedidos pe
           INNER JOIN articulo ar ON pe.id_producto = ar.codigo
           $where
           GROUP BY ar.codigo, ar.nombre
           ORDER BY unidades DESC
           LIMIT 5";

$resTop = $cnx->query($sqlTop);

$productos = [];
$cantidades = [];

while ($r = $resTop->fetch_assoc()) {
    $productos[] = $r['nombre'];
    $cantidades[] = (int)$r['unidades'];
}

function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function soles($valor) {
    return 'S/ ' . number_format((float)$valor, 2);
}
?>

<?php
// Estadísticas generales del inventario
$sqlInventario = "SELECT
    COUNT(*) AS total_productos,
    COALESCE(SUM(stock), 0) AS unidades_stock,
    COALESCE(SUM(stock * precio), 0) AS valor_inventario,
    COALESCE(SUM(CASE WHEN stock <= 5 THEN 1 ELSE 0 END), 0)
        AS stock_bajo,
    COALESCE(SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END), 0)
        AS agotados
    FROM articulo";

$inventario = $cnx->query($sqlInventario)->fetch_assoc();

// Productos ordenados por menor stock
$sqlStock = "SELECT codigo, nombre, categoria, tipo, stock, precio
             FROM articulo
             WHERE stock <= 5
             ORDER BY stock ASC, nombre ASC";

$resStock = $cnx->query($sqlStock);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="refresh" content="60">
<title>Dashboard | Platería Lastra</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
:root {
    --oro: #b69a65;
    --fondo: #f5f5f2;
    --oscuro: #252722;
}
body {
    background: var(--fondo);
    color: var(--oscuro);
    font-family: Arial, sans-serif;
}
.sidebar {
    background: var(--oscuro);
    color: white;
    min-height: 100vh;
}
.logo {
    color: #e0c995;
    font-weight: 800;
    letter-spacing: 2px;
}
.card {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 3px 14px #00000008;
}
.stat-icon {
    background: #f1eadc;
    color: #98783e;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 23px;
}
.table thead th {
    background: #292b26;
    color: white;
    white-space: nowrap;
}
.btn-oro {
    background: #ad915b;
    color: white;
}
.btn-oro:hover {
    background: #8f7547;
    color: white;
}
</style>
</head>
<body>

<div class="container-fluid">
<div class="row">

<aside class="col-md-2 sidebar p-4">
    <h4 class="logo mb-1">◆ LASTRA</h4>
    <small class="text-secondary">PLATERÍA</small>
    <hr>
    <p><i class="bi bi-grid me-2"></i> Dashboard</p>
    <p><i class="bi bi-bag me-2"></i> Pedidos</p>
    <p><i class="bi bi-bar-chart me-2"></i> Ventas</p>
    <hr>
    <small class="text-secondary">
        Actualización automática cada 60 segundos
    </small>
</aside>

<main class="col-md-10 p-4 p-lg-5">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Panel de control</h2>
        <p class="text-secondary mb-0">
            Resumen comercial de Platería Lastra
        </p>
    </div>
    <div class="text-secondary">
        <i class="bi bi-clock"></i>
        <?= date('d/m/Y H:i') ?>
    </div>
</div>

<!-- INDICADORES -->
<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Ventas registradas</p>
                    <h3 class="fw-bold"><?= soles($stats['ventas']) ?></h3>
                </div>
                <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
            <small class="text-secondary">Suma de los pedidos filtrados</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Pedidos</p>
                    <h3 class="fw-bold"><?= (int)$stats['pedidos'] ?></h3>
                </div>
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            </div>
            <small class="text-secondary">Registros encontrados</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Unidades vendidas</p>
                    <h3 class="fw-bold"><?= (int)$stats['unidades'] ?></h3>
                </div>
                <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            </div>
            <small class="text-secondary">Suma de cantidades</small>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-secondary mb-2">Promedio por pedido</p>
                    <h3 class="fw-bold"><?= soles($stats['promedio']) ?></h3>
                </div>
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
            <small class="text-secondary">Importe medio registrado</small>
        </div>
    </div>

</div>

<!-- Incorpora las tarjetas de inventario -->
<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">
        <div class="card p-3 h-100">
            <p class="text-secondary mb-2">Productos registrados</p>
            <h3 class="fw-bold">
                <?= (int)$inventario['total_productos'] ?>
            </h3>
            <i class="bi bi-box-seam fs-3 text-secondary"></i>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card p-3 h-100">
            <p class="text-secondary mb-2">Unidades disponibles</p>
            <h3 class="fw-bold">
                <?= (int)$inventario['unidades_stock'] ?>
            </h3>
            <i class="bi bi-collection fs-3 text-secondary"></i>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card p-3 h-100">
            <p class="text-secondary mb-2">Valor del inventario</p>
            <h3 class="fw-bold">
                <?= soles($inventario['valor_inventario']) ?>
            </h3>
            <small class="text-secondary">
                Según el precio registrado
            </small>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card p-3 h-100">
            <p class="text-secondary mb-2">Stock bajo o agotado</p>
            <h3 class="fw-bold text-danger">
                <?= (int)$inventario['stock_bajo'] ?>
            </h3>
            <small class="text-secondary">
                <?= (int)$inventario['agotados'] ?> productos agotados
            </small>
        </div>
    </div>

</div>

<!-- FILTROS -->
<div class="card p-3 mb-4">
    <form method="GET" class="row g-3 align-items-end">

        <div class="col-lg-4">
            <label class="form-label">Buscar cliente o producto</label>
            <input type="search" name="buscar" class="form-control"
                   placeholder="Nombre, celular o producto"
                   value="<?= e($buscar) ?>">
        </div>

        <div class="col-sm-6 col-lg-3">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control"
                   value="<?= e($desde) ?>">
        </div>

        <div class="col-sm-6 col-lg-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control"
                   value="<?= e($hasta) ?>">
        </div>

        <div class="col-lg-2 d-flex gap-2">
            <button class="btn btn-oro flex-grow-1" type="submit">
                <i class="bi bi-search"></i> Filtrar
            </button>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

<!-- GRÁFICOS -->
<div class="row g-3 mb-4">

    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <h5 class="fw-bold">Evolución mensual de ventas</h5>
            <p class="text-secondary small">
                Últimos seis meses con registros
            </p>
            <canvas id="graficoVentas"></canvas>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <h5 class="fw-bold">Productos más vendidos</h5>
            <p class="text-secondary small">
                Según las unidades registradas
            </p>
            <canvas id="graficoProductos"></canvas>
        </div>
    </div>

</div>

<!-- TABLA DE PEDIDOS -->
<div class="card p-3 p-lg-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-1">Detalle de pedidos</h5>
            <small class="text-secondary">
                Información de clientes y productos
            </small>
        </div>
        <button class="btn btn-outline-dark btn-sm"
                onclick="exportarCSV()">
            <i class="bi bi-download"></i> Exportar CSV
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="tablaPedidos">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= e(date('d/m/Y H:i', strtotime($row['fecha_pedido']))) ?>
                    </td>
                    <td class="fw-semibold"><?= e($row['cliente_nombre']) ?></td>
                    <td><?= e($row['cliente_celular']) ?></td>
                    <td><?= e($row['cliente_direccion']) ?></td>
                    <td><?= e($row['producto']) ?></td>
                    <td><?= (int)$row['cantidad'] ?></td>
                    <td class="fw-bold text-success">
                        <?= soles($row['total']) ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">
                        No se encontraron pedidos.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div><br>

<!-- Añade una tabla de control de inventario -->
<div class="card p-3 p-lg-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1">
                <i class="bi bi-exclamation-triangle text-warning"></i>
                Alertas de inventario
            </h5>
            <small class="text-secondary">
                Productos con 5 unidades o menos
            </small>
        </div>
        <span class="badge bg-warning text-dark">
            <?= (int)$inventario['stock_bajo'] ?> productos
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Stock disponible</th>
                    <th>Precio de venta</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($resStock && $resStock->num_rows > 0): ?>

                <?php while ($p = $resStock->fetch_assoc()): ?>
                <tr>
                    <td class="fw-semibold">
                        <?= e($p['nombre']) ?>
                    </td>

                    <td><?= e($p['categoria']) ?></td>

                    <td>
                        <span class="fw-bold <?= (int)$p['stock'] === 0
                            ? 'text-danger' : 'text-warning' ?>">
                            <?= (int)$p['stock'] ?> unidades
                        </span>
                    </td>

                    <td><?= soles($p['precio']) ?></td>

                    <td>
                        <?php if ((int)$p['stock'] === 0): ?>
                            <span class="badge bg-danger">Agotado</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">
                                Reponer stock
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-success">
                        <i class="bi bi-check-circle"></i>
                        ¡Excelente! No hay productos con stock bajo.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<p class="text-center text-secondary small mt-4">
    © <?= date('Y') ?> Platería Lastra · Panel administrativo
</p>

</main>
</div>
</div>

<script>
const meses = <?= json_encode($meses, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const ventas = <?= json_encode($ventasMes) ?>;
const productos = <?= json_encode($productos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const cantidades = <?= json_encode($cantidades) ?>;

new Chart(document.getElementById('graficoVentas'), {
    type: 'line',
    data: {
        labels: meses,
        datasets: [{
            label: 'Ventas (S/)',
            data: ventas,
            borderColor: '#ad915b',
            backgroundColor: 'rgba(173,145,91,0.15)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('graficoProductos'), {
    type: 'bar',
    data: {
        labels: productos,
        datasets: [{
            label: 'Unidades vendidas',
            data: cantidades,
            backgroundColor: '#ad915b'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        scales: { x: { beginAtZero: true } }
    }
});

function exportarCSV() {
    const tabla = document.getElementById('tablaPedidos');
    const filas = [...tabla.querySelectorAll('tr')];

    const csv = filas.map(fila =>
        [...fila.querySelectorAll('th, td')].map(celda => {
            let texto = celda.innerText.trim().replace(/"/g, '""');

            // Evita que las hojas de cálculo interpreten
            // datos importados como fórmulas.
            if (/^[=+\-@\t\r]/.test(texto)) {
                texto = "'" + texto;
            }

            return '"' + texto + '"';
        }).join(';')
    ).join('\r\n');

    const blob = new Blob(['\uFEFF' + csv], {
        type: 'text/csv;charset=utf-8;'
    });

    const url = URL.createObjectURL(blob);
    const enlace = document.createElement('a');
    enlace.href = url;
    enlace.download = 'pedidos_lastra.csv';
    enlace.click();
    URL.revokeObjectURL(url);
}
</script>

</body>
</html>
