<?php
include 'conexion.php';
include 'header.php';

// Filtros de búsqueda y categoría
$buscar = isset($_GET['buscar'])
    ? mysqli_real_escape_string($cnx, $_GET['buscar'])
    : '';

$tipo = isset($_GET['tipo'])
    ? mysqli_real_escape_string($cnx, $_GET['tipo'])
    : '';

// Configuración del paginador
$porPagina = 10;
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

if (!$pagina || $pagina < 1) {
    $pagina = 1;
}

// Construir los filtros
$where = " WHERE visible = 1";

if (!empty($buscar)) {
    $where .= " AND (nombre LIKE '%$buscar%' OR categoria LIKE '%$buscar%')";
}

if (!empty($tipo)) {
    $where .= " AND tipo = '$tipo'";
}

// Contar los productos que cumplen los filtros
$sqlTotal = "SELECT COUNT(*) AS total FROM articulo" . $where;
$resTotal = mysqli_query($cnx, $sqlTotal);
$totalProductos = (int) mysqli_fetch_assoc($resTotal)['total'];

// Calcular el total de páginas
$totalPaginas = (int) ceil($totalProductos / $porPagina);

// Evitar páginas fuera del rango
if ($totalPaginas > 0 && $pagina > $totalPaginas) {
    $pagina = $totalPaginas;
}

$inicio = ($pagina - 1) * $porPagina;

// Consultar solamente los productos de la página actual
$sql = "SELECT * FROM articulo" . $where .
       " ORDER BY codigo ASC LIMIT $inicio, $porPagina";

$resultado = mysqli_query($cnx, $sql);

// Conservar los filtros en los enlaces del paginador
$parametros = [];

if ($buscar !== '') {
    $parametros['buscar'] = $buscar;
}

if ($tipo !== '') {
    $parametros['tipo'] = $tipo;
}
?>
<div class="container-catalogo">
    <!-- Barra de Búsqueda -->
    <div class="search-section">
        <form action="catalogo.php" method="GET" class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar..." value="<?= htmlspecialchars($buscar) ?>">
        </form>
    </div>

    <!-- Miga de pan / Breadcrumb -->
    <div class="breadcrumb">
        <i class="fa fa-home"></i> &gt; CATÁLOGO
    </div>

    <div class="main-content">
        <!-- Sidebar Filtros -->
        <aside class="sidebar-filtros">
            <h3>Filtros</h3>
            
            <div class="filtro-grupo">
                <h4>TIPO DE JOYA</h4>
                <ul>
                    <li><a href="catalogo.php?tipo=Dije">Dije</a></li>
                    <li><a href="catalogo.php?tipo=Anillo">Anillo</a></li>
                    <li><a href="catalogo.php?tipo=Arete">Arete</a></li>
                    <li><a href="catalogo.php?tipo=Pulsera">Pulsera</a></li>
                </ul>
            </div>

            <div class="filtro-grupo">
                <h4>GÉNERO</h4>
                <ul>
                    <li><a href="#">Infantiles</a></li>
                    <li><a href="#">Adulto</a></li>
                    <li><a href="#">Mujer</a></li>
                </ul>
            </div>
        </aside>

        <!-- Grilla de Artículos -->
        <section class="grid-productos">
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($art = mysqli_fetch_assoc($resultado)): ?>
                    <div class="card-producto">
                        <div class="card-img">
                            <img src="art/<?= htmlspecialchars($art['foto']) ?>" alt="<?= htmlspecialchars($art['nombre']) ?>">
                        </div>
                        <div class="card-body">
                            <h3><?= htmlspecialchars($art['nombre']) ?></h3>
                            <p class="card-sub"><?= htmlspecialchars($art['tipo']) ?> | <?= htmlspecialchars($art['categoria']) ?></p>
                            <p class="card-precio">S/ <?= number_format($art['precio'], 2) ?></p>
                            <a href="detalle.php?codigo=<?= $art['codigo'] ?>" class="btn-mas-info">Mas info &rarr;</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron productos.</p>
            <?php endif; ?>
        </section>
    </div>

    <?php if ($totalPaginas > 1): ?>
        <nav class="paginador-container" aria-label="Paginación del catálogo">
            <ul class="paginador">
                <!-- Página anterior -->
                <li class="<?= ($pagina <= 1) ? 'deshabilitado' : '' ?>">
                    <?php $parametros['pagina'] = max(1, $pagina - 1); ?>
                    <a href="catalogo.php?<?= htmlspecialchars(http_build_query($parametros), ENT_QUOTES, 'UTF-8') ?>"
                        aria-label="Página anterior"
                        <?= ($pagina <= 1) ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                        &laquo; <span class="texto-paginacion">Anterior</span>
                    </a>
                </li>
                <!-- Números de página -->
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php $parametros['pagina'] = $i; ?>
                    <li class="<?= ($i == $pagina) ? 'actual' : '' ?>">
                        <a href="catalogo.php?<?= htmlspecialchars(http_build_query($parametros), ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($i == $pagina) ? 'aria-current="page"' : '' ?>>
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <!-- Página siguiente -->
                <li class="<?= ($pagina >= $totalPaginas) ? 'deshabilitado' : '' ?>">
                    <?php $parametros['pagina'] = min($totalPaginas, $pagina + 1); ?>
                    <a href="catalogo.php?<?= htmlspecialchars(http_build_query($parametros), ENT_QUOTES, 'UTF-8') ?>"
                        aria-label="Página siguiente"
                        <?= ($pagina >= $totalPaginas) ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                        <span class="texto-paginacion">Siguiente</span> &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

</body>
</html>