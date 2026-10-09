<?php 
include 'conexion.php';
include 'header.php';

// Filtro de búsqueda y categoría
$buscar = isset($_GET['buscar']) ? mysqli_real_escape_string($cnx, $_GET['buscar']) : '';
$tipo = isset($_GET['tipo']) ? mysqli_real_escape_string($cnx, $_GET['tipo']) : '';

$sql = "SELECT * FROM articulo WHERE 1=1";

if (!empty($buscar)) {
    $sql .= " AND (nombre LIKE '%$buscar%' OR categoria LIKE '%$buscar%')";
}

if (!empty($tipo)) {
    $sql .= " AND tipo = '$tipo'";
}

$resultado = mysqli_query($cnx, $sql);
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
</div>

</body>
</html>